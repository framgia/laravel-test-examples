<?php

namespace Tests\Unit\Repo;

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
    protected function mockDatabaseConnection()
    {
        $connection = m::mock(Connection::class);
        $query = new Builder($connection, new SQLiteGrammar(), new Processor());

        $connection->allows()->table('streets')->andReturn($query->from('streets'));

        return $connection;
    }

    /**
     * @test
     */
    public function it_fetches_list_with_default_arguments()
    {
        $c = $this->mockDatabaseConnection();

        $repo = new StreetRepository($c);

        $testStreet = (object) [
            'id' => 1,
            'city_id' => 1,
            'name' => 'Test Street',
        ];

        $c->shouldReceive('select')->with(
            'select * from "streets" limit 10 offset 0',
            [],
            true
        )->andReturn([
            $testStreet,
        ]);

        $streets = $repo->fetchList();

        $this->assertInstanceOf(Collection::class, $streets);
        $this->assertEquals([$testStreet], $streets->all());
    }
}
