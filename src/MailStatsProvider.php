<?php

namespace BitsOfLove\MailStats;

use BitsOfLove\MailStats\Providers\EventProvider;
use Bogardo\Mailgun\MailgunServiceProvider;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MailStatsProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->bootConfig();
        $this->bootViews();
        $this->bootMigrations();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerProviders();
        $this->registerLogger();
        $this->registerRoutes();
    }

    /**
     * Boot the config for the package
     */
    private function bootConfig()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('mailstats.php'),
        ]);
    }

    /**
     * Boot the views for the package
     */
    private function bootViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'mail-stats');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/bitsoflove/mail-stats'),
        ]);
    }

    /**
     * Boot the migrations for the package
     */
    private function bootMigrations()
    {
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register the routes for the package
     */
    private function registerRoutes()
    {
        include __DIR__ . '/Http/routes.php';

        $this->app->make('BitsOfLove\MailStats\Http\Controllers\MailsController');
        $this->app->make('BitsOfLove\MailStats\Http\Controllers\MailStatisticsController');
        $this->app->make('BitsOfLove\MailStats\Http\Controllers\ProjectsController');
    }

    /**
     * Register a logger instance
     */
    private function registerLogger()
    {
        // bind a new logger to be used for logging mail information
        $this->app->bind('mail-stats.logger', function () {
            $view_log = new Logger('Mail statistics logger');
            $view_log->pushHandler(new StreamHandler(storage_path('logs/mail-stats.log'), Logger::INFO));
            return $view_log;
        });
    }

    /**
     * Register a view file namespace.
     *
     * @param  string  $path
     * @param  string  $namespace
     * @return void
     */
    protected function loadViewsFrom($path, $namespace)
    {
        // because we work with a sub namespace under bitsoflove namespace
        // we have to add it manually
        if (is_dir($appPath = $this->app->basePath().'/resources/views/vendor/bitsoflove/'.$namespace)) {
            $this->app['view']->addNamespace($namespace, $appPath);
        }

        $this->app['view']->addNamespace($namespace, $path);
    }

    private function registerProviders()
    {
        // load the required service provider
        $this->app->register(MailgunServiceProvider::class);
        $this->app->register(EventProvider::class);
    }
}
