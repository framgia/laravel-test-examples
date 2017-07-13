<?php

namespace Tests\Unit\Http\Controllers;

use App\City;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\CityController;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\WithClearQueryLog;

class CityControllerTest extends TestCase
{
    use DatabaseTransactions, WithClearQueryLog;

    public function setUp()
    {
        $this->afterApplicationCreated(function () { $this->setUpQueryLog(); });
        $this->beforeApplicationDestroyed(function () { $this->tearDownQueryLog(); });
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_stores_new_city()
    {
        $controller = new CityController();

        $data = [
            'name' => 'New City',
        ];

        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        $controller->store($request);
        $log = $this->db->getQueryLog();

        $this->assertDatabaseHas('cities', $data);
        // First query is validation.
        $this->assertEquals(2, count($log));
    }

    /**
     * @test
     */
    public function it_throws_error_on_duplicate_name()
    {
        $controller = new CityController();

        $data = [
            'name' => 'New City',
        ];

        City::create($data);

        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        $this->expectException(ValidationException::class);
        $controller->store($request);
    }
}
