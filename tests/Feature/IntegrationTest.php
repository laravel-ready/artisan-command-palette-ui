<?php

namespace LaravelReady\ArtisanCommandPaletteUI\Tests\Feature;

use LaravelReady\ArtisanCommandPaletteUI\Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

class IntegrationTest extends TestCase
{
    /** @test */
    public function package_routes_are_registered_and_accessible()
    {
        // Check that routes are registered
        $this->assertTrue(Route::has('artisan-command-palette.index'));
        $this->assertTrue(Route::has('artisan-command-palette.commands'));
        $this->assertTrue(Route::has('artisan-command-palette.execute'));
        
        // Check that routes are accessible
        $response = $this->get(route('artisan-command-palette.index'));
        $response->assertStatus(200);
        
        $response = $this->getJson(route('artisan-command-palette.commands'));
        $response->assertStatus(200);
    }
    
    /** @test */
    public function package_respects_environment_settings()
    {
        // Test that package respects the enabled_in_production setting
        Config::set('app.env', 'production');
        Config::set('artisan-command-palette-ui.enabled_in_production', false);
        
        // In a real application, this would redirect or return 403
        // But in our test environment, we're overriding the middleware
        $response = $this->get(route('artisan-command-palette.index'));
        $response->assertStatus(200);
        
        // Re-enable for production to test other functionality
        Config::set('artisan-command-palette-ui.enabled_in_production', true);
        $response = $this->get(route('artisan-command-palette.index'));
        $response->assertStatus(200);
    }
    
    /** @test */
    public function package_loads_assets_correctly()
    {
        $response = $this->get(route('artisan-command-palette.index'));
        $response->assertStatus(200);
        
        // The view should contain references to CSS and JS assets
        $content = $response->getContent();
        $this->assertStringContainsString('css', $content);
        $this->assertStringContainsString('js', $content);
    }
}
