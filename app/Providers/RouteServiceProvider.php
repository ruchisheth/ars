<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Admin;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace            = 'App\Http\Controllers';
    protected $webNamespace         = 'App\Http\Web\Controllers';
    protected $adminNamespace       = 'App\Http\Admin\Controllers';
    protected $superAdminNamespace  = 'App\Http\SuperAdmin\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapWebRoutes($router);

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        
        if(isset ($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == preg_replace('#^http(s)?://#', '', env('SUPER_ADMIN_URL'))) {
            $router->group(
            [
                'namespace' => $this->superAdminNamespace, 'middleware' => 'web'
            ], function ($router) {
                require app_path('Http/SuperAdmin/routes.php');
            });
        }
        else if(isset ($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == preg_replace('#^http(s)?://#', '', env('WEB_URL'))) {
            
            $router->group(['namespace' => $this->webNamespace, 'middleware' => 'web'], function ($router) {
                require app_path('Http/Web/routes.php');
            });
        }
        else{
            $router->group(['namespace' => $this->namespace, 'middleware' => 'web',], function ($router) {
                require app_path('Http/routes.php'); 
            });
            $router->group(['namespace' => $this->adminNamespace, 'middleware' => 'web'], function($router){
                require app_path('Http/Admin/routes.php');
            });
        }
    }
}
