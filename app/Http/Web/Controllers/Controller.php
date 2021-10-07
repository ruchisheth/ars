<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use Auth;
use Session;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function __construct() {
		if(Session::has('selected_database')){
			$this->setDBConnection();
		}
	}

	public function setDBConnection($sSchemaName = ""){
		if($sSchemaName == ""){
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
