<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class ARSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('ars', function()
        {
            return new \App\ARS;
        });
    }
}
