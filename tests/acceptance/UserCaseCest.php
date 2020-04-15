<?php namespace App\Tests;
use App\Tests\AcceptanceTester;

class UserCaseCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('auction.com');
        $I->click('login');
        $I->fillField('email','test.user@test.com');
        $I->fillField('password','regular');
        $I->click('Sign in');
        $I->see('Email could not be found.');
        $I->click('back');
        $I->see('List of live auctions');
    }

    // tests
    public function tryToTestRegisterAndUpdateThePassword(AcceptanceTester $I)
    {
        $I->amOnPage('/bid/list');
        $I->click('register');
        $I->amOnPage('/register');
        $I->fillField('email','test.user@test.com');
        $I->fillField('username','testUser');
        $I->fillField('password','regular');
        $I->click('Register');
        $I->amOnPage('/bid/list');
        $I->see('List of live auctions');
        $I->click('register');
        $I->amOnPage('/register');
        $I->fillField('email','test.user@test.com');
        $I->fillField('username','testUser');
        $I->fillField('password','regular');
        $I->click('Register');
        $I->see('credentials already in use');
        $I->click('back');
        $I->amOnPage('/bid/list');
        $I->click('login');
        $I->amOnPage('/login');
        $I->fillField('email','test.user@test.com');
        $I->fillField('password','regular');
        $I->click('Sign in');
        $I->see('You are logged in as testUser');
        $I->click('logout');
        $I->amOnPage('/');
        $I->see('welcome');
        $I->amOnPage('/');
        $I->click('auction.com');
        $I->click('login');
        $I->amOnPage('/login');
        $I->fillField('email','test.user@test.com');
        $I->fillField('password','regular');
        $I->click('Sign in');
        $I->amOnPage('/bid/userlist');
        $I->see('You are logged in as testUser');
        $I->click('reset credentials');
        $I->see('Edit User');
        $I->fillField('email','test.user@test.com');
        $I->fillField('username','testUser');
        $I->fillField('password','updatedregular');
        $I->click('Update');
        $I->amOnPage('/bid/list');
        $I->see('List of live auctions');
        $I->click('login');
        $I->click('logout');
        $I->click('auction.com');
        $I->click('login');
        $I->see('Please sign in ');
        $I->fillField('email','test.user@test.com');
        $I->fillField('password','regular');
        $I->click('Sign in');
        $I->see('invalid credentials');
        $I->fillField('email','test.user@test.com');
        $I->fillField('password','updatedregular');
        $I->click('Sign in');
        $I->see('You are logged in as testUser');
        $I->click('reset credentials');
        $I->click('Delete');
    }
//i hate this tool
//tried to put the tests in separate methods but if one modifies the DB
//the following one does not acknowledge it

//    public function tryToTestResetOwnCredentials(AcceptanceTester $I){
//
//        $I->amOnPage('/');
//        $I->click('auction.com');
//        $I->click('login');
//        $I->amOnPage('/login');
//        $I->fillField('email','test.user@test.com');
//        $I->fillField('password','regular');
//        $I->click('Sign in');
//        $I->amOnPage('/bid/userlist');
//        $I->see('You are logged in as testUser');
//        $I->click('reset credentials');
//        $I->see('Edit User');
//        $I->fillField('email','test.user@test.com');
//        $I->fillField('username','testUser');
//        $I->fillField('password','updatedregular');
//        $I->click('Update');
//        $I->amOnPage('/bid/list');
//        $I->see('List of live auctions');
//        $I->click('login');
//        $I->click('logout');
//        $I->click('auction.com');
//        $I->click('login');
//        $I->see('Please sign in ');
//        $I->fillField('email','test.user@test.com');
//        $I->fillField('password','regular');
//        $I->click('Sign in');
//        $I->see('invalid credentials');
//        $I->fillField('email','test.user@test.com');
//        $I->fillField('password','updatedregular');
//        $I->click('Sign in');
//        $I->see('You are logged in as testUser');
//        $I->click('reset credentials');
//        $I->click('Delete');
//    }
}
