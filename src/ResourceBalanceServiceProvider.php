<?php

namespace WebFresh\ResourceBalance;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use WebFresh\ResourceBalance\Console\Commands\WfrbInstallPermissions;

class ResourceBalanceServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                WfrbInstallPermissions::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../config/wfresourcebalance.php' => config_path('wfresourcebalance.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/wfrb'),
        ], 'wfrb-views');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadJsonTranslationsFrom(__DIR__.'/../lang', 'wfrb');

        Livewire::addNamespace(
            namespace: 'wfrb',
            classNamespace: 'WebFresh\\ResourceBalance\\Livewire',
            classPath: __DIR__.'/Livewire',
            classViewPath: __DIR__.'/../resources/views/livewire'
        );

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'wfrb');
    }
}
