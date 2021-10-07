<?php

namespace App\Http\SuperAdmin\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function setDBConnection($sSchemaName = NULL){
		if($sSchemaName == NULL){
			if(Session::has('selected_database')){
				$sSchemaName = Session::get('selected_database');
			}  
		}

		if($sSchemaName != ""){
			$config = app()->make('config');
			$connections = $config->get('database.connections');
			$default_connection = $connections[$config->get('database.default')];
			$new_connection = $default_connection;
			$new_connection['database'] = $sSchemaName;
			$config->set('database.connections.'.$sSchemaName, $new_connection);
			$config->set('database.default',$sSchemaName);
			Session::put('selected_database',$sSchemaName);
		} 
	}

}
