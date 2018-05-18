<?php

namespace Thunderbite\TableHelper;

use Illuminate\Support\ServiceProvider;

class TableHelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/tablehelper.php' => config_path('tablehelper.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__.'/../config/tablehelper.php', 'tablehelper');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias('tablehelper', TableHelper::class);
        $this->app->singleton('tablehelper', function () {
            return new TableHelper;
        });
    }
}
