<?php

/*
|--------------------------------------------------------------------------
| Streams Routes
|--------------------------------------------------------------------------
|
| This file is where you may override any of the routes that are included
| with Streams.
|
*/

Route::group(['as' => 'streams.'], function () {
    $namespacePrefix = '\\RAD\\Streams\\Http\\Controllers\\';

    Route::get('login', ['uses' => $namespacePrefix.'StreamsAuthController@login', 'as' => 'login']);
    Route::post('login', ['uses' => $namespacePrefix.'StreamsAuthController@postLogin', 'as' => 'postlogin']);

    Route::group(['middleware' => ['admin.user']], function () use ($namespacePrefix) {

        // Main Admin and Logout Route
        Route::get('/', ['uses' => $namespacePrefix.'StreamsController@index', 'as' => 'dashboard']);
        Route::get('logout', ['uses' => $namespacePrefix.'StreamsController@logout', 'as' => 'logout']);
        Route::post('upload', ['uses' => $namespacePrefix.'StreamsController@upload', 'as' => 'upload']);

        Route::get('profile', ['uses' => $namespacePrefix.'StreamsController@profile', 'as' => 'profile']);

        try {
            foreach (\RAD\Streams\Models\DataType::all() as $dataTypes) {
                Route::resource($dataTypes->slug, $namespacePrefix.'StreamsBreadController');
            }
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException("Custom routes hasn't been configured because: ".$e->getMessage(), 1);
        } catch (\Exception $e) {
            // do nothing, might just be because table not yet migrated.
        }

        // Role Routes
        Route::resource('roles', $namespacePrefix.'StreamsRoleController');

        // Menu Routes
        Route::group([
            'as'     => 'menus.',
            'prefix' => 'menus/{menu}',
        ], function () use ($namespacePrefix) {
            Route::get('builder', ['uses' => $namespacePrefix.'StreamsMenuController@builder', 'as' => 'builder']);
            Route::post('order', ['uses' => $namespacePrefix.'StreamsMenuController@order_item', 'as' => 'order']);

            Route::group([
                'as'     => 'item.',
                'prefix' => 'item',
            ], function () use ($namespacePrefix) {
                Route::delete('{id}', ['uses' => $namespacePrefix.'StreamsMenuController@delete_menu', 'as' => 'destroy']);
                Route::post('/', ['uses' => $namespacePrefix.'StreamsMenuController@add_item', 'as' => 'add']);
                Route::put('/', ['uses' => $namespacePrefix.'StreamsMenuController@update_item', 'as' => 'update']);
            });
        });

        // Settings
        Route::group([
            'as'     => 'settings.',
            'prefix' => 'settings',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'StreamsSettingsController@index', 'as' => 'index']);
            Route::post('/', ['uses' => $namespacePrefix.'StreamsSettingsController@store', 'as' => 'store']);
            Route::put('/', ['uses' => $namespacePrefix.'StreamsSettingsController@update', 'as' => 'update']);
            Route::delete('{id}', ['uses' => $namespacePrefix.'StreamsSettingsController@delete', 'as' => 'delete']);
            Route::get('{id}/move_up', ['uses' => $namespacePrefix.'StreamsSettingsController@move_up', 'as' => 'move_up']);
            Route::get('{id}/move_down', ['uses' => $namespacePrefix.'StreamsSettingsController@move_down', 'as' => 'move_down']);
            Route::get('{id}/delete_value', ['uses' => $namespacePrefix.'StreamsSettingsController@delete_value', 'as' => 'delete_value']);
        });

        // Admin Media
        Route::group([
            'as'     => 'media.',
            'prefix' => 'media',
        ], function () use ($namespacePrefix) {
            Route::get('/', ['uses' => $namespacePrefix.'StreamsMediaController@index', 'as' => 'index']);
            Route::post('files', ['uses' => $namespacePrefix.'StreamsMediaController@files', 'as' => 'files']);
            Route::post('new_folder', ['uses' => $namespacePrefix.'StreamsMediaController@new_folder', 'as' => 'new_folder']);
            Route::post('delete_file_folder', ['uses' => $namespacePrefix.'StreamsMediaController@delete_file_folder', 'as' => 'delete_file_folder']);
            Route::post('directories', ['uses' => $namespacePrefix.'StreamsMediaController@get_all_dirs', 'as' => 'get_all_dirs']);
            Route::post('move_file', ['uses' => $namespacePrefix.'StreamsMediaController@move_file', 'as' => 'move_file']);
            Route::post('rename_file', ['uses' => $namespacePrefix.'StreamsMediaController@rename_file', 'as' => 'rename_file']);
            Route::post('upload', ['uses' => $namespacePrefix.'StreamsMediaController@upload', 'as' => 'upload']);
        });

        // Database Routes
        Route::group([
            'as'     => 'database.',
            'prefix' => 'database',
        ], function () use ($namespacePrefix) {
            Route::post('bread/create', ['uses' => $namespacePrefix.'StreamsDatabaseController@addBread', 'as' => 'create_bread']);
            Route::post('bread/', ['uses' => $namespacePrefix.'StreamsDatabaseController@storeBread', 'as' => 'store_bread']);
            Route::get('bread/{id}/edit', ['uses' => $namespacePrefix.'StreamsDatabaseController@addEditBread', 'as' => 'edit_bread']);
            Route::put('bread/{id}', ['uses' => $namespacePrefix.'StreamsDatabaseController@updateBread', 'as' => 'update_bread']);
            Route::delete('bread/{id}', ['uses' => $namespacePrefix.'StreamsDatabaseController@deleteBread', 'as' => 'delete_bread']);
        });

        Route::resource('database', $namespacePrefix.'StreamsDatabaseController');
    });
});
