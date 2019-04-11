<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use App\City;
use Tests\Browser\Pages\LoginPage;

class CityTest extends DuskTestCase
{
    /**
     * Test login
     *
     * @return void
     */
    public function test_index()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/cities')
                    ->assertSee('Create New City');
        });
    }

    public function test_create()
    {
        $city = factory(City::class)->make();
        $this->browse(function (Browser $browser) use ($city) {
            $browser->visit('/cities/create')
                    ->type('name', $city->name)
                    ->press('Save')
                    ->assertPathIs('/cities')
                    ->assertSee($city->name);
        });
    }

    public function test_edit_and_update()
    {
        $city = factory(City::class)->create();
        $newCityName = factory(City::class)->make()->name;
        $this->browse(function (Browser $browser) use ($city, $newCityName) {
            $browser->visit("/cities/{$city->id}/edit")
                    ->type('name', $newCityName)
                    ->press('Save')
                    ->assertPathIs('/cities')
                    ->assertSee($newCityName);
        });
    }

    public function test_delete()
    {
        $city = factory(City::class)->create();
        $this->browse(function (Browser $browser) use ($city) {
            $browser->visit("/cities")
                    ->press('Delete')
                    ->assertPathIs('/cities')
                    ->assertDontSee($city->name);
        });
    }
}
