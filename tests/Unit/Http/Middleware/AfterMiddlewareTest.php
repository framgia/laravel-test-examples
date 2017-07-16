<?php

namespace Tests\Unit\Http\Middleware;

use Tests\SimpleTestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Middleware\AfterMiddleware;

class AfterMiddlewareTest extends SimpleTestCase
{
    public function test_it_appends_test_header()
    {
        $request = new Request();
        $next = function () {
            return new Response('Test Response');
        };

        $m = new AfterMiddleware();

        $response = $m->handle($request, $next);

        $this->assertNotEmpty($response->headers->get('X-Framgia-Test'));
    }
}
