<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use DB;
use Auth;
use Artisan;
use Validator;

use App\Assignment;
use App\FieldRep;
use App\Project;
use App\Round;
use App\Client;
use App\Chain;
use App\Site;
use App\FieldRep_Org;
use App\User;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    //protected 
    
    public function boot()
    {
      
      view()->composer('*', function ($view) 
      {

        if(\Session::get('selected_database') != null){

          // Artisan::call('migrate', [
          //   '--database' => \Session::get('selected_database'),
          //   '--path' => '/database/migrations/db',
          //   ]);

          
          // Artisan::call( 'db:seed', [
          //   '--class' => 'KalanTableSeeder',
          //   '--force' => true ]
          //   );
          
          //$view->with('selected_database', \Session::get('selected_database'));
          
          // $clients_settings = DB::table('settings')->where(['user_id' => Auth::id()])->select(['id',  'logo', 'theme_color'])->first();
          // $view->with('clients_settings', $clients_settings);
          
          // $count['projects']=DB::table('projects')->count();
          // $count['rounds']=DB::table('rounds')->count();
          // $count['assignments']=DB::table('assignments')->count();
          // $count['surveys']=DB::table('surveys')->whereIn('status',[2,3,4])->count();
          // $count['clients']=DB::table('clients')->count();
          // $count['chains']=DB::table('chains')->count();
          // $count['sites']=DB::table('sites')->count();
          // $count['fieldrep_orgs']=DB::table('fieldrep_orgs')->count();
          // $count['fieldreps']=DB::table('fieldreps')->count();
          // $count['surveys_templates']=DB::table('surveys_templates')->count();

          // view()->share('counter', $count);
          // view()->share('site_geocoding', $site_geocoding);
          // view()->share('fieldrep_geocoding', $fieldrep_geocoding);


        }
      });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
  }
