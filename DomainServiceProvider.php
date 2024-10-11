<?php

namespace CarSubscription;

use Dotenv\Dotenv;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/');
        $dotenv->load();

        $this->mergeConfigFrom(__DIR__.'/Config/config.php', 'cars');
        Config::set('database.connections.cars', config('cars.connections.cars'));

        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        $this->loadViewsFrom(__DIR__.'/Resources/views', 'cars');
        Blade::componentNamespace('CarSubscription\\View\\Components', 'cars');

        $loader = AliasLoader::getInstance();
        $loader->alias('DomainService', \CarSubscription\Facades\DomainService::class);
    }

    public function register(): void
    {
        $this->app->bind(DomainService::class, function () {
            return new DomainService();
        });
    }
}
