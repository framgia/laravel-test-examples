<?php

namespace Tests\Unit\Http\Controllers;

use App\Events\CityShown;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\RedirectResponse;
use Mockery as m;
use App\City;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
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

    /**
     * @var \Mockery\Mock|App\City
     */
    protected $cityMock;

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

            $this->cityMock = m::mock(City::class . '[update, delete]');
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

    public function test_store_new_city_throw_query_exception()
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

        $this->db->shouldReceive('insert')->once()
            ->withArgs([
                'insert into "cities" ("name", "updated_at", "created_at") values (?, ?, ?)',
                m::on(function ($arg) {
                    return is_array($arg) &&
                        $arg[0] == 'New City';
                })
            ])
            ->andReturnUsing(function() {
                throw new QueryException('', [], new \Exception);
            });

        /** @var RedirectResponse $response */
        $response = $controller->store($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(config('app.url'), $response->headers->get('Location'));
        $this->assertArrayHasKey('system', $response->getSession()->get('errors')->messages());
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

    public function test_create_returns_view()
    {
        $controller = new CityController();

        $view = $controller->create();

        $this->assertEquals('cities.form', $view->getName());
        $this->assertArraySubset(['city' => null], $view->getData());
    }

    public function test_edit_city()
    {
        $cityInfo = ['id' => 1, 'name' => 'New City'];
        $city = new City($cityInfo);

        $controller = new CityController();

        $view = $controller->edit($city);
        $this->assertEquals('cities.form', $view->getName());
        $this->assertArraySubset(['city' => $city], $view->getData());
    }

    public function test_update_existing_city()
    {
        $controller = new CityController();

        $data = [
            'id' => 1,
            'name' => 'New City',
        ];

        $city = $this->cityMock->forceFill(['id' => 1, 'name' => 'Old City']);
        $newCity = (new City())->forceFill(['id' => 1, 'name' => $data['name']]);

        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        // Mock Validation Presence Query
        $this->db->shouldReceive('select')->once()->withArgs([
            'select count(*) as aggregate from "cities" where "name" = ? and "id" <> ?',
            [$data['name'], $data['id']],
            m::any(),
        ])->andReturn([(object) ['aggregate' => 0]]);

        $this->cityMock->shouldReceive('update')->once()->withArgs([
            m::on(function($arg) {
                return is_array($arg) && $arg['name'] == 'New City';
            }
        )])->andReturn($newCity);

        $this->db->getPdo()->shouldReceive('lastInsertId')->andReturn($data['id']);

        $response = $controller->update($request, $city);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('cities.index'), $response->headers->get('Location'));
        $this->assertEquals($data['id'], $response->getSession()->get('updated'));
    }

    public function test_update_throws_error_on_duplicate_name()
    {
        $controller = new CityController();

        $data = [
            'id' => 1,
            'name' => 'New City',
        ];

        $city = new City();
        $city->forceFill(['id' => 1, 'name' => $data['name']]);

        $this->db->shouldReceive('select')->once()->withArgs([
            'select count(*) as aggregate from "cities" where "name" = ? and "id" <> ?',
            [$data['name'], $data['id']],
            m::any(),
        ])->andReturn([(object) ['aggregate' => 1]]);

        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        $this->expectException(ValidationException::class);
        $controller->update($request, $city);
    }

    public function test_update_existing_city_throw_query_exception()
    {
        $controller = new CityController();

        $data = [
            'id' => 1,
            'name' => 'New City',
        ];

        $city = $this->cityMock->forceFill(['id' => 1, 'name' => 'Old City']);

        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        // Mock Validation Presence Query
        $this->db->shouldReceive('select')->once()->withArgs([
            'select count(*) as aggregate from "cities" where "name" = ? and "id" <> ?',
            [$data['name'], $data['id']],
            m::any(),
        ])->andReturn([(object) ['aggregate' => 0]]);

        $this->cityMock->shouldReceive('update')->once()->withArgs([
            m::on(function($arg) {
                return is_array($arg) && $arg['name'] == 'New City';
            }
        )])->andThrow(new QueryException('', [], new \Exception));

        $response = $controller->update($request, $city);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(config('app.url'), $response->headers->get('Location'));
        $this->assertArrayHasKey('system', $response->getSession()->get('errors')->messages());
    }

    public function test_destroy_existing_city()
    {
        $controller = new CityController();

        $data = [
            'id' => 1,
            'name' => 'New City',
        ];

        $city = $this->cityMock->forceFill($data);

        $this->cityMock->shouldReceive('delete')->once()->andReturn(true);

        $response = $controller->destroy($city);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('cities.index'), $response->headers->get('Location'));
        $this->assertEquals($data['id'], $response->getSession()->get('deleted'));
    }

    public function test_destroy_existing_city_throw_query_exception()
    {
        $controller = new CityController();

        $data = [
            'id' => 1,
            'name' => 'New City',
        ];

        $city = $this->cityMock->forceFill($data);

        $this->cityMock->shouldReceive('delete')->once()->andReturnUsing(function() {
            throw new QueryException('', [], new \Exception);
        });

        $response = $controller->destroy($city);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(config('app.url'), $response->headers->get('Location'));
        $this->assertArrayHasKey('system', $response->getSession()->get('errors')->messages());
    }
}
