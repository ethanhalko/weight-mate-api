<?php


Route::group(['prefix' => 'import', 'middleware' => ['api']], function () use ($router) {
    $router->get('/', [
        'as' => 'import.index', 'uses' => 'ImportsController@index'
    ]);

    $router->post('store', [
        'as' => 'import.store', 'uses' => 'ImportsController@store'
    ]);
});

Route::group(['prefix' => 'export', 'middleware' => ['api']], function () use ($router) {
    $router->get('/', [
        'as' => 'export', 'uses' => 'ExportController@export'
    ]);
});


