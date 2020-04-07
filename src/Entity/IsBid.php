<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IsBidRepository")
 */
class IsBid
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBid;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Auction", mappedBy="isBid")
     */
    private $auctions;

    public function __construct()
    {
        $this->auctions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsBid(): ?bool
    {
        return $this->isBid;
    }

    public function setIsBid(bool $isBid): self
    {
        $this->isBid = $isBid;

        return $this;
    }

    /**
     * @return Collection|Auction[]
     */
    public function getAuctions(): Collection
    {
        return $this->auctions;
    }

    public function addAuction(Auction $auction): self
    {
        if (!$this->auctions->contains($auction)) {
            $this->auctions[] = $auction;
            $auction->setIsBid($this);
        }

        return $this;
    }

    public function removeAuction(Auction $auction): self
    {
        if ($this->auctions->contains($auction)) {
            $this->auctions->removeElement($auction);
            // set the owning side to null (unless already changed)
            if ($auction->getIsBid() === $this) {
                $auction->setIsBid(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        if($this->getIsBid()==true)
            return 'yes';
        else
            return 'no';
    }
}
