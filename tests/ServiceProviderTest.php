<?php

namespace LaravelReady\ArtisanCommandPaletteUI\Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class ServiceProviderTest extends TestCase
{
    /** @test */
    public function it_registers_the_service_provider()
    {
        $this->assertTrue($this->app->providerIsLoaded(
            \LaravelReady\ArtisanCommandPaletteUI\ArtisanCommandPaletteUIServiceProvider::class
        ));
    }

    /** @test */
    public function it_merges_config_correctly()
    {
        $this->assertNotNull(Config::get('artisan-command-palette-ui'));
        $this->assertIsArray(Config::get('artisan-command-palette-ui.command_groups'));
    }

    /** @test */
    public function it_registers_routes_correctly()
    {
        $prefix = config('artisan-command-palette-ui.route_prefix', 'artisan-command-palette');
        
        $this->assertTrue(Route::has('artisan-command-palette.index'));
        $this->assertTrue(Route::has('artisan-command-palette.commands'));
        $this->assertTrue(Route::has('artisan-command-palette.execute'));
    }

    /** @test */
    public function it_loads_views_correctly()
    {
        $this->assertDirectoryExists(__DIR__ . '/../resources/views');
        $this->assertFileExists(__DIR__ . '/../resources/views/index.blade.php');
    }
}
