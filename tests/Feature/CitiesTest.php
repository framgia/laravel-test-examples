<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\WithStubUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CitiesTest extends TestCase
{
    use DatabaseTransactions, WithStubUser;

    /**
     * @test
     */
    public function index_authentication()
    {
        $this->assertAuthenticationRequired('/cities');
        $this->assertAuthenticationRequired('/cities/create');
        $this->assertAuthenticationRequired('/cities', 'post');
        $this->assertAuthenticationRequired('/cities/1');
        $this->assertAuthenticationRequired('/cities/1/edit');
        $this->assertAuthenticationRequired('/cities/1', 'put');
        $this->assertAuthenticationRequired('/cities/1', 'delete');
    }

    /**
     * @test
     */
    public function index_view()
    {
        $user = $this->createStubUser();
        $response = $this->actingAs($user)->get('/cities');

        $response->assertStatus(200);
        $response->assertViewHas('cities');
        $response->assertSee('<span>Cities</span>');
    }
}