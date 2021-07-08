<?php

namespace App\Providers;

use App\Helpers\RolesHelper;
use App\Websockets\IWebsocketManager;
use App\Websockets\PusherManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        IWebsocketManager::class => PusherManager::class
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(RolesHelper::class);
    }
}
