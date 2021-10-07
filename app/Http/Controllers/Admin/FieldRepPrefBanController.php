<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\AppHelper;

use Illuminate\Database\Eloquent\Collection;

use Validator;

use App\Site;

use App\Client;

use App\Chain;

use App\FieldRep;

use App\Project;

use App\Rating;

use App\PrefBan;

use DB;

use Datatables;

use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redirect;

class FieldRepPrefBanController extends Controller
{
  public function store(Request $request){

    $this->validate($request, 
      [
      "pref_ban"  =>  "required",
      "chain_id"  =>  "required",
      "site_id"   =>  "required",
      'activity'  =>  'required',
      ],[
        'chain_id.required' => 'The Chain field is required.',
        'site_id.required' => 'The Site field is required.',
      ]);

    $activity = $request->get('activity');
    $chain_id = $request->get('chain_id');
    $site_id = $request->get('site_id');

    $this->validate($request, 
      [
        'fieldrep_id' => 'unique_with:fieldrep_prefbans,chain_id=>'.$chain_id.':site_id=>'.$site_id.':activity=>'.$activity.',id,'.$request->input('id'),
      ],[
          'fieldrep_id.unique_with'  =>  'The Preferance already exist',
      ]);

    

    if($request->input('id') == '' || $request->input('id') == '0'){
      $prefban = new PrefBan($request->except(['_token']));
      $prefban->save();
      return response()->json(array(
        "status" => "success",
        "message"=>"Pref/Ban added successfully!",
        ));
    }
    else{            
      $prefban = PrefBan::where(['id'=>$request->input('id')])->first(); 
      $inputs = $request->except('__token'); 
      $prefban->update($inputs);
      return response()->json(array(
        "status" => "success",
        "message"=>"Pref/Ban saved successfully!",
        ));
    } 
  }

  public function changesite(Request $request){
    $chain_id = $request->input('chain_id');
    $site_ids =  DB::table('sites')->where('chain_id','=', $chain_id)->lists('site_name','id');

    return response()->json(array(
      "status" => "success",              
      'site_ids' => $site_ids,
      ));

  }

  public function edit(Request $request,$prefban_id){

    $inputs = PrefBan::find($prefban_id);

    $inputs->updated = date_formats(AppHelper::getLocalTimeZone($inputs->updated_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT); 
    $inputs->created = date_formats(AppHelper::getLocalTimeZone($inputs->created_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT);     

    $filteredArr = [
    'id'=>["type"=>"hidden",'value'=>$inputs->id],
    'chain_id'=>["type"=>"select",'value'=>$inputs->chain_id],
    'site_id'=>["type"=>"select",'value'=>$inputs->site_id,'wait'=>'500'],
    'activity'=>["type"=>"select",'value'=>$inputs->activity],
    'pref_ban'=>["type"=>"radio",'checkedValue'=>$inputs->pref_ban],
    'created_at'=>["type"=>"label",'value'=>$inputs->created],
    'updated_at'=>["type"=>"label",'value'=>$inputs->updated],

    ];

    return response()->json(array(
     "status" => "success",
     "inputs"=>$filteredArr,
     ));
  }

  public function getdata($fieldrep_id){


    $prefbans = DB::table('fieldrep_prefbans')->where('fieldrep_id', $fieldrep_id)->get(
      ['id','chain_id','site_id','activity','pref_ban','status']);

      //dd($prefbans);

    $prefbans = Collection::make($prefbans);

    return Datatables::of($prefbans)

    ->addColumn('action', function ($prefbans) {

      return '<button class="btn btn-box-tool" type="submit" onclick="SetEditPrefBan(this)" data-id="'.$prefbans->id.'" value="edit" >
      <span class="fa fa-edit"></span>
    </button>'.'<button class="btn btn-box-tool" type="submit" name="remove_prefban" data-id="'.$prefbans->id.'" value="delete" ><span class="fa fa-trash"></span></button>';
  })

    ->editColumn('chain_id', function ($prefbans) {
      $chains = DB::table('chains')->select('chain_name')->where('id', '=', $prefbans->chain_id)->first();
      return $chains->chain_name;
    })

    ->editColumn('site_id', function ($prefbans) {
     $sites = DB::table('sites')->select('site_name')->where('id', '=', $prefbans->site_id)->first();
     return $sites->site_name;
   })  	

    ->editColumn('activity', function ($prefbans) {
      $project_types = Project::getProjectTypes();
      return $project_types[$prefbans->activity];


    // $activity = DB::table('_list')->where([['list_name','=','rep_activity'],['id',$prefbans->activity],])->first();
    //              	//dd($activity);
    // return $activity->item_name;
    })

    ->editColumn('pref_ban', function ($prefbans) {
     if($prefbans->pref_ban == 0)
     {
       return '<span class="label label-success">P</span>';
     }
     else{
       return '<span class="label label-danger">B</span>';
     }

   })

    ->removeColumn('id')
    ->removeColumn('status')
    ->make();
  }

  public function deletePrefBan(Request $request){
    $prefban = PrefBan::find($request->input('id'));
    $prefban->delete();
    return response()->json(array(
     "status" => "success",
     "message"=>"Pref/Ban removed successfully",
     ));   
  }

}
