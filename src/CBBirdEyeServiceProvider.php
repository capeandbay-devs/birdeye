<?php

namespace CapeAndBay\BirdEye;

use Illuminate\Support\ServiceProvider;

class CBBirdEyeServiceProvider extends ServiceProvider
{
    const VERSION = '0.1.0';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/birdeye.php' => config_path('birdeye.php'),
        ], 'config');

        // Publishing the Migrations files.
        if (! class_exists('CreateBirdEyeBusinessesTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../migrations/create_birdeye_businesses_table.php.stub' => database_path("/migrations/{$timestamp}_create_birdeye_businesses_table.php"),
            ], 'migrations');
        }

        // Registering package commands.
        $this->commands([
            \CapeAndBay\BirdEye\Console\Commands\ImportChildBusinessesCommand::class
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/birdeye.php', 'birdeye');

        // Register the service the package provides.
        $this->app->singleton('birdeye', function ($app) {
            return new BirdEye();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['birdeye'];
    }
}
