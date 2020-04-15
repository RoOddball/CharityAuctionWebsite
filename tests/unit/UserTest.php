<?php namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use Codeception\Module\Doctrine2;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \App\Tests\UnitTester
     */
    protected $tester;



    protected function _before()
    {

    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {

        $user = new User();
        $user->setRoles(["ROLE_USER"]);
        $user->setUsername("tester");
        $user->setPassword("test");
        $user->setEmail("tester@test.com");
    }
}