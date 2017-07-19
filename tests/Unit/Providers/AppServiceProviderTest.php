<?php

namespace Tests\Unit\Providers;

use App\Repo\StreetRepository;
use App\Repo\StreetRepositoryInterface;
use Illuminate\Database\Connection;
use Mockery as m;
use Tests\SimpleTestCase;
use Illuminate\Container\Container;
use App\Providers\AppServiceProvider;
use Illuminate\Database\DatabaseManager;

class AppServiceProviderTest extends SimpleTestCase
{
    public function test_it_registers_all_instances()
    {
        $container = new Container();
        $provider = new AppServiceProvider($container);

        $container->bind('db', function () {
            $mock = m::mock(DatabaseManager::class);
            $mock->shouldReceive('connection')->andReturn(m::mock(Connection::class));
            return $mock;
        });

        $provider->register();
        $this->assertTrue($container->bound(StreetRepository::class));
        $this->assertTrue($container->bound(StreetRepositoryInterface::class));
        $this->assertTrue($container->bound('streets'));

        $streets = $container->make(StreetRepositoryInterface::class);
        $this->assertInstanceOf(StreetRepositoryInterface::class, $streets);
        $this->assertEquals($streets, $container->make(StreetRepository::class));
        $this->assertEquals($streets, $container->make('streets'));
    }
}
