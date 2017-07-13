<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OAuthController extends Controller
{
    /**
     * OAuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function showClientsList()
    {
        return view('oauth.clients');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function showAuthorizedClientsList()
    {
        return view('oauth.authorized-clients');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function showPersonalAccessTokensList()
    {
        return view('oauth.personal-access-tokens');
    }
}
