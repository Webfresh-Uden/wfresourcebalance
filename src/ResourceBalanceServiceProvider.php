<?php

namespace WebFresh\ResourceBalance;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use WebFresh\UserManager\Console\Commands\WfumInstallCommand;

class ResourceBalanceServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
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

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'wfrb');

        Livewire::addNamespace(
            namespace: 'wfrb',
            classNamespace: 'WebFresh\\ResourceBalance\\Livewire',
            classPath: __DIR__.'/Livewire',
            classViewPath: __DIR__.'/../resources/views/livewire',
        );

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'wfrb');
    }
}
