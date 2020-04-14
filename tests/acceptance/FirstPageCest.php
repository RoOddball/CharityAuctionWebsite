<?php namespace App\Tests;
use App\Tests\AcceptanceTester;

class FirstPageCest
{
    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('welcome');
        $I->see('auction.com');
    }
}
