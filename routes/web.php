<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/api/register','UsersController@register');
$router->group(['prefix' => 'api','middleware' => 'auth'], function () use ($router) {
    $router->get('contacts/{id}',  ['uses' => 'ContactsController@get']);
    $router->post('contacts',  ['uses' => 'ContactsController@insert']);
    $router->put('contacts/{id}',  ['uses' => 'ContactsController@update']);
    $router->delete('contacts/{id}',  ['uses' => 'ContactsController@delete']);
});
