<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user2 = new User();
        $user3 = new User();

        $user1->setEmail('regular@gmain.com');
        $user1->setRoles(['ROLE_USER']);
        $user1->setPassword($this->passwordEncoder->encodePassword($user1,'qwerty'));
        $user2->setEmail('staff@gmain.com');
        $user2->setRoles(['ROLE_STAFF']);
        $user2->setPassword($this->passwordEncoder->encodePassword($user1,'asdfg'));

        $user3->setEmail('admin@gmain.com');
        $user3->setRoles(['ROLE_ADMIN']);
        $user3->setPassword($this->passwordEncoder->encodePassword($user1,'zxcvb'));

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);

        $manager->flush();
    }
}