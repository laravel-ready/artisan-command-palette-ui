<?php

namespace LaravelReady\ArtisanCommandPaletteUI\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;

class ArtisanCommandController extends Controller
{
    /**
     * Display the command palette UI.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $commands = $this->getCommandGroups();
        return View::make('artisan-command-palette-ui::index', compact('commands'));
    }

    /**
     * Get command groups from config with environment restrictions applied.
     *
     * @return array
     */
    protected function getCommandGroups()
    {
        $commandGroups = Config::get('artisan-command-palette-ui.command_groups', []);
        $envRestrictions = Config::get('artisan-command-palette-ui.environment_restricted_groups', []);
        $currentEnv = App::environment();

        // Apply environment restrictions
        foreach ($envRestrictions as $group => $allowedEnvs) {
            if (!in_array($currentEnv, $allowedEnvs) && isset($commandGroups[$group])) {
                $commandGroups[$group] = [];
            }
        }

        return $commandGroups;
    }

    /**
     * List all available Artisan commands.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * List all available Artisan commands.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listCommands()
    {
        // Return both predefined command groups and all available commands
        $commandGroups = $this->getCommandGroups();
        $allCommands = $this->getAllCommands();

        return Response::json([
            'groups' => $commandGroups,
            'all' => $allCommands,
        ]);
    }

    /**
     * Get all available commands excluding the ones in the excluded list.
     *
     * @return array
     */
    protected function getAllCommands()
    {
        $commands = [];
        $excludedCommands = Config::get('artisan-command-palette-ui.excluded_commands', []);

        foreach (Artisan::all() as $name => $command) {
            if (in_array($name, $excludedCommands)) {
                continue;
            }

            $commands[] = [
                'command' => $name,
                'description' => $command->getDescription(),
                'signature' => $command->getSynopsis(),
            ];
        }

        return $commands;
    }

    /**
     * Execute an Artisan command.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Execute an Artisan command.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function executeCommand(Request $request)
    {
        $command = $request->input('command');

        if (empty($command)) {
            return Response::json([
                'success' => false,
                'message' => 'Error executing command',
                'error' => 'No command specified'
            ], 400);
        }

        // Parse the command string into command name and arguments
        $parts = explode(' ', $command);
        $commandName = array_shift($parts);
        $arguments = $parts;

        // Check if command exists - compatible with Laravel 8-12
        $commands = Artisan::all();
        if (!array_key_exists($commandName, $commands)) {
            return Response::json([
                'success' => false,
                'message' => 'Error executing command',
                'error' => "Command '{$commandName}' not found"
            ], 404);
        }

        // Check if command is excluded
        $excludedCommands = Config::get('artisan-command-palette-ui.excluded_commands', []);
        if (in_array($commandName, $excludedCommands)) {
            return Response::json([
                'success' => false,
                'message' => 'Error executing command',
                'error' => "Command '{$commandName}' is not allowed"
            ], 403);
        }

        // Execute the command
        try {
            Artisan::call($command);
            $output = Artisan::output();

            return Response::json([
                'success' => true,
                'message' => 'Command executed successfully',
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Error executing command',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Parse command arguments into key-value pairs.
     *
     * @param  array  $arguments
     * @return array
     */
    protected function parseArguments(array $arguments)
    {
        $result = [];

        foreach ($arguments as $argument) {
            if (strpos($argument, '=') !== false) {
                list($key, $value) = explode('=', $argument, 2);
                $result[ltrim($key, '-')] = $value;
            } elseif (strpos($argument, '--') === 0) {
                $result[ltrim($argument, '-')] = true;
            } else {
                $result[] = $argument;
            }
        }

        return $result;
    }
}
