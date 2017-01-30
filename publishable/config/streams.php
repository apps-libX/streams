<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User config
    |--------------------------------------------------------------------------
    |
    | Here you can specify streams user configs
    |
    */

    'user' => [
        'add_default_role_on_register' => true,
        'default_role'                 => 'user',
        'admin_permission'             => 'browse_admin',
        'namespace'                    => App\User::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Controllers config
    |--------------------------------------------------------------------------
    |
    | Here you can specify streams controller settings
    |
    */

    'controllers' => [
        'namespace' => 'RAD\\Streams\\Http\\Controllers',
    ],

    /*
    |--------------------------------------------------------------------------
    | Path to the Streams Assets
    |--------------------------------------------------------------------------
    |
    | Here you can specify the location of the streams assets path
    |
    */

    'assets_path' => '/vendor/rad/streams/assets',

    /*
    |--------------------------------------------------------------------------
    | Storage Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify attributes related to your application file system
    |
    */

    'storage' => [
        'subfolder' => 'public/', // include trailing slash, like 'my_folder/'
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify streams database settings
    |
    */

    'database' => [
        'tables' => [
            'hidden' => [], // database tables that are hidden from the admin panel
        ],
    ],

];
