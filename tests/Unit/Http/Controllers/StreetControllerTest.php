<?php

namespace Tests\Unit\Http\Controllers;

use Mockery as m;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Repo\StreetRepositoryInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Street;
use App\Http\Controllers\StreetController;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StreetControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var \Mockery\Mock|Repo\StreetRepositoryInterface
     */
    protected $streetRepoMock;

    public function setUp()
    {
        $this->afterApplicationCreated(function () {
            $this->streetRepoMock = m::mock($this->app->make(StreetRepositoryInterface::class));
        });

        parent::setUp();
    }

    public function test_index_returns_view()
    {
        $controller = new StreetController($this->streetRepoMock);

        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->query->set('page', 3);

        $streets = factory(Street::class, 10)->make();
        $this->streetRepoMock->shouldReceive('paginateList')
            ->once()
            ->with(3)
            ->andReturn($streets);

        $view = $controller->index($request);

        $this->assertEquals('streets.list', $view->getName());
        $this->assertArraySubset(['streets' => $streets], $view->getData());
    }

    public function test_index_default_parameters()
    {
        $controller = new StreetController($this->streetRepoMock);

        $request = new Request();
        $streets = factory(Street::class, 10)->make();
        $this->streetRepoMock->shouldReceive('paginateList')
            ->once()
            ->with(1)
            ->andReturn($streets);

        $view = $controller->index($request);

        $this->assertEquals('streets.list', $view->getName());
        $this->assertArraySubset(['streets' => $streets], $view->getData());
    }
}
