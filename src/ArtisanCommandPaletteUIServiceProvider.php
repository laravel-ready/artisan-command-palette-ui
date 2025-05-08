<?php

namespace LaravelReady\ArtisanCommandPaletteUI;

use Illuminate\Support\ServiceProvider;

class ArtisanCommandPaletteUIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'artisan-command-palette-ui');

        // Register routes in a way compatible with all Laravel versions
        if (version_compare($this->app->version(), '12.0.0', '>=')) {
            // Laravel 12+ route registration
            $this->app->booted(function () {
                $router = $this->app['router'];
                require __DIR__ . '/../routes/web.php';
            });
        } else {
            // Legacy route registration
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }

        // Load assets
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/artisan-command-palette-ui'),
        ], 'public');

        // Publish config
        $this->publishes([
            __DIR__ . '/../config/artisan-command-palette-ui.php' => config_path('artisan-command-palette-ui.php'),
        ], 'config');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/artisan-command-palette-ui'),
        ], 'views');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/artisan-command-palette-ui.php',
            'artisan-command-palette-ui'
        );
    }
}
