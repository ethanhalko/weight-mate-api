<?php


Route::group(['prefix' => 'import', 'middleware' => ['auth']], function () use ($router) {
    $router->get('/', [
        'as' => 'import.index', 'uses' => 'ImportsController@index'
    ]);

    $router->post('store', [
        'as' => 'import.store', 'uses' => 'ImportsController@store'
    ]);
});

Route::group(['prefix' => 'export', 'middleware' => ['auth']], function () use ($router) {
    $router->get('/', [
        'as' => 'export', 'uses' => 'ExportController@export'
    ]);
});

Route::group(['prefix' => 'app', 'middleware' => ['auth']], function () use ($router) {
    $router->get('download', [
        'as' => 'app.download', 'uses' => 'AppController@download'
    ]);
});

Route::group(['prefix' => 'groups', 'middleware' => ['auth']], function () use ($router) {
    $router->delete('remove', [
        'as' => 'groups.delete', 'uses' => 'GroupController@delete'
    ]);
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::post('password/set/{user}', [
   'as' => 'password.set', 'uses' => 'ChangePasswordController@set'
]);

Route::get('password', [
   'as' => 'password.index', 'uses' => 'ChangePasswordController@index'
]);