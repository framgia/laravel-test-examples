<?php

namespace Tests\Unit\Http\Middleware;

use Mockery as m;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Auth\SessionGuard;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\RedirectIfAuthenticated;

class RedirectIfAuthenticatedTest extends TestCase
{
    public function test_it_redirect_if_logged_in()
    {
        $request = new Request();
        $next = function ($request) {
            return new Response('Test Response');
        };
        $guard = null;

        $sessionGuard = m::mock(SessionGuard::class);
        $sessionGuard->shouldReceive('check')
            ->once()
            ->andReturn(true);

        $authFacade = Auth::shouldReceive('guard')
            ->with($guard)
            ->once()
            ->andReturn($sessionGuard);

        $m = new RedirectIfAuthenticated();

        $response = $m->handle($request, $next, $guard);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('home'), $response->headers->get('Location'));
        $this->assertEquals(302, $response->status());
    }

    public function test_it_if_not_login()
    {
        $request = new Request();
        $next = function ($request) {
            return new Response('Test Response');
        };
        $guard = null;

        $sessionGuard = m::mock(SessionGuard::class);
        $sessionGuard->shouldReceive('check')
            ->once()
            ->andReturn(false);

        $authFacade = Auth::shouldReceive('guard')
            ->with($guard)
            ->once()
            ->andReturn($sessionGuard);

        $m = new RedirectIfAuthenticated();

        $response = $m->handle($request, $next, $guard);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Test Response', $response->getContent());
    }
}
