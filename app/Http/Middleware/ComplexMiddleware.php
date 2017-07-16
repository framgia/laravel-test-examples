<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class ComplexMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $session = $request->getSession();

        if (!$session) {
            return new Response('Session Does Not Exist.', 500);
        }

        if ($test = $request->headers->get('X-Framgia-Test')) {
            $session->set('Test-Started', time());
        }

        $response = $next($request);

        if ($test) {
            $session->set('Test-Ended', time());
        }

        return $response;
    }
}
