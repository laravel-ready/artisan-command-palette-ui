<?php

use Illuminate\Support\Facades\Route;
use LaravelReady\ArtisanCommandPaletteUI\Http\Controllers\ArtisanCommandController;

Route::group([
    'prefix' => config('artisan-command-palette-ui.route_prefix', 'artisan-command-palette'),
    'middleware' => config('artisan-command-palette-ui.middleware', ['web', 'auth']),
], function () {
    Route::get('/', [ArtisanCommandController::class, 'index'])->name('artisan-command-palette.index');
    Route::get('/commands', [ArtisanCommandController::class, 'listCommands'])->name('artisan-command-palette.commands');
    Route::post('/execute', [ArtisanCommandController::class, 'executeCommand'])->name('artisan-command-palette.execute');
});
