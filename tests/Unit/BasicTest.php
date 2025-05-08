<?php

namespace LaravelReady\ArtisanCommandPaletteUI\Tests\Unit;

use LaravelReady\ArtisanCommandPaletteUI\Tests\TestCase;
use Illuminate\Support\Facades\Config;

class BasicTest extends TestCase
{
    /** @test */
    public function config_file_is_loaded()
    {
        $this->assertNotNull(Config::get('artisan-command-palette-ui'));
    }

    /** @test */
    public function route_prefix_is_configurable()
    {
        $defaultPrefix = Config::get('artisan-command-palette-ui.route_prefix');
        $this->assertEquals('artisan-command-palette', $defaultPrefix);
        
        // Test changing the prefix
        Config::set('artisan-command-palette-ui.route_prefix', 'custom-prefix');
        $this->assertEquals('custom-prefix', Config::get('artisan-command-palette-ui.route_prefix'));
    }

    /** @test */
    public function production_mode_is_configurable()
    {
        $defaultValue = Config::get('artisan-command-palette-ui.enabled_in_production');
        $this->assertIsBool($defaultValue);
        
        // Test changing the value
        Config::set('artisan-command-palette-ui.enabled_in_production', !$defaultValue);
        $this->assertEquals(!$defaultValue, Config::get('artisan-command-palette-ui.enabled_in_production'));
    }
}
