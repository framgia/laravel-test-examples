<?php

namespace Tests;

use InvalidArgumentException;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function assertAuthenticationRequired($uri, $method = 'get', $redirect = '/login')
    {
        $method = strtolower($method);
        if (!in_array($method, ['get', 'post', 'put', 'update', 'delete'])) {
            throw new InvalidArgumentException('Invalid method: '.$method);
        }

        // Html check
        $response = $this->$method($uri);
        $response->assertStatus(302);
        $response->assertRedirect($redirect);

        // Json check
        $method .= 'Json';
        $response = $this->$method($uri);
        $response->assertStatus(401);
    }
}
