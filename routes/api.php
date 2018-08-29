<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api'], function () use ($router) {
    $router->get('authenticate', [
        'as' => 'authenticate.index', 'uses' => 'AuthController@index'
    ]);

    $router->post('authenticate', [
        'as' => 'login', 'uses' => 'AuthController@login'
    ]);

    $router->get('logout', [
        'as' => 'authenticate.logout', 'uses' => 'AuthController@logout'
    ]);

    $router->group(['prefix' => 'weight-entries'], function () use ($router) {
        $router->post('/', [
            'as' => 'weight-entry.create', 'uses' => 'WeightEntryController@create'
        ]);
        $router->get('index', [
            'as' => 'getWeightEntries', 'uses' => 'WeightEntryController@index'
        ]);
    });


    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('/', [
            'as' => 'user.index', 'uses' => 'UserController@index'
        ]);

        $router->get('/search', [
            'as' => 'user.search', 'uses' => 'UserController@search'
        ]);

        $router->get('/scan', [
            'as' => 'user.scan', 'uses' => 'UserController@scan'
        ]);
    });

    $router->group(['prefix' => 'messages'], function () use ($router) {
        $router->get('{message}', [
            'as' => 'message.get', 'uses' => 'MessagesController@show'
        ]);

        $router->post('create', [
            'as' => 'message.create', 'uses' => 'MessagesController@create'
        ]);

        $router->post('update', [
            'as' => 'message.update', 'uses' => 'MessagesController@update'
        ]);
    });


});
