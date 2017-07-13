<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('settings/oauth/clients', 'OAuthController@showClientsList')->name('oauth.clients');
Route::get('settings/oauth/authorized-clients', 'OAuthController@showAuthorizedClientsList')->name('oauth.authorized-clients');
Route::get('settings/oauth/personal-access-tokens', 'OAuthController@showPersonalAccessTokensList')->name('oauth.personal-access-tokens');

Route::resource('cities', 'CityController');
Route::resource('streets', 'StreetController');
