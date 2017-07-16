<?php

namespace Tests\Unit\Repo;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Mockery as m;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\SQLiteGrammar;
use Tests\TestCase;
use Illuminate\Database\Query\Processors\Processor;
use App\Repo\StreetRepository;
use Illuminate\Database\Connection;

class StreetRepositoryTest extends TestCase
{
    protected function makeRepository($connection)
    {
        return new StreetRepository($connection);
    }

    protected function getInstanceClass()
    {
        return \stdClass::class;
    }

    protected function mockDatabaseConnection()
    {
        $connection = m::mock(Connection::class);

        $connection->allows()
            ->table()
            ->with(m::any())
            ->andReturnUsing(function ($table) use ($connection) {
                return (new Builder(
                    $connection,
                    new SQLiteGrammar(),
                    new Processor()
                ))->from($table);
            });

        return $connection;
    }

    public function test_it_fetches_list_with_default_arguments()
    {
        $c = $this->mockDatabaseConnection();

        $repo = $this->makeRepository($c);

        $testStreet = (object) [
            'id' => 1,
            'city_id' => 1,
            'name' => 'Test Street',
        ];

        $c->shouldReceive('select')
            ->once()
            ->withArgs([
                'select * from "streets" limit 10 offset 0',
                [],
                m::any(),
            ])
            ->andReturn([
                $testStreet,
            ]);

        $streets = $repo->fetchList();

        $this->assertInstanceOf(Collection::class, $streets);
        $this->assertContainsOnlyInstancesOf($this->getInstanceClass(), $streets);
        $this->assertEquals([$testStreet], $streets->all());
    }

    public function test_it_fetches_pagination()
    {
        $c = $this->mockDatabaseConnection();

        $repo = $this->makeRepository($c);

        $testStreet = (object) [
            'id' => 1,
            'city_id' => 1,
            'name' => 'Test Street',
        ];

        $c->shouldReceive('select')
            ->once()
            ->withArgs([
                'select count(*) as aggregate from "streets"',
                [],
                m::any(),
            ])
            ->andReturn([
                (object) [
                    'aggregate' => 1,
                ]
            ]);

        $c->shouldReceive('select')
            ->once()
            ->withArgs([
                'select * from "streets" limit 10 offset 0',
                [],
                m::any(),
            ])
            ->andReturn([
                $testStreet,
            ]);

        $streets = $repo->paginateList();

        $this->assertInstanceOf(LengthAwarePaginator::class, $streets);
        $this->assertContainsOnlyInstancesOf($this->getInstanceClass(), $streets);
        $this->assertEquals([$testStreet], $streets->all());
    }

    public function test_it_fetches_single_instance()
    {
        $c = $this->mockDatabaseConnection();

        $repo = $this->makeRepository($c);

        $testStreet = (object) [
            'id' => 1,
            'city_id' => 1,
            'name' => 'Test Street',
        ];

        $c->shouldReceive('select')
            ->once()
            ->withArgs([
                'select * from "streets" where "id" = ? limit 1',
                [10],
                m::any(),
            ])
            ->andReturn([
                $testStreet,
            ]);

        $street = $repo->fetchOneById(10);

        $this->assertInstanceOf($this->getInstanceClass(), $street);
        $this->assertEquals($testStreet, $street);

        $c->shouldReceive('select')
            ->once()
            ->withArgs([
                'select * from "streets" where "id" = ? limit 1',
                [15],
                m::any(),
            ])
            ->andReturn([]);

        $street = $repo->fetchOneById(15);

        $this->assertNull($street);
    }

    public function test_it_fetches_list_by_city_id()
    {
        $c = $this->mockDatabaseConnection();

        $repo = $this->makeRepository($c);

        $testStreet = (object) [
            'id' => 1,
            'city_id' => 2,
            'name' => 'Test Street',
        ];

        $c->shouldReceive('select')
            ->once()
            ->withArgs([
                'select * from "streets" where "city_id" = ? limit 10 offset 0',
                [2],
                m::any(),
            ])
            ->andReturn([
                $testStreet,
            ]);

        $streets = $repo->fetchListByCity(2);

        $this->assertInstanceOf(Collection::class, $streets);
        $this->assertContainsOnlyInstancesOf($this->getInstanceClass(), $streets);
        $this->assertEquals([$testStreet], $streets->all());
    }

    public function test_it_fetches_list_by_conditions()
    {
        $c = $this->mockDatabaseConnection();

        $repo = $this->makeRepository($c);

        $testStreet = (object) [
            'id' => 1,
            'city_id' => 2,
            'name' => 'Test Street',
        ];



        $c->shouldReceive('select')
            ->once()
            ->withArgs([
                'select * from "streets" where "city_id" = ? and "name" = ? limit 10 offset 0',
                [2, 'Test Street'],
                m::any(),
            ])
            ->andReturn([
                $testStreet,
            ]);

        $streets = $repo->fetchListByFields([
            'city_id' => 2,
            'name' => 'Test Street',
        ]);

        $this->assertInstanceOf(Collection::class, $streets);
        $this->assertContainsOnlyInstancesOf($this->getInstanceClass(), $streets);
        $this->assertEquals([$testStreet], $streets->all());
    }

    public function test_it_stores_new_instance()
    {
        $c = $this->mockDatabaseConnection();

        $repo = $this->makeRepository($c);

        $testStreet = [
            'city_id' => 2,
            'name' => 'Test Street',
        ];

        $mockPdo = m::mock(\PDO::class);
        $c->shouldReceive('getPdo')->once()->andReturn($mockPdo);
        $mockPdo->shouldReceive('lastInsertId')->once()->andReturn(123);

        $c->shouldReceive('insert')
            ->once()
            ->withArgs([
                'insert into "streets" ("city_id", "name") values (?, ?)',
                array_values($testStreet),
            ])
            ->andReturn(true);

        $id = $repo->store($testStreet);

        $this->assertEquals(123, $id);
    }

    public function test_it_stores_many_instances()
    {
        $c = $this->mockDatabaseConnection();

        $repo = $this->makeRepository($c);

        $testStreets = [
            [
                'city_id' => 2,
                'name' => 'Test Street',
            ],
            [
                'city_id' => 3,
                'name' => 'Test Street 2',
            ],
        ];

        $c->shouldReceive('insert')
            ->once()
            ->withArgs([
                'insert into "streets" ("city_id", "name") '.
                'select ? as "city_id", ? as "name" union all select ? as "city_id", ? as "name"',
                array_values(array_flatten($testStreets)),
            ])
            ->andReturn(true);

        $result = $repo->storeMany(collect($testStreets));

        $this->assertEquals(true, $result);
    }

    public function test_it_updates_single_instance()
    {
        $c = $this->mockDatabaseConnection();

        $repo = $this->makeRepository($c);

        $update = [
            'name' => 'Test Street Updated',
        ];

        $c->shouldReceive('update')
            ->once()
            ->withArgs([
                'update "streets" set "name" = ? where "id" = ?',
                ['Test Street Updated', 2],
            ])
            ->andReturn(1);

        $result = $repo->updateById(2, $update);
        $this->assertEquals(1, $result);
    }

    public function test_it_updates_many_instances()
    {
        $c = $this->mockDatabaseConnection();

        $repo = $this->makeRepository($c);

        $update = [
            'name' => 'Test Street Updated',
        ];

        $c->shouldReceive('update')
            ->once()
            ->withArgs([
                'update "streets" set "name" = ? where "city_id" = ? and "name" = ?',
                ['Test Street Updated', 2, 'test'],
            ])
            ->andReturn(10);

        $result = $repo->updateMany([
            'city_id' => 2,
            'name' => 'test',
        ], $update);
        $this->assertEquals(10, $result);
    }
}
