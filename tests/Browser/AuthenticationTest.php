<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use Tests\Browser\Pages\LoginPage;

class AuthenticationTest extends DuskTestCase
{
    /**
     * Test login
     *
     * @return void
     */
    public function test_login()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'secret')
                    ->press('Login')
                    ->assertSee('You are logged in!');
        });
    }

    /**
     * Test register
     *
     * @return void
     */
   public function test_register()
   {
       // Clear cookie for fresh navigation
       static::$browsers->first()->driver->manage()->deleteAllCookies();

       // Make User data but not save
       $user = factory(User::class)->make();

        // Try to register
       $this->browse(function (Browser $browser) use ($user) {
           $browser->visit('/register')
               ->type('name', $user->name)
               ->type('email', $user->email)
               ->type('password', 'secret')
               ->type('password_confirmation', 'secret')
               ->click('[type="submit"]')
               ->assertSee('You are logged in!');
       });
   }
}
