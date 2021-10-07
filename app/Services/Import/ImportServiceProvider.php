<?php 
namespace App\Services\Import;

use Illuminate\Support\ServiceProvider;

class ImportServiceProvider extends ServiceProvider {


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // We don't have to register anything here so we keep this empty!
       //new CustomImport;
        // $this->app['excel'] = $this->app->share(function($app)
        // {
             return new CustomImport;
        // });
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
            
    }

}