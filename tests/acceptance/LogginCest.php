<?php

class LogginCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function UserGoesInside(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->see('Iniciar');
        $I->fillField("//input[@type='text']", $_ENV['SUDO_EMAIL']);
        $I->fillField("//input[@type='password']", $_ENV['SUDO_PASS']);
        $I->click('button[type=submit]');
        $I->see('iniciado');
    }
}
