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
use DB;
use Datatables;

use Config;

use Session;

use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redirect;

class FieldRepRatingController extends Controller
{
  public function store(Request $request)
  {
    $this->validate($request, 
      [
      "rating_category" =>  "required",
       "rater"  =>  "required",
       "rating" => "required",
      ],[
        'rater.required' => 'The User field is required'
      ]);

    if($request->input('id') == '' || $request->input('id') == '0'){
      $rating = new Rating($request->except(['_token']));
      $rating->effective_date = null;
      if($request->has('effective_date')){
       $rating->effective_date =  date_formats($request->input('effective_date'),AppHelper::DATE_SAVE_FORMAT);
      }
      $rating->save();
      return response()->json(array(
        "status" => "success",
        "message"=>"Rating added successfully!",
        ));
    }
    else{            
      $rating = Rating::where(['id'=>$request->input('id')])->first(); 
      $inputs = $request->except('__token'); 

      if($request->input('effective_date') != null){         
      $inputs['effective_date'] = date_formats($request->input('effective_date'),AppHelper::DATE_SAVE_FORMAT);
      }
      else{
       $inputs['effective_date'] = null;
      }
      $rating->update($inputs);
      return response()->json(array(
        "status" => "success",
        "message"=>"Rating saved successfully!",
        ));
    } 

  }

  public function edit(Request $request,$rating_id){

    $inputs = Rating::find($rating_id);

    $inputs->updated = date_formats(AppHelper::getLocalTimeZone($inputs->updated_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT); 
    $inputs->created = date_formats(AppHelper::getLocalTimeZone($inputs->created_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT);        

    if($inputs->effective_date <= 0)
    {    $inputs->effectiveDate = null;    }
    elseif(\Carbon::createFromFormat(AppHelper::DATE_SAVE_FORMAT, $inputs->effective_date) !== false){
        $inputs->effectiveDate = date_formats($inputs->effective_date,AppHelper::DATE_DISPLAY_FORMAT);           
    }
    $filteredArr = [
    'id'=>["type"=>"hidden",'value'=>$inputs->id],
    'rating_category'=>["type"=>"select",'value'=>$inputs->rating_category],
    'rater'=>["type"=>"select",'value'=>$inputs->rater],
    'rating'=>["type"=>"select",'value'=>$inputs->rating],
    'effective_date'=>["type"=>"text",'value'=>$inputs->effectiveDate],
    'created_at'=>["type"=>"label",'value'=>$inputs->created],
    'updated_at'=>["type"=>"label",'value'=>$inputs->updated],

    ];

    return response()->json(array(
     "status" => "success",
     "inputs"=>$filteredArr,
     ));
  }

  public function getdata(Request $request,$fieldrep_id)
  {
    $ratings = DB::table('ratings')->where('fieldrep_id', $fieldrep_id)->get(['id','rating_category','effective_date','rating','rater','status']);
    $ratings = Collection::make($ratings);
    return Datatables::of($ratings)
    ->addColumn('action', function ($ratings) {
      return '<button class="btn btn-box-tool" type="submit" onclick="SetEditRating(this)" data-id="'.$ratings->id.'" value="edit" >
      <span class="fa fa-edit"></span>
    </button>'.'<button class="btn btn-box-tool" type="submit" name="remove_rating" data-id="'.$ratings->id.'" value="delete" ><span class="fa fa-trash"></span></button>';
  })
    ->editColumn('rating_category', function ($ratings) {
      if($ratings->rating_category == 1 )
      {
        return 'Field Visit';
      }
      elseif ($ratings->rating_category == 2) {
        return 'Quality Assurance';
      }
      else
      {
        return 'Review';
      }
    })
    ->editColumn('rater', function ($ratings) {
      Config::set('database.default','mysql');
      $clients = DB::table('clients')->select('name')->where('id', '=', $ratings->rater)->first();
      Config::set('database.default',Session::get('selected_database'));
      return ucwords($clients->name);
    })
    ->editColumn('effective_date', function ($ratings) use($request) {
      $effective_date = date_formats($ratings->effective_date,AppHelper::DATE_DISPLAY_FORMAT);
      return $effective_date;
    })
    ->removeColumn('id')
    ->removeColumn('status')
    ->make();
  }

  public function deleteRating(Request $request){
    $rating = Rating::find($request->input('id'));
    $rating->delete();
    return response()->json(array(
     "status" => "success",
     "message"=>"Rating removed successfully",
     ));   
  }

}
