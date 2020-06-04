<?php

class LandingCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function LandingWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Codigitar');
    }
}
