<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            'fieldrep.app', 'App\Http\ViewComposers\FieldrepSidebarComposer'
            );

        view()->composer(            
            'app', 'App\Http\ViewComposers\AdminSidebarComposer'
            );

        view()->composer(            
            'super_admin.app', 'App\Http\ViewComposers\SuperAdminComposer'
            );


    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
