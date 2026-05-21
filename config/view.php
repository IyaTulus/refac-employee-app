<?php

return [
    'paths' => [
        resource_path('views'),

        // Frontend theme
        base_path('vendor/jeemce/laravel-theme-guest-simple/views'),

        // Backend theme
        base_path('vendor/jeemce/laravel-theme-admin-v5/views'),

        // Package core
        base_path('vendor/jeemce/laravel/views'),
    ],

    'compiled' => env('VIEW_COMPILED_PATH', realpath(storage_path('framework/views'))),
];
