<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;

use DB;
use Auth;
use Artisan;

use App\Assignment;
use App\FieldRep;
use App\Project;
use App\Round;
use App\Client;
use App\Chain;
use App\Site;
use App\FieldRep_Org;


class AdminSidebarComposer
{
	public $counter = [];

	public function __construct()
    {
    	  $projects=DB::table('projects')->count();
          $rounds=DB::table('rounds')->count();
          $assignments=DB::table('assignments')->count();
          $surveys=DB::table('surveys')->whereIn('status',[2,3,4])->count();
          $clients=DB::table('clients')->count();
          $chains=DB::table('chains')->count();
          $sites=DB::table('sites')->count();
          $fieldrep_orgs=DB::table('fieldrep_orgs')->count();
          $fieldreps=DB::table('fieldreps')->count();
          $surveys_templates=DB::table('surveys_templates')->count();
    	$this->counter = [
            'projects'=>$projects,
            'rounds'=>$rounds,
            'assignments'=>$assignments,
            'surveys'=>$surveys,
            'clients'=>$clients,
            'chains'=>$chains,
            'sites'=>$sites,
            'fieldrep_orgs'=>$fieldrep_orgs,
            'fieldreps'=>$fieldreps,
            'surveys_templates'=>$surveys_templates,

        ];
    }

    public function compose(View $view)
    {
        $view->with(['counter'=>$this->counter]);
    }

  
}