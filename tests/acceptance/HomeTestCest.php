<?php


class HomeTestCest
{

    public function _before(AcceptanceTester $I)
    {

    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage('https://www.google.com/');
    $I->see('Recerche');
    }

}
