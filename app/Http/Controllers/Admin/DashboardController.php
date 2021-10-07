<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\AppHelper;

use App\Site,
App\Client,
App\Chain,
App\FieldRep,
App\Project,
App\Rating,
App\PrefBan,
App\Assignment,
App\Setting,
Validator,
DB,
Datatables;

class DashboardController extends Controller
{

    public function getClients(Request $request){    	
    	 //$clients = DB::table('clients')->select(['id','client_name','client_logo','status'])->get();
        ini_set('max_execution_time', 1500);
        $res = parent::isDataAvailable('client','create.client');
        
        $counts['clients']=DB::table('clients')->count();
        $counts['chains']=DB::table('chains')->count();
        $counts['rounds']=DB::table('rounds')->count();

        $chains = DB::table('chains as ch')
        ->leftJoin('clients as c', 'c.id', '=', 'ch.client_id')
        ->leftJoin('sites as s', 's.chain_id', '=', 'ch.id')
        ->leftJoin('projects as p','p.chain_id','=','ch.id')             
        ->select([
            'ch.id as chain_id groupBy ch.id',
            'ch.chain_name',
            'c.client_logo',
            'c.id as client_id',                               
            DB::raw('(select COUNT(id) as site_count from sites where chain_id = ch.id) as site_count'),
            DB::raw('(select COUNT(id) as project_count from projects where chain_id = ch.id) as project_count') ])
        ->groupBy('ch.id')
        ->get();

        $clientChains = '';
        $clients_chains = DB::table('chains as ch')
        ->leftJoin('clients as c','c.id','=','ch.client_id')
        ->leftJoin('sites as s','s.chain_id','=','ch.id')
        ->select([
            DB::raw('(select COUNT(id) from chains where status = 1) as chain_count'),
            DB::raw('(select COUNT(id) from sites where status = 1) as site_count'),
            DB::raw('(select COUNT(id) from clients where status = 1 ) as client_count')
            ])->groupBy('client_count')
        ->get();
        if($clients_chains)
        {
            foreach ($clients_chains as $client_chain) {
                $clientChains = $client_chain;
            }
        }  

        $site_geocoding = DB::table('sites')->where('lat','=',null)->orWhere('long','=',null)->count();
        $fieldrep_geocoding = DB::table('fieldreps as f')
        ->leftjoin('contacts as co', function ($join) {
            $join->on('co.reference_id', '=', 'f.id');
        })
        ->where('co.entity_type', '=', '4')
        ->where('co.contact_type', '=', 'primary')
        ->whereNull('co.lat')
        ->count();

        $data = [
            'chains' => $chains,
            'clientChains' => $clientChains,
            'counts'    => $counts,
            'site_geocoding' => $site_geocoding,
            'fieldrep_geocoding'    => $fieldrep_geocoding,
        ];

        //return view('admin.dashboard',compact('chains','clientChains','counts'));
        return view('admin.dashboard', $data);

    }

    public function getUserTimezone(Request $request){

        $timezone = $request->input('timezone');
        $request->session()->put('timezone', $timezone);
        $request->session()->put('local_tz', $timezone);
    }

}
