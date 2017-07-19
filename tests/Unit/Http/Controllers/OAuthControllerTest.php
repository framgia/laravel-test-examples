<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\OAuthController;

class OAuthControllerTest extends TestCase
{
    public function test_clients_list_view()
    {
        $controller = new OAuthController();

        $view = $controller->showClientsList();

        $this->assertEquals('oauth.clients', $view->getName());
    }

    public function test_authorized_clients_list_view()
    {
        $controller = new OAuthController();

        $view = $controller->showAuthorizedClientsList();

        $this->assertEquals('oauth.authorized-clients', $view->getName());
    }

    public function test_personal_access_tokens_list_view()
    {
        $controller = new OAuthController();

        $view = $controller->showPersonalAccessTokensList();

        $this->assertEquals('oauth.personal-access-tokens', $view->getName());
    }
}
