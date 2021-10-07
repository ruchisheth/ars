<?php 
namespace App\Http\Middleware;

use Closure;
use Redirect;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role = null)
    {

        if (!app('Illuminate\Contracts\Auth\Guard')->guest()) {
            if ($request->user()->hasrole($role)) {
                
                //return $next($request);
                return $this->nocache( $next($request) );
            }
        }

         return $request->ajax ? response('Unauthorized.', 401) : Redirect::guest('login');
        
    }

    protected function nocache($response)
    {
        $response->headers->set('Cache-Control','nocache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        $response->headers->set('Pragma','no-cache');

        return $response;
    }


}

 