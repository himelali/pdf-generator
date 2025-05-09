<?php

namespace Himelali\PdfGenerator;

use Illuminate\Support\ServiceProvider;

class PdfServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * This method merges the package configuration with the application's configuration
     * and registers the PdfManager singleton binding into the Laravel service container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/pdf-generator.php', 'pdf-generator');
        $this->app->singleton('pdf.generator', function ($app) {
            return new PdfManager($app->config->get('pdf-generator'));
        });
    }

    /**
     * Bootstrap services.
     *
     * This method publishes the package configuration file to the application's config directory
     * allowing users to override default settings.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/pdf-generator.php' => config_path('pdf-generator.php'),
        ], 'config');
    }
}
