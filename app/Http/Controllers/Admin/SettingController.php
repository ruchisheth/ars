<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\AppHelper;
use Illuminate\Support\Collection;
use Validator;
use File;
use Auth;
use App\Site;
use App\Client;
use App\AppData;
use App\Chain;
use App\Setting;
use App\_List;
use DB;
use Datatables;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Exception;
use DateTimeZone;
use App\Profile;
use App\SiteSetting;

class SettingController extends Controller
{
  public function index(Request $request){          


    //$setting = Setting::find($request->input('id'));
    $setting = Setting::where('user_id', Auth::id())->first();
    if($setting != null){

      $setting->updated = date_formats(AppHelper::getLocalTimeZone($setting->updated_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT); 
      $setting->created = date_formats(AppHelper::getLocalTimeZone($setting->created_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT);

      if($setting->logo != '' || $setting->logo != null){
        $setting->logo = AppHelper::USER_IMAGE.$setting->logo; 
      }else{
        $setting->logo = null;
      }
    }
    
    $aAdminSettings = SiteSetting::all()->pluck('setting_value', 'setting_key')->toArray();
    // date_default_timezone_set('Pacific/Kiritimati');

    // $timezonelist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    // $zones_array = array();
    // $timestamp = time();
    // foreach(timezone_identifiers_list() as $key => $zone) {
    //   date_default_timezone_set($zone);
    
    //   $zones_array[$key] = '(UTC/GMT ' . date('P', $timestamp).') '.$zone;
    // }
    

    // $zones_array = array_values(array_sort($zones_array, function ($value) {
    //   return $value;
    // }));     
    
    $timezone = AppHelper::getTimeZone();
    

    $aViewData = [ 
    'setting'       =>  $setting,
    'timezone'      =>  $timezone,
    'aAdminSettings'    =>  $aAdminSettings,
    ];

    //return view('admin.settings.create_setting',compact('setting'));
    return view('common.settings.settings', $aViewData);
  }

  public function store(Request $request)
  {

    $this->validate($request, 
      [
      "list_name" =>'required',

      ],[
      'list_name.required' => 'The List Name field is required',

      ]);

    $setting = new _List($request->except(['_token']));
    
    

    $setting->save();
    return response()->json(array(
      "status" => "success",
      "message"=>"List Added Successfully",
      ));
  }

  public function getlistdata(){

    $lists = DB::table('_list')->distinct()->select(['list_name','created_at'])->groupBy('list_name');

    return Datatables::of($lists)
    ->orderBy('created_at','desc')
    // ->addColumn('action', function ($lists) {
    //   return '<button class="btn btn-box-tool" type="submit" name="remove_list" data-id="'.$lists->list_name.'" value="delete" ><span class="fa fa-trash"></span></button>';
    // })

    ->editColumn('list_name', function ($lists) {
                //return $lists->list_name;
                //return '<a href='.url("/lists-item-edit/").'/'.$lists->list_name.'>'. $lists->list_name .'</a>';
      $html  = '';
      $html .= '<a href="javascript:void(0)" onclick="SetListItem(this,event)" data-id='.$lists->list_name.'>'. ucwords(str_replace('_', ' ', @$lists->list_name)).'</a>';

      $html .= '<br>';

      return $html;
    })


    ->removeColumn('status')
    ->removeColumn('id')
    ->removeColumn('item_name')
    ->removeColumn('list_order')
    ->removeColumn('created_at')
    ->make();
  }

  function deleteList(Request $request){
    $list = DB::table('_list')->where('list_name', '=', $request->input('list_name'))->delete();
    return response()->json(array(
     "status" => "success",
     "message"=>"Data Removed",
     ));   
  }

  public function listitem(Request $request,$list_type){
    //$list_types = DB::table('_list')->where(['list_name' => $list_type])->orderBy('list_order')->get(['id','item_name','list_order']) ;
    $list_types = DB::table('_list')->where(['list_name' => $list_type])->where('item_name' ,'!=', '')->orderBy('list_order')->get(['id','item_name','list_order','is_default']) ;        
    $list_types = collect($list_types);

    $list_names = DB::table('_list')->distinct()->select(['list_name'])->where('list_name','=',$list_type)->first();

    $data = [
    'list_types'=>$list_types,
    'lists'=>$list_names,

    ];
    $HTML = view('admin.settings.list_item_modal',$data)->render();
    return response()->json(array(
     "status" => "success",
     "ListItemModalHtml"=>$HTML,
     ));      
  }

  public function list_item_store(Request $request){

      $this->validate($request, 
        [
        "item_name" =>'required',            
        ],[
        'item_name.required' => 'The Item Name field is required',           
        ]);
    if($request->input('id') == '' || $request->input('id') == '0'){

      $setting = $request->except('_token');
      $create = _List::create($setting);
      $data = ['data'=>$create];
      $HTML = view('common.list_items_li',$data)->render();
      return response()->json(array(
        "status" => "success",
        "ListItemHtml"=>$HTML,
        "message"=>"List Item Added Successfully",
        ));
    }
    else{
     $setting = _List::where(['id'=>$request->input('id')])->first(); 

     $item_name = $request->input('item_name');

     $setting->update($request->except(['_token']));
     $data = ['data'=>$setting];
     $HTML = view('common.list_items_li',$data)->render();

     return response()->json(array(
      "status" => "success",
      "data"=>$item_name,
      "message"=>"List Item Updated Successfully",
      ));
   }  /* else */

 } 

 public function edit($listitem_id){

  $inputs = _List::find($listitem_id);
  $filteredArr = [
  'id'=>["type"=>"hidden",'value'=>$inputs->id],
  'item_name'=>["type"=>"text",'value'=>$inputs->item_name],

  ];
  return response()->json(array(
   "status" => "success",
   "inputs"=>$filteredArr,
   ));
}

function deleteListItem(Request $request){
  try{

    $listitem = _List::find($request->input('id'));

    if($listitem->is_default){
      throw new \PDOException("You can not delete Default Items");
    }
  }catch(Exception $e){
    if($e instanceof \PDOException )
    {
      $error_code = $e->getCode();

      $message = $e->getMessage();
      return response()->json([ 'message' => $message ], 422);
    }
  }
  $listitem->delete();
  return response()->json(array(
   "status" => "success",
   "message"=>"List Item Removed",
   ));   
}

public function ListItemOrder(Request $request){
  $i=1;
  foreach($request->input('list_order') as $item){
    $order = _List::where(['id'=>$item])->first();
    $order->update(['list_order'=>$i]);
    $i++;
  }
}

public function GeneralSettingStore(Request $request){
  //dd($request->all());
  $this->validate($request, [
    'timezone'        =>  'sometimes|required',
    ],[
    'timezone.required'         =>  'Please select TimeZone',
    ]);
  $destinationPath = AppHelper::USER_IMAGE;
  chmod($destinationPath,0777);

  if($request->input('id')==''){
    $general_setting = $request->all();
    $general_setting['user_id'] = Auth::id();
    $fileName = 'null';
    if(Input::hasFile('logo'))
    {
      $file = $request->file('logo');      
      if($file->isValid())
      {
        $ImagePath = $file->getRealPath();
        $name =  $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $encrypted_name = md5(uniqid().time()).".".$extension;
        
        $img = \Image::make($ImagePath);

        $img->resize(160, 160, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        $img->save($destinationPath.$encrypted_name,'70');
        //$file->move($destinationPath,$encrypted_name);
        $image_data = [ 'name'  =>  $name,  'encrypted_name'  =>  $encrypted_name];
        $general_setting['logo'] = $image_data['encrypted_name'];
      }
    }
    Setting::create($general_setting);
    return redirect('settings')->with('success', 'Settings added successfully!');
  }
  else{
    $general_setting = Setting::where(['id'=>$request->input('id')])->first();
    $profile = Profile::firstOrCreate(['user_id' => $general_setting->user_id]);

    if(Input::hasFile('logo')){
      $file = $request->file('logo');
      if($file->isValid()){
        $ImagePath = $file->getRealPath();
        if($general_setting->logo!=''){
          $Image = $destinationPath.$general_setting->logo;
          if(\File::exists($Image)){
            \File::delete($Image);
          }
        }
        $name =  $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $encrypted_name = md5(uniqid().time()).".".$extension;  
        $img = \Image::make($ImagePath);

        $img->resize(160, 160, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
        $img->save($destinationPath.$encrypted_name,'70');               
        
        $image_data = ['name' =>  $name, "encrypted_name"=>$encrypted_name ];
        $general_setting->update(['logo'=>$image_data['encrypted_name']]);
        
        //  Auth::user()->UserDetails->update(['logo'=>$image_data['encrypted_name']]);
        Auth::user()->UserDetails->update(['profile_pic'=>$image_data['encrypted_name']]);
      }
    }
    $general_setting->update($request->except(['_token','logo']));
    
    
      
    return redirect('settings')->with('success', 'Settings saved successfully!');
  }
}

public function deleteLogo($logo){
  $logo = Setting::find($logo);
  $filename = $logo->logo;
  $path = AppHelper::USER_IMAGE;
  if(file_exists($path.$filename))
  {
    File::delete($path.$filename);
    $logo->update(['logo'=>'']);
    Auth::user()->UserDetails->update(['logo'=> '']);
    return response()->json([
      'status'  =>  'success',
      'message' =>  'Data Removed'
      ]);
  }
}

public function getTimeZoneDate(Request $request){

  date_default_timezone_set($request->timezone);
  
  return response()->json([
    'status'  =>  'success',
    'date' =>  date('r')
    ]);
}

public function callSaveFTPCredential(Request $oRequest){
  foreach($oRequest->except(['_token']) as $sSiteSettingkey => $sSiteSettingValue){
    $oSiteSetting = SiteSetting::firstOrCreate(['setting_key' => $sSiteSettingkey]);
    $oSiteSetting->update(['setting_value' => $sSiteSettingValue]);
  }
  return response()->json([
      'status'  =>  'success',
      'message' =>  'Settings Saved'
    ]);
}

}
