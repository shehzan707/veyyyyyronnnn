<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Typically, this is within the storage
    | directory. However, as indicated here, you are free to change this.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

    /*
    |--------------------------------------------------------------------------
    | Relative Path Setting
    |--------------------------------------------------------------------------
    |
    | When compilation is enabled, the compiled view path should be relative
    | to your base path for all environments. This keeps deployments simple
    | regardless of the domain or installation directory of your app.
    |
    */

    'relative_hash' => false,

    /*
    |--------------------------------------------------------------------------
    | Cache Compiled Views
    |--------------------------------------------------------------------------
    |
    | This option determines if the view files should be cached while being
    | compiled, which can improve performance. By default this is disabled.
    | You may change this to true for improved performance in production.
    |
    */

    'cache' => env('VIEW_CACHE_COMPILED', false),

    /*
    |--------------------------------------------------------------------------
    | Compiled Extension
    |--------------------------------------------------------------------------
    |
    | When the views are compiled, they will be compiled as PHP files with a
    | given extension. By default, this is "php", which is the standard PHP
    | extension. You may change this to a different value if desired.
    |
    */

    'compiled_extension' => 'php',

];
