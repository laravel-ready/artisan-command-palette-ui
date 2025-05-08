<?php

namespace LaravelReady\ArtisanCommandPaletteUI\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use LaravelReady\ArtisanCommandPaletteUI\ArtisanCommandPaletteUIServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ArtisanCommandPaletteUIServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Set test environment
        $app['config']->set('app.env', 'testing');

        // Configure artisan-command-palette-ui
        $app['config']->set('artisan-command-palette-ui.middleware', []);
        $app['config']->set('artisan-command-palette-ui.enabled_in_production', true);
    }
}
