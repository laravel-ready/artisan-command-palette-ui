<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Artisan Command Palette UI Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for configuring the Artisan Command Palette UI package.
    |
    */

    // Route prefix for the command palette UI
    'route_prefix' => 'artisan-command-palette',

    // Middleware for the command palette routes
    'middleware' => ['web', 'auth'],

    // Commands to exclude from the palette
    'excluded_commands' => [
        // Add command names to exclude them from the palette
    ],

    // Enable/disable command palette in production
    'enabled_in_production' => false,

    // Predefined command groups
    'command_groups' => [
        'Optimize' => [
            ['command' => 'optimize', 'description' => 'Cache the framework bootstrap files'],
            ['command' => 'optimize:clear', 'description' => 'Remove the cached bootstrap files'],
            ['command' => 'auth:clear-resets', 'description' => 'Flush expired password reset tokens'],
            ['command' => 'event:clear', 'description' => 'Clear all cached events and listeners'],
            ['command' => 'event:cache', 'description' => 'Discover and cache the application\'s events and listeners'],
            ['command' => 'package:discover', 'description' => 'Rebuild the cached package manifest'],
            ['command' => 'queue:clear', 'description' => 'Delete all of the jobs from the specified queue'],
            ['command' => 'schedule:clear-cache', 'description' => 'Delete the cached mutex files created by scheduler'],
        ],
        'Cache' => [
            ['command' => 'cache:clear', 'description' => 'Flush the application cache'],
            ['command' => 'cache:forget', 'description' => 'Remove an item from the cache'],
            ['command' => 'cache:table', 'description' => 'Create a migration for the cache database table'],
        ],
        'Config' => [
            ['command' => 'config:cache', 'description' => 'Create a cache file for faster configuration loading'],
            ['command' => 'config:clear', 'description' => 'Remove the configuration cache file'],
        ],
        'Database' => [
            ['command' => 'db:seed', 'description' => 'Seed the database with records'],
            ['command' => 'db:wipe', 'description' => 'Drop all tables, views, and types'],
            ['command' => 'db:monitor', 'description' => 'Monitor database connections'],
        ],
        'Migration' => [
            ['command' => 'migrate', 'description' => 'Run database migrations'],
            ['command' => 'migrate:fresh', 'description' => 'Drop all tables and re-run all migrations'],
            ['command' => 'migrate:refresh', 'description' => 'Reset and re-run all migrations'],
            ['command' => 'migrate:rollback', 'description' => 'Rollback the last database migration'],
            ['command' => 'migrate:status', 'description' => 'Show the status of each migration'],
        ],
        'Route' => [
            ['command' => 'route:cache', 'description' => 'Create a route cache file'],
            ['command' => 'route:clear', 'description' => 'Remove the route cache file'],
            ['command' => 'route:list', 'description' => 'List all registered routes'],
        ],
        'View' => [
            ['command' => 'view:cache', 'description' => 'Compile all of the application\'s Blade templates'],
            ['command' => 'view:clear', 'description' => 'Clear all compiled view files'],
        ],
        'Storage' => [
            ['command' => 'storage:link', 'description' => 'Create the symbolic links configured for the application'],
            ['command' => 'storage:unlink', 'description' => 'Remove the symbolic links configured for the application'],
        ],
    ],

    // Environment restrictions for command groups
    // If a command group is in this list, it will only be shown in the specified environments
    'environment_restricted_groups' => [
        'Database' => ['local', 'staging'],
        'Migration' => ['local', 'staging'],
        'Storage' => ['local', 'staging'],
    ],
];
