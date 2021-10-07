<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\AppHelper;
use Validator;
use App\Site;
use App\Assignment;
use App\PrefBan;
use App\Client;
use App\AppData;
use App\Chain;
use App\FieldRep;
use DB;
use Datatables;
use Html;
use Geocoder\Geocoder;
use Exception;

class SiteController extends Controller
{

    public function index(Request $request){ 
                // $geo = new Geocoder();

        // $geocode = $geo->geocode('10 rue Gambetta, Paris, France');

       // try {
       //      $geo = new Geocoder();
       //  $geocode = $geo->geocode('10 rue Gambetta, Paris, France');
       //      // ...
       //  } catch (\Exception $e) {
       //      // Here we will get "The FreeGeoIpProvider does not support Street addresses." ;)

       //  }


        $res = parent::isDataAvailable('site','site.create');
        if($res === true){
            $chain_list = ['' => 'Select Chain'] + Chain::lists('chain_name','id')->all();
            $states = ['' => 'Select State'] + DB::table('_list')->where('list_name','=','state')->orderBy('item_name')->lists('item_name','item_name');       

            $city =  Site::distinct()->orderBy('city')->get(['city'])->toArray();
            $city =  array_map('current', $city);
            $city=array_combine($city,$city);
            $city =array_merge(['' => 'Select City'],$city);

            $data = [
            'chain_list' => $chain_list,
            'states'    => $states,
            'city' => $city,
            'chain_filter' => $request->query('chain_id'),
            ];

            return view('admin.sites.sites',$data);
        }
        return $res;        
    }

    public function create(Request $request,$chain_id = '')
    {
        $chains = ['' => 'Select Chain'] + DB::table('chains')->where('status','=','1')->lists('chain_name','id');            
        //$fieldreps = ['' => 'Select FieldRep'] + FieldRep::select(DB::raw("concat(first_name,' ',last_name) as full_name, id"))->orderBy('last_name', 'asc')->lists('full_name', 'id')->all();
        $fieldreps = ['' => 'Select FieldRep'] + FieldRep::select(DB::raw("concat(fieldrep_code,'-',first_name,' ',last_name) as full_name, id"))->where('initial_status', '=', true)->orderBy(DB::raw('lpad(trim(fieldrep_code), 10, 0)'), 'asc')->lists('full_name', 'id')->all();
        return view('admin.sites.create_site',compact('chains','chain_id','fieldreps'));
    }


    public function store(Request $request){
        $this->validate($request, [
            "site_name" =>  "required",
            'site_code' =>  'unique_with:sites,chain_id=>'.$request->chain_id.',id,'.$request->input('id'),
            "chain_id"  =>  "required",
            "street"    =>  "required",
            "city"      =>  "required",
            "state"     =>  "required", 
            "zipcode"   =>  "required|numeric",            
            ],[
            "chain_id.required" => "Select Chain for the Site",
            "site_code.unique_with" => "The Site Code has already been taken.",
            ]);
        
        $inputs = $request->all();
        $inputs['street'] = $request->input('street');
        $inputs['city'] = $request->input('city');
        $inputs['state'] = $request->input('state');
        $inputs['zipcode']=$request->input('zipcode');
        $address = $inputs['street'].' '.$inputs['city'].' '.$inputs['state'].' '.$inputs['zipcode'];
        $location=AppHelper::getLatlong($address);
        $inputs['lat']  = $location['lat']; 
        $inputs['long'] = $location['long'];        
        if($request->get('site_code') == ''){
            $site_codes = Site::where('chain_id',$request->chain_id)->select(DB::raw('max(cast(site_code as signed)) as site_code'))->first();
            $site_code = $site_codes->site_code + 1;
        }else{
            $site_code = $request->site_code;
        }
        $inputs['site_code'] = $site_code;

        if($request->input('id')==''){
            // Add New Site
            Site::create($inputs);
            $url = $request->input('url');
            return redirect($url)->with('success','Site added successfully');
        }
        else{
            // Update Site
            $sites = Site::where(['id'=>$request->input('id')])->first();
            $sites->update($inputs);
            $url = $request->input('url');
            return redirect($url)->with('success','Site saved successfully');
        }
    } 

    public function edit(Request $request,$id){
        $site = Site::findorFail($id);
        $site->updated = date_formats(AppHelper::getLocalTimeZone($site->updated_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT); 
        $site->created = date_formats(AppHelper::getLocalTimeZone($site->created_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT);           
        $chains = ['' => 'Select Chain'] + DB::table('chains')->where('status','=','1')->lists('chain_name','id');
        $fieldreps = ['' => 'Select FieldRep'] + FieldRep::select(DB::raw("concat(fieldrep_code,'-',first_name,' ',last_name) as full_name, id"))->where('initial_status', '=', true)->orderBy(DB::raw('lpad(trim(fieldrep_code), 10, 0)'), 'asc')->lists('full_name', 'id')->all();

        $app = new AppData;
        $entity_type = $app->entity_types['site'];
        $contact_types = ['' => 'Select Contact'] + $app->contact_types['site'];
        $states = ['' => 'Select State'] + DB::table('_list')->where('list_name','=','state')->lists('item_name','item_name','list_order');

        $data = [
        'site'          =>  $site,
        'entity_type'   =>  $entity_type,
        'contact_types' =>  $contact_types,
        'chains'        =>  $chains,
        'fieldreps'     =>  $fieldreps,
        'states'        =>  $states,
        ];

        return view('admin.sites.create_site', $data);      
        //return view('admin.sites.create_site',compact('site','entity_type','contact_types','chains','fieldreps'));
    }

    public function getdata(Request $request){
        $sites = DB::table('sites as s')           
        ->leftJoin('chains as ch', 's.chain_id', '=', 'ch.id')
        ->leftJoin('clients as c','c.id','=','ch.client_id')
        ->leftJoin('fieldreps as f','f.id','=','s.fieldrep_id')             
        ->select([                
            'ch.chain_name',
            'c.client_logo',
            's.id',
            's.chain_id',
            's.fieldrep_id',
            'f.first_name',
            'f.last_name',
            DB::raw('CONCAT(f.first_name," ",f.last_name) as full_name'), 
            's.site_name',
            's.site_code',
            's.city',
            's.state',
            's.zipcode',
            's.status']);

        if($request->order){
            if($request->columns[$request->order[0]['column']]['name'] == 's.site_code'){
                $sites->orderBy(DB::raw('lpad(trim(site_code), 10, 0)'), $request->order[0]['dir']);
            }
            if($request->columns[$request->order[0]['column']]['name'] == 'full_name'){
                $sites->orderBy('full_name', $request->order[0]['dir']);
            }
        }

        $datatables = Datatables::of($sites)
        ->addColumn('action', function ($sites) {
            return '<button class="btn btn-box-tool" type="submit" name="remove_site" data-id="'.$sites->id.'" value="delete" ><span class="fa fa-trash"></span></button>';
        })
        ->editColumn('client_logo', function ($sites) {
            $logo = AppHelper::getClientLogoImage($sites->client_logo);
            return $logo;
        })
        ->editColumn('site_code', function ($sites) {
            return '<a href='.url("/sites-edit/").'/'.$sites->id.'>'. $sites->site_code .'</a>';
        })
        ->editColumn('chain_name', function ($chains){
            return '<a href='.url("/chains-edit/").'/'.$chains->chain_id.'>'. $chains->chain_name.'</a>';   
        })
        ->editColumn('city', function ($sites) {
            return format_location($sites->city,$sites->state,$sites->zipcode);
        })
        ->editColumn('status', function ($sites) {
            if($sites->status == 1){
                return '<span class="label label-success">Open</span>';
            }else{
                return '<span class="label label-danger">Closed</span>';
            }
        });

        if ($id = $request->get('chain_id')) {
            $datatables->where('s.chain_id', '=', "$id"); // additional users.name search
        }
        
        if ($request->get('city')) {
            $city = $request->get('city');
            $datatables->where('s.city', 'like', "$city%"); // additional users.name search
        }

        if ($request->get('state')) {
            $state = $request->get('state');
            $datatables->where('s.state', 'like', "$state%"); // additional users.name search
        }

        if ($request->get('status') != ''  ) {
            $status = $request->get('status');
            $datatables->where('s.status', 'like', "$status%"); 
        }

        $keyword = $request->get('search')['value'];
        if (preg_match("/^".$keyword."/i", 'Open', $match)){                
            $datatables->filterColumn('s.status', 'where', '=', 1);
        }

        if (preg_match("/^".$keyword."/i", 'Closed', $match)){
            $datatables->filterColumn('s.status', 'where', '=', 0);
        }

        $datatables->filterColumn('city', 'whereRaw', "CONCAT(s.city,',',s.state,' ',s.zipcode) like ? ", ["%$keyword%"]);

        $datatables->filterColumn('full_name', 'whereRaw', "CONCAT(f.first_name,' ',f.last_name) like ? ", ["%$keyword%"]);
        return $datatables->make(true);
    }


    function deleteSite(Request $request){
        try
        {
            $site = Site::find($request->input('id'));
            $site->delete();

            Contact::where(['entity_type' => 3, 'reference_id' => $request->input('id')])->delete();
            
            return response()->json(array(
             "status" => "success",
             "message"=>"Site removed successfully",
             ));   
        }
        catch(Exception $e){
            if($e instanceof \PDOException )
            {
                $error_code = $e->getCode();
                if($error_code == 23000){
                    $message = 'Site can not be deleted, it has ';
                    $entity = [];
                    if(Assignment::where(['site_id' => $request->input('id')])->count() > 0){
                        $entity[] = 'Assignments';
                    }
                    if(PrefBan::where(['site_id' => $request->input('id')])->count() > 0){
                        $entity[] = 'FieldRep Preference';
                    }
                    $message .= implode(', ', $entity);
                    return response()->json([ 'message' => $message ], 422);
                }
            }
        }
    }
}
