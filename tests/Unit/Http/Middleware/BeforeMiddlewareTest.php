<?php

namespace Tests\Unit\Http\Middleware;

use Tests\SimpleTestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Middleware\BeforeMiddleware;

class BeforeMiddlewareTest extends SimpleTestCase
{
    public function test_it_removes_test_header()
    {
        $request = new Request();
        $request->headers->set('X-Framgia-Test', 'foo');

        $next = function ($request) {
            $this->assertNull($request->headers->get('X-Framgia-Test'));
            return 'bar';
        };

        $m = new BeforeMiddleware();

        $response = $m->handle($request, $next);
        $this->assertEquals('bar', $response);
    }

    public function test_it_aborts_on_wrong_header()
    {
        $request = new Request();
        $request->headers->set('X-Framgia-Test', 'abort');

        $next = function () {
            $this->fail('$next callback must not have been called.');
        };

        $m = new BeforeMiddleware();

        /** @var Response $response */
        $response = $m->handle($request, $next);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('Request Aborted', $response->content());
        $this->assertEquals(400, $response->status());
    }
}
