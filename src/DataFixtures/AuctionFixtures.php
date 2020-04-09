<?php

namespace App\DataFixtures;

use App\Entity\Auction;
use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AuctionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $liveState = new State();
        $liveState->setName('live');

        $draftState = new State();
        $draftState->setName('draft');

        $passedState = new State();
        $passedState->setName('passed');

        $auction1 = new Auction();
        $auction1->setName('Venician 16th century night bowl');
        $auction1->setState($liveState);
        $auction1->setDeadline(new \DateTime("2020-5-22 13:05:21"));

        $auction2= new Auction();
        $auction2->setName('British 18th century horse blanket');
        $auction2->setState($draftState);
        $auction2->setDeadline(new \DateTime("2020-4-25 18:05:21"));

        $auction3 = new Auction();
        $auction3->setName('Russian 12th century distillator');
        $auction3->setState($passedState);
        $auction3->setDeadline(new \DateTime("2020-3-3 21:05:55"));

        $auction4 = new Auction();
        $auction4->setName('Yemeni 13th century surgeon kit');
        $auction4->setState($liveState);
        $auction4->setDeadline(new \DateTime("2020-6-4 20:25:15"));

        $manager->persist($auction1);
        $manager->persist($auction2);
        $manager->persist($auction3);
        $manager->persist($auction4);
        $manager->persist($liveState);
        $manager->persist($passedState);
        $manager->persist($draftState);

        $faker = Factory::create();
        $numOfAuctions = 20;

        for($i=0; $i < $numOfAuctions; $i++){

            $auctionItem = $faker->country .' '. $faker->century .'th'.' century '.' '. $faker->word;
            $auctionDeadline = $faker->dateTime;

            if($i%4==0)
                $auctionState = $draftState;
            else if($i%3==0)
                $auctionState = $liveState;
            else
                $auctionState = $passedState;

            $auction = new Auction();
            $auction->setName($auctionItem);
            $auction->setDeadline($auctionDeadline);
            $auction->setState($auctionState);
            $manager->persist($auction);
        }

        $manager->flush();
    }
}
