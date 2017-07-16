<?php

namespace Tests\Unit\Http\Controllers;

use App\Events\CityShown;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\RedirectResponse;
use Mockery as m;
use App\City;
use Illuminate\Database\Connection;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\CityController;

class CityControllerTest extends TestCase
{
    /**
     * @var \Mockery\Mock|\Illuminate\Database\Connection
     */
    protected $db;

    public function setUp()
    {
        $this->afterApplicationCreated(function () {
            $this->db = m::mock(
                Connection::class.'[select,update,insert,delete]',
                [m::mock(\PDO::class)]
            );

            $manager = $this->app['db'];
            $manager->setDefaultConnection('mock');

            $r = new \ReflectionClass($manager);
            $p = $r->getProperty('connections');
            $p->setAccessible(true);
            $list = $p->getValue($manager);
            $list['mock'] = $this->db;
            $p->setValue($manager, $list);
        });

        parent::setUp();
    }

    public function test_index_returns_view()
    {
        $controller = new CityController();

        $this->db->shouldReceive('select')->once()->withArgs([
            'select count(*) as aggregate from "cities"',
            [],
            m::any(),
        ])->andReturn((object) ['aggregate' => 10]);

        $view = $controller->index();

        $this->assertEquals('cities.list', $view->getName());
        $this->assertArrayHasKey('cities', $view->getData());
    }


    public function test_it_stores_new_city()
    {
        $controller = new CityController();

        $data = [
            'name' => 'New City',
        ];

        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        // Mock Validation Presence Query
        $this->db->shouldReceive('select')->once();

        $this->db->getPdo()->shouldReceive('lastInsertId')->andReturn(333);
        $this->db->shouldReceive('insert')->once()
            ->withArgs([
                'insert into "cities" ("name", "updated_at", "created_at") values (?, ?, ?)',
                m::on(function ($arg) {
                    return is_array($arg) &&
                        $arg[0] == 'New City';
                })
            ])
            ->andReturn(true);

        /** @var RedirectResponse $response */
        $response = $controller->store($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('cities.index'), $response->headers->get('Location'));
        $this->assertEquals(333, $response->getSession()->get('created'));
    }

    public function test_it_throws_error_on_duplicate_name()
    {
        $controller = new CityController();

        $data = [
            'name' => 'New City',
        ];

        $this->db->shouldReceive('select')->once()->withArgs([
            'select count(*) as aggregate from "cities" where "name" = ?',
            ['New City'],
            m::any(),
        ])->andReturn([(object) ['aggregate' => 1]]);

        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        $this->expectException(ValidationException::class);
        $controller->store($request);
    }

    public function test_it_fires_event_and_shows_city()
    {
        $controller = new CityController();
        $city = new City(['id' => 111]);

        $events = m::mock(Dispatcher::class);
        $events->shouldReceive('dispatch')->with(m::on(function ($arg) use ($city) {
            return $arg instanceof CityShown && $arg->city === $city;
        }));
        $view = $controller->show($events, $city);
        $this->assertEquals('cities.item', $view->getName());
        $this->assertArrayHasKey('city', $view->getData());
    }
}
