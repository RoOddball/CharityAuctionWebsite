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
        $user4 = new User();
        $user5 = new User();
        $user6 = new User();

        $user1->setUsername('Jim');
        $user1->setEmail('regular@gmain.com');
        $user1->setRoles(['ROLE_USER']);
        $user1->setPassword($this->passwordEncoder->encodePassword($user1,'qwerty'));

        $user2->setUsername('Ben');
        $user2->setEmail('staff@gmain.com');
        $user2->setRoles(['ROLE_STAFF']);
        $user2->setPassword($this->passwordEncoder->encodePassword($user1,'asdfg'));

        $user3->setUsername('Tom');
        $user3->setEmail('admin@gmain.com');
        $user3->setRoles(['ROLE_ADMIN']);
        $user3->setPassword($this->passwordEncoder->encodePassword($user1,'zxcvb'));

        $user4->setUsername('May');
        $user4->setEmail('regularette@gmain.com');
        $user4->setRoles(['ROLE_USER']);
        $user4->setPassword($this->passwordEncoder->encodePassword($user1,'abx'));

        $user5->setUsername('Fay');
        $user5->setEmail('regularinne@gmain.com');
        $user5->setRoles(['ROLE_USER']);
        $user5->setPassword($this->passwordEncoder->encodePassword($user1,'xyz'));

        $user6->setUsername('Matt');
        $user6->setEmail('matt@gmain.com');
        $user6->setRoles(['ROLE_DEV']);
        $user6->setPassword($this->passwordEncoder->encodePassword($user1,'dev'));


        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);
        $manager->persist($user5);

        $manager->flush();
    }
}
