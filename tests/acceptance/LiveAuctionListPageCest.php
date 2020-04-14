<?php namespace App\Tests;
use App\Tests\AcceptanceTester;

class LiveAuctionListPageCest
{
    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('auction.com');
        $I->see('List of live auctions');
        $I->cantSeeLink('app_register');
        $I->cantSeeLink('app_login');
        $I->click('show');
        $I->see('Id');
        $I->see('back to list');
        $I->click('back');
        $I->see('List of live auctions');
    }
}
