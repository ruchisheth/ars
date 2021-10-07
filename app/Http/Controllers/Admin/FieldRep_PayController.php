<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests;

use App\Http\AppHelper;

use Validator;

use App\Site;

use App\Client;

use App\Chain;

use App\FieldRep;

use App\Project;

use App\Rating;

use App\FieldRep_Pay;

use DB;

use Datatables;

use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redirect;

class FieldRep_PayController extends Controller
{

	public function create(){

	}

    public function store(Request $request)
    {

        $this->validate($request, [
            "project_type" =>'required',
            "client_id" => 'required',
            "item"  =>  "required",
            "rate"  => "required",
            // "pay_type"	=>	"required",
            ],[
      'client_id.required' => 'The Client field is required',

      ]);  
        //dd($request->except(['_token']));
        if($request->input('id') == '' || $request->input('id') == '0'){
            $fieldrep_pay = new FieldRep_Pay($request->except(['_token']));
            $fieldrep_pay->save();
            return response()->json(array(
                "status" => "success",
                "message"=>"Payment rule added successfully!",
                ));
        }
        else{            
            $fieldrep_pay = FieldRep_Pay::where(['id'=>$request->input('id')])->first(); 
            $inputs = $request->except('__token'); 
            $fieldrep_pay->update($inputs);
            return response()->json(array(
                "status" => "success",
                "message"=>"Payment rule saved successfully",
                ));
        } 
    }

    public function edit(Request $request,$fieldrep_pay_id){

      $inputs = FieldRep_Pay::find($fieldrep_pay_id);
      $inputs->updated = date_formats(AppHelper::getLocalTimeZone($inputs->updated_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT); 
      $inputs->created = date_formats(AppHelper::getLocalTimeZone($inputs->created_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT);

      $filteredArr = [
      'id'=>["type"=>"hidden",'value'=>$inputs->id],
      'project_type'=>["type"=>"select",'value'=>$inputs->project_type],
      'client_id'=>["type"=>"select",'value'=>$inputs->client_id],
      'item'=>["type"=>"select",'value'=>$inputs->item],
      'rate'=>["type"=>"text",'value'=>$inputs->rate],
      'pay_type'=>["type"=>"select",'value'=>$inputs->pay_type],
      'notes'=>["type"=>"textarea",'value'=>$inputs->notes],
      'created_at'=>["type"=>"label",'value'=>$inputs->created],
      'updated_at'=>["type"=>"label",'value'=>$inputs->updated],

      ];

		       //	dd($filteredArr);
      return response()->json(array(
         "status" => "success",
         "inputs"=>$filteredArr,
         ));
  }

  public function getdata($fieldrep_id){

    $fieldrep_pays = DB::table('fieldrep_pays')->where('fieldrep_id',$fieldrep_id)->get(['id','client_id','project_type','item','rate','pay_type','status']);

    $fieldrep_pays = Collection::make($fieldrep_pays);


    return Datatables::of($fieldrep_pays)

    ->addColumn('action', function ($fieldrep_pays) {
        return '<button class="btn btn-box-tool" type="submit" onclick="SetEdit(this)" data-id="'.$fieldrep_pays->id.'" value="edit" >
        <span class="fa fa-edit"></span>
    </button>'.'<button class="btn btn-box-tool" type="submit" name="remove_fieldrep_pay" data-id="'.$fieldrep_pays->id.'" value="delete" ><span class="fa fa-trash"></span></button>';
})

    ->editColumn('client_id', function ($fieldrep_pays) {
     $clients = DB::table('clients')->select('client_name')->where('id', '=', $fieldrep_pays->client_id)->first();

     return ucwords($clients->client_name);
 })

    ->editColumn('project_type', function ($fieldrep_pays) {
      $project_types = Project::getProjectTypes();
      return $project_types[$fieldrep_pays->project_type];
  })

    ->editColumn('item', function ($fieldrep_pays) {
        if($fieldrep_pays->item == 1 )
        {
         return 'Advance Check';
     }
     elseif ($fieldrep_pays->item == 2) {
        return 'Apron';
    }
    elseif ($fieldrep_pays->item == 3) {
      return 'Asignment Pay/Time Spent';
  }
  elseif ($fieldrep_pays->item == 4) {
      return 'Bonus';
  }
  elseif ($fieldrep_pays->item == 5) {
      return 'Drive Time';
  }
  elseif ($fieldrep_pays->item == 6) {
      return 'Expense - Purchases';
  }
  elseif ($fieldrep_pays->item == 7) {
      return 'Late Paperwork';
  }
  elseif ($fieldrep_pays->item == 8) {
      return 'Late Reprting';
  }
  else{
     return 'Parking';
 } 
})

    ->editColumn('rate', function ($fieldrep_pays) {
        if($fieldrep_pays->pay_type == 0 )
        {
         return $fieldrep_pays->rate.'/H';
     }
     else{
         return $fieldrep_pays->rate.'/A';
     } 
 })

    ->removeColumn('id')
    ->removeColumn('pay_type')
    ->removeColumn('status')
    ->make();
}

public function deleteFieldRep_Pay(Request $request){
    $fieldrep_pays = FieldRep_Pay::find($request->input('id'));
    $fieldrep_pays->delete();
    return response()->json(array(
       "status" => "success",
       "message"=>"Payment Rule removed successfully",
       ));   
}
}
