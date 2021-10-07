<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use App\Http\AppHelper;
use Config;
use Session;
use App;
use Artisan;
use Auth;
use App\Round;


class Controller extends BaseController
{
  use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
  
   protected $nNotificationCount = 0, $nRoundDeadlineNotificationCount = 0;

  public function __construct() {
    
    if(Session::has('selected_database')){
      //$this->setDBConnection();
    }
    if(Auth::check()){
        
    
    if(Auth::user()->role == 3){
        $this->getNotificationCount();
    }
    }
    
    
  }
  
//   public function setDBConnection(){

//     if(Session::has('selected_database')){
//       $config = App::make('config');
//       $connections = $config->get('database.connections');
//       $defaultConnection = $connections[$config->get('database.default')];
//       $newConnection = $defaultConnection;
//       $newConnection['database'] = Session::get('selected_database');
//       App::make('config')->set('database.connections.'.Session::get('selected_database'), $newConnection);
//       Config::set('database.default',Session::get('selected_database'));
//     } 
//   }
  
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

  public function isDataAvailable($entity, $url){
    $is_data_available = false;
    switch ($entity) {
      case 'client':
      if(App\Client::count() > 0){
        $is_data_available = true;
      }
      break;
      case 'chain':
      if(App\Chain::count() > 0){
        $is_data_available = true;
      }
      break;
      case 'site':
      if(App\Site::count() > 0){
        $is_data_available = true;
      }
      break;
      case 'FieldRep Organization':
      if(App\FieldRep_Org::count() > 0){
        $is_data_available = true;
      }
      break;
      case 'FieldRep':
      if(App\FieldRep::count() > 0){
        $is_data_available = true;
      }
      break;
      case 'Survey Template':
      if(App\surveys_template::count() > 0){
        $is_data_available = true;
      }
      break;
      case 'project':
      if(App\Project::count() > 0){
        $is_data_available = true;
      }
      break;
      case 'assignment':
      if(App\Assignment::count() > 0){
        $is_data_available = true;
      }
      break;
      case 'survey':
      if(App\surveys::count() > 0){
        $is_data_available = true;
      }
      break;
      case 'round':
      if(App\Round::count() > 0){
        $is_data_available = true;
      }
      break;

      default:
        # code...
      break;
    }
    //$is_data_available = false;
    if($is_data_available == false)
    {
      $data = ['entity' => $entity,'url' => $url];
      return view('common.no_data',$data);
    }else{
      return $is_data_available;
    }
  }
  
  public function getNotificationCount(){
      $nIdUser = Auth::id();
      $this->nRoundDeadlineNotificationCount = Round::getCountOfUserRoundsEndInThreeDays($nIdUser);

      $this->nNotificationCount = $this->nRoundDeadlineNotificationCount;
      // Share this property with all the views in your application.
      view()->share([
        'nNotificationCount' => $this->nNotificationCount,
      ]);
  }
}
