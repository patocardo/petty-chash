<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return voidging
     */
    public function testLo()
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/login')
                ->waitForText('Log In')
                ->type('email', getenv('SUDO_EMAIL'))
                ->type('password', getenv('SUDO_PASS'))
                ->click('button[type="submit"]')
                ->waitForText('Home')
                ->assertSee('logged in');
        });
    }
}
