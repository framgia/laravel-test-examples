<?php

namespace Tests\Unit\Http\Controllers;

use Mockery as m;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Repo\StreetRepositoryInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Street;
use App\Http\Controllers\StreetController;

class StreetControllerTest extends TestCase
{

    /**
     * @var \Mockery\Mock|Repo\StreetRepositoryInterface
     */
    protected $streetRepoMock;

    public function setUp()
    {
        $this->afterApplicationCreated(function () {
            $this->streetRepoMock = m::mock(StreetRepositoryInterface::class);
        });

        parent::setUp();
    }

    public function test_index_returns_view()
    {
        $controller = new StreetController($this->streetRepoMock);

        $data = ['page' => 1];
        $request = new Request();
        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));

        $streets = factory(Street::class, 10)->create();
        $this->streetRepoMock->shouldReceive('paginateList')
            ->once()
            ->with($data['page'])
            ->andReturn($streets);

        $view = $controller->index($request);

        $this->assertEquals('streets.list', $view->getName());
        $this->assertArraySubset(['streets' => $streets], $view->getData());
    }
}
