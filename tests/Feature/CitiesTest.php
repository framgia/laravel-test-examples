<?php

namespace Tests\Feature;

use App\City;
use Tests\TestCase;
use Tests\WithStubUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CitiesTest extends TestCase
{
    use DatabaseTransactions, WithStubUser;

    public function test_index_authentication()
    {
        $this->assertAuthenticationRequired('/cities');
        $this->assertAuthenticationRequired('/cities/create');
        $this->assertAuthenticationRequired('/cities', 'post');
        $this->assertAuthenticationRequired('/cities/1');
        $this->assertAuthenticationRequired('/cities/1/edit');
        $this->assertAuthenticationRequired('/cities/1', 'put');
        $this->assertAuthenticationRequired('/cities/1', 'delete');
    }

    public function test_index_view()
    {
        $user = $this->createStubUser();
        $response = $this->actingAs($user)->get('/cities');

        $response->assertStatus(200);
        $response->assertViewHas('cities');
        $response->assertSee('<span>Cities</span>');
    }

    public function test_authenticated_user_can_create_new_city()
    {
        $this->actingAs($this->createStubUser());

        $this->get('/cities/create')
             ->assertStatus(200)
             ->assertViewIs('cities.form')
             ->assertViewHas('city', null);

        $this->post('/cities', ['name' => 'Hanoi'])
             ->assertRedirect('/cities')
             ->assertSessionHas('created', City::latest()->first()->id);
    }

    public function test_it_checks_for_invalid_city()
    {
        $this->actingAs($this->createStubUser());

        $this->postJson('/cities', ['name' => ''])
             ->assertStatus(422)
             ->assertJsonStructure(['message', 'errors' => ['name']]);
    }

    public function test_authenticated_user_can_view_a_city()
    {
        $city = $this->createCity();

        $this->get("/cities/{$city->id}")
             ->assertStatus(200)
             ->assertViewIs('cities.item')
             ->assertViewHas('city');
    }

    public function test_authenticated_user_can_edit_an_existing_city()
    {
        $city = $this->createCity();

        $this->get("/cities/{$city->id}/edit")
             ->assertStatus(200)
             ->assertViewIs('cities.form')
             ->assertViewHas('city');

        $this->put("/cities/{$city->id}", ['name' => 'London'])
             ->assertRedirect('/cities')
             ->assertSessionHas('updated', $city->id);
    }

    public function test_authenticated_user_can_delete_an_existing_city()
    {
        $city = $this->createCity();

        $this->delete("/cities/{$city->id}")
             ->assertRedirect('/cities')
             ->assertSessionHas('deleted', $city->id);
    }

    private function createCity($authenticated = true)
    {
        $city = factory(City::class)->create();

        if ($authenticated) {
            $this->actingAs($this->createStubUser());
        }

        return $city;
    }
}
