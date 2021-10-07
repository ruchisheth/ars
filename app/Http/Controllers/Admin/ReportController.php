<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Http\AppHelper;
use Html;
use Cookie;
use Validator;
use DB;
use Datatables;
use Exception;

use App\Site;
use App\Fieldrep;

class ReportController extends Controller
{
	public function index(Request $request){
    $geocoding = DB::table('sites')->where('lat','=',null)->orWhere('long','=',null)->count();
    return view('admin.reports.site_geolocations',compact('geocoding'));
  }

  public function fieldrepGeoLocations(Request $request){
    $geocoding = DB::table('contacts')->where('entity_type','=',4)->where('lat','=',null)->count();
    return view('admin.reports.fieldrep_geolocations',compact('geocoding'));
  }

  public function getdata(Request $request){
   $sites = DB::table('sites as s')
   ->where(function ($query)  {
      return $query->where('lat','=',null)
      ->orWhere('long','=',null);
    })
   ->select([
    's.id',
    's.site_code',
    's.street',
    's.city',
    's.state',
    's.zipcode',
    's.lat',
    's.long',
    ]);
		    //dd($sites);
		    //->groupBy('s.id');  


   $datatables = Datatables::of($sites)
   ->editColumn('site_code', function ($sites) {
    return '<a href='.url("/sites-edit/").'/'.$sites->id.'>'. $sites->site_code .'</a>';
  })
   ->editColumn('city', function ($sites) {
    return format_location($sites->city,$sites->state,$sites->zipcode);
  })
   ->editColumn('lat', function ($sites) {
     return $sites->lat;
   })
   ->editColumn('long', function ($sites) {
     return $sites->long;
   });

   $keyword = $request->get('search')['value'];

   $datatables->filterColumn('s.city', 'whereRaw', "CONCAT(s.city,',',s.state,' ',s.zipcode) like ? ", ["%$keyword%"]);

   return $datatables->make(true);
 }

 public function getfieldrepGeoLocations(Request $request){
  $fieldreps = DB::table('fieldreps as f')
  ->leftJoin('contacts as c', 'c.reference_id', '=', 'f.id') 
  ->where('entity_type',4)  
  ->where(function ($query)  {
      return $query->where('lat','=',null)
      ->orWhere('long','=',null);
    })
  ->select([
    'f.id',
    'f.fieldrep_code',
    'c.id as contact_id',
    'c.reference_id',          
    'c.address1',
    'c.address2',
    'c.city',
    'c.state',
    'c.zipcode', 
    'c.lat',
    'c.long'
    ]);

  $datatables = Datatables::of($fieldreps)
  ->editColumn('fieldrep_code', function ($fieldreps) {
    return '<a href='.url("/fieldreps-edit/").'/'.$fieldreps->id.'>'. $fieldreps->fieldrep_code .'</a>';
  })
  ->editColumn('city', function ($fieldreps) {
    return format_location($fieldreps->city,$fieldreps->state,$fieldreps->zipcode);
  })
  ->editColumn('lat', function ($fieldreps) {
    return $fieldreps->lat;		      
  })
  ->editColumn('long', function ($fieldreps) {
    return $fieldreps->long;
  });

  $keyword = $request->get('search')['value'];

  $datatables->filterColumn('c.city', 'whereRaw', "CONCAT(c.city,',',c.state,' ',c.zipcode) like ? ", ["%$keyword%"]);


  return $datatables->make(true);
}

public static function getLatlongs($address){  
  //error_reporting() 
  $latlong = [];
  $latlong['lat'] = null;
  $latlong['long'] = null;
  $address = preg_replace("/\s+/","+",$address);

  $geocode_url = 'https://maps.google.com/maps/api/geocode/json?address='.trim($address).'&key=AIzaSyDTyhUAMfYOMmbephBS2NCyzmbEzQEhVRo';
  $geocode = @file_get_contents($geocode_url); 

  $res = json_decode($geocode);

  if(count($res->results) > 0 && $res->status != 'OVER_QUERY_LIMIT'){
    $latlong['status'] = true;
    $latlong['lat'] = $res->results[0]->geometry->location->lat;
    $latlong['long'] = $res->results[0]->geometry->location->lng;
    return $latlong;
  }
  elseif ($res->status == 'ZERO_RESULTS') {
   $latlong['status'] = 'ZERO_RESULTS';        
   return $latlong;
 }   
 elseif ($res->status == 'OVER_QUERY_LIMIT') {
   $latlong['status'] = 'OVER_QUERY_LIMIT';        
      // return $latlong;
   return $latlong;
 }

 else{
  $latlong['status'] = false;       
      //return $latlong;
  return $latlong['status'];
}

}

public function refreshGeoCodes(){

  $geocodings = DB::table('sites')->where('lat', '=', null)->orWHere('long', '=', null)->select(['id','site_code','street','city','state','zipcode'])->get();
  
  $inputs = array();
  $ins_arrs = array();
  $error = array();
  foreach ($geocodings as $row =>$geocoding) {
    $street = trim($geocoding->street);
    $city = trim($geocoding->city);
    $state = trim($geocoding->state);
    $zipcode = trim($geocoding->zipcode);

    $address = $street.' '.$city.' '.$state.' '.$zipcode;
    $location= self::getLatlongs($address);


    if($location['status'] == 'OVER_QUERY_LIMIT' && $location != true){
     $inputs['lat'] = null;
     $inputs['long'] = null;
     $res['message'] = 'Sorry, You have reached daily limit of Geocoding API.';
     $res['type'] = 'error';
     return $res;
   }
   elseif($location['status'] == 'ZERO_RESULTS' && $location != true) {
     $inputs['lat'] = null;
     $inputs['long'] = null;
     $res['message'] = 'Looks like the address provided is wrong.';
           //dd($geocoding->site_code);
     $error['invalid_address'][] = $geocoding->site_code;
          // dd($geocoding->site_code);
     $res['type'] = 'error';
           //continue;
           //return $res;
   }                
   else{
     $inputs['lat'] = trim($location['lat']);
     $inputs['long'] = trim($location['long']);
   }     
   $inserted = DB::table('sites')
   ->where('id', $geocoding->id)
   ->update(['lat' => $inputs['lat'],'long'=>$inputs['long']]);
 }
      //dd($error);
 if($error){
        // foreach($error as $err=>$val){
        //     //dd($val);
        //   //['latLng'=>[$arr[0],$arr[1]],'name'=>$arr[2]]
        //  $err_msg['msg'] = ['message'=>$val];
        //    // $err_msg = "Invalid address in Site Code".$val;
        //   //return $error['invalid_address'];
        // }
        //dd($err_msg);
        //dd(json_encode($error['invalid_address']));
  return response()->json([ 'message' => $error['invalid_address'] ], 422);
}
elseif($inserted && (!$error)){
  $res['message'] = 'GeoLocations updated successfully!';
  $res['type'] = 'success';
  return $res;

}
      //dd($inputs);
}

public function refreshFieldrepGeoCodes(){

  $fieldrep_geocodes = DB::table('contacts')->where('entity_type','=',4)->where('lat','=',null)->select(['id','reference_id','entity_type','address1','address2','city','city','state','zipcode'])->get();
  $inputs = array();
  $ins_arrs = array();
  $error = array();
  foreach ($fieldrep_geocodes as $row =>$geocoding) {
    $address1 = trim($geocoding->address1);
    $address2 = trim($geocoding->address2);
    $city = trim($geocoding->city);
    $state = trim($geocoding->state);
    $zipcode = trim($geocoding->zipcode);

    $address = $address1.' '.$address2.' '.$city.' '.$state.' '.$zipcode;
            //dd($address);
    if($address != ''){
      $location= self::getLatlongs($address);              
    }
    else{
      $location['status'] = 'ZERO_RESULTS';
    }
            //dd($location);
            //dd($location);       
          //$inputs['lat'] = null;
          //$inputs['long'] = null;

    if($location['status'] == 'OVER_QUERY_LIMIT' && $location != true){
     $inputs['lat'] = null;
     $inputs['long'] = null;
     $res['message'] = 'Sorry, You have reached daily limit of Geocoding API.';
     $res['type'] = 'error';
     return $res;
   }
   elseif($location['status'] == 'ZERO_RESULTS' && $location != true) {
     $inputs['lat'] = null;
     $inputs['long'] = null;
     $res['message'] = 'Looks like the address provided is wrong.';
               //dd($geocoding->site_code);
     $error['invalid_address'][] = $geocoding->site_code;
              // dd($geocoding->site_code);
     $res['type'] = 'error';
               //continue;
               //return $res;
   }                
   else{
     $inputs['lat'] = trim($location['lat']);
     $inputs['long'] = trim($location['long']);
   }     
   $inserted = DB::table('contacts')
   ->where('id', $geocoding->id)
   ->update(['lat' => $inputs['lat'],'long'=>$inputs['long']]);
 }
          //dd($error);
 if($error){
            // foreach($error as $err=>$val){
            //     //dd($val);
            //   //['latLng'=>[$arr[0],$arr[1]],'name'=>$arr[2]]
            //  $err_msg['msg'] = ['message'=>$val];
            //    // $err_msg = "Invalid address in Site Code".$val;
            //   //return $error['invalid_address'];
            // }
            //dd($err_msg);
            //dd(json_encode($error['invalid_address']));
  return response()->json([ 'message' => $error['invalid_address'] ], 422);
}
elseif($inserted && (!$error)){
  $res['message'] = 'GeoLocations updated successfully!';
  $res['type'] = 'success';
  return $res;

}
      //dd($inputs);
}

}