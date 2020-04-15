<?php namespace App\Tests;
use App\Repository\UserRepository;
use App\Tests\AcceptanceTester;

class AdminCaseCest
{
    public function tryToTestAdminCase(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('auction.com');
        $I->amOnPage('/bid/list');
        $I->click('login');
        $I->fillField('email','admin@gmain.com');
        $I->fillField('password','zxcvb');
        $I->click('Sign in');
        $I->see('User index');
        $I->see('Create new');
        $I->click('show');
        $I->see('Id');
        $I->see('Email');
        $I->see('Roles');
        $I->see('Password');
        $I->see('Username');
        $I->click('edit');
        $I->see('Edit user');
        $I->click('back to list');
        $I->click('Logout');
        $I->amOnPage('/');
    }

    public function tryToAddNewUser(AcceptanceTester $I){

        $I->amOnPage('/');
        $I->click('auction.com');
        $I->click('login');
        $I->fillField('email','admin@gmain.com');
        $I->fillField('password','zxcvb');
        $I->click('Sign in');
        $I->click('Create new');
        $I->fillField('Email','testuseradmin@test.com');
        $I->fillField('Password','password');
        $I->fillField('Username','testuseradmin');
        $I->fillField('user[roles][0]','ROLE_USER');
        $I->click('Save');
        $I->see('testuseradmin');
    }
}
