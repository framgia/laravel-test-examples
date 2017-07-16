<?php

namespace Tests\Unit\Http\Middleware;

use Mockery as m;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Tests\SimpleTestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Middleware\ComplexMiddleware;

class ComplexMiddlewareTest extends SimpleTestCase
{
    public function test_it_appends_data_to_session()
    {
        $session = m::mock(SessionInterface::class);
        $request = new Request();
        $request->headers->set('X-Framgia-Test', 'true');
        $request->setSession($session);

        $started = null;
        $ended = null;
        $session->shouldReceive('set')
            ->once()
            ->with('Test-Started', m::any())
            ->andReturnUsing(function ($key, $value) use (&$started) {
                $started = $value;
            });

        $session->shouldReceive('set')
            ->once()
            ->with('Test-Ended', m::any())
            ->andReturnUsing(function ($key, $value) use (&$ended) {
                $ended = $value;
            });

        $next = function () {
            return 'foo';
        };

        $m = new ComplexMiddleware();
        $response = $m->handle($request, $next);

        $this->assertEquals('foo', $response);
        $this->assertGreaterThanOrEqual($started, $ended);
    }

    public function test_it_does_not_modify_session_without_test_header()
    {
        $session = m::mock(SessionInterface::class);
        $request = new Request();
        $request->setSession($session);

        $session->shouldNotReceive('set');

        $next = function () {
            return 'bar';
        };

        $m = new ComplexMiddleware();
        $response = $m->handle($request, $next);

        $this->assertEquals('bar', $response);
    }

    public function test_it_aborts_if_no_session()
    {
        $request = new Request();

        $next = function () {
            $this->fail('$next callback must not have been called.');
        };

        $m = new ComplexMiddleware();

        $response = $m->handle($request, $next);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('Session Does Not Exist.', $response->content());
        $this->assertEquals(500, $response->status());
    }
}
