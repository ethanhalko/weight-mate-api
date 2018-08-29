<?php


Route::group(['prefix' => 'imports', 'middleware' => ['api']], function () use ($router) {
    $router->get('/', [
        'as' => 'imports.index', 'uses' => 'ImportsController@index'
    ]);

    $router->post('store', [
        'as' => 'imports.store', 'uses' => 'ImportsController@store'
    ]);
});

Route::group(['prefix' => 'export', 'middleware' => ['api']], function () use ($router) {
    $router->get('/', [
        'as' => 'export', 'uses' => 'ExportController@export'
    ]);
});


