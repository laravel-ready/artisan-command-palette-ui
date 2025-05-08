# Artisan Command Palette UI

A beautiful UI for Laravel Artisan commands that provides a command palette interface to search and execute Artisan commands directly from your browser.

## Installation

You can install the package via composer:

```bash
composer require laravel-ready/artisan-command-palette-ui
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="LaravelReady\ArtisanCommandPaletteUI\ArtisanCommandPaletteUIServiceProvider" --tag="config"
```

This will create a `config/artisan-command-palette-ui.php` file where you can modify the package settings.

## Usage

After installation, you can access the command palette UI at `/artisan-command-palette` (or the custom route prefix you defined in the config).

The UI allows you to:

- Search for available Artisan commands
- View command descriptions and signatures
- Execute commands and see their output in real-time

## Security

By default, the command palette is protected by the `web` and `auth` middleware, meaning only authenticated users can access it.

For additional security:

- The package is disabled in production environments by default (can be enabled in config)
- You can exclude sensitive commands in the config file

## Frontend Development

If you want to modify the frontend assets, you can publish the views:

```bash
php artisan vendor:publish --provider="LaravelReady\ArtisanCommandPaletteUI\ArtisanCommandPaletteUIServiceProvider" --tag="views"
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
