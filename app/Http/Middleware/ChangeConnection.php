<?php

namespace App\Http\Middleware;

use Closure;

use Session;

use Auth;

use AppHelper;

class ChangeConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Session::has('selected_database')){
            if(Auth::check()){
                $user = Auth::user();
                if($user->db_version != '' && $user->db_version != NULL){
                    $schemaName = config('constants.DB_PREFIX').$user->db_version;
                    Session::put('selected_database',$schemaName);
                }
            }
        }
        if(Session::has('selected_database')){
            $config = app()->make('config');
            $connections = $config->get('database.connections');
            $default_connection = $connections[$config->get('database.default')];
            $new_connection = $default_connection;
            $new_connection['database'] = Session::get('selected_database');
            $config->set('database.connections.'.Session::get('selected_database'), $new_connection);
            $config->set('database.default',Session::get('selected_database'));
        } 
        return $next($request);
    }
}
