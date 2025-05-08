<?php

namespace LaravelReady\ArtisanCommandPaletteUI\Tests;

use Illuminate\Support\Facades\Config;

class ConfigTest extends TestCase
{
    /** @test */
    public function config_file_has_required_structure()
    {
        $config = Config::get('artisan-command-palette-ui');

        $this->assertIsString($config['route_prefix']);
        $this->assertIsArray($config['middleware']);
        $this->assertIsArray($config['excluded_commands']);
        $this->assertIsBool($config['enabled_in_production']);
        $this->assertIsArray($config['command_groups']);
        $this->assertIsArray($config['environment_restricted_groups']);
    }

    /** @test */
    public function command_groups_have_valid_structure()
    {
        $commandGroups = Config::get('artisan-command-palette-ui.command_groups');

        // Test at least one group exists
        $this->assertNotEmpty($commandGroups);

        // Test the structure of the first group
        $firstGroup = reset($commandGroups);
        $this->assertIsArray($firstGroup);

        if (!empty($firstGroup)) {
            $firstCommand = reset($firstGroup);
            $this->assertArrayHasKey('command', $firstCommand);
            $this->assertArrayHasKey('description', $firstCommand);
        }
    }

    /** @test */
    public function environment_restrictions_have_valid_structure()
    {
        $envRestrictions = Config::get('artisan-command-palette-ui.environment_restricted_groups');

        // Check that environment restrictions are properly structured
        foreach ($envRestrictions as $group => $environments) {
            $this->assertIsString($group);
            $this->assertIsArray($environments);
            
            // Check that environments are strings
            if (!empty($environments)) {
                $this->assertIsString(reset($environments));
            }
        }
    }
}
