<?php namespace App\Tests;
use App\Tests\AcceptanceTester;

class StaffCaseCest
{
    public function tryToTestStaffCase(AcceptanceTester $I)
    {

        $I->amOnPage('/');
        $I->see('welcome');
        $I->click('auction.com');
        $I->click('login');
        $I->fillField('email','staff@gmain.com');
        $I->fillField('password','asdfg');
        $I->click('Sign in');
        $I->see('Auction index');
        $I->see('Create new');
        $I->click('show');
        $I->see('Auction');
        $I->see('Id');
        $I->see('Name');
        $I->see('Deadline');
        $I->click('edit');
        $I->click('back to list');
        $I->click('Logout');
    }
}
