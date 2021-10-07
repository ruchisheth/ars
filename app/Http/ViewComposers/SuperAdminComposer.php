<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;

use Auth;

use DB;


class SuperAdminComposer
{
	public $counters = [];

	public function __construct()
    {
    	// $clients = DB::table('clients as c')
     //    ->leftJoin('clients as c', 'c.user_id', '=', 'u.id')
     //    //->where('role',2)
     //    ->count();

        $clients = DB::table('clients as c')
        ->leftJoin('users as u', 'u.id', '=', 'c.user_id')
        ->where('role',2)
        ->count();
    	$this->counters = [
            'clients'=>$clients,            
        ];
    }

    public function compose(View $view)
    {
        $view->with(['counters'=>$this->counters]);
    }
}