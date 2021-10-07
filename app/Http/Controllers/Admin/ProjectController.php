<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\AppHelper;
use Redirect;
use DB;
use App\AppData;
use App\Project;
use App\Client;
use App\Chain;
use App\Round;
use Datatables;
use Exception;
use Html;
use Illuminate\Support\Facades\Input;


class ProjectController extends Controller
{
  public function index(Request $oRequest){

    $res = parent::isDataAvailable('project','create.project');
    if($res === true){
      
      $aChainList = ['' => 'Select Chain'] + Chain::lists('chain_name','id')->all();
      
      $sStatus = ($oRequest->has('status') ? (($oRequest->status == 'active') ? 1 : 0) : 1 );

      $aViewData = [
        'chain_list' => $aChainList,
        'status_filter' => $sStatus,
      ];

      return view('admin.projects.projects', $aViewData);
    }
    return $res;
  }

  public function create(Request $request,$chain_id = '')
  {

    $project_id = Project::max('id');
    $project_id++;

    $project_types = ['' => 'Select Project Type'] + Project::getProjectTypes();

    $chains = ['' => 'Select Chain'] + Chain::lists('chain_name','id')->all();

    $contacts = ['' => 'Select Contact'];

    return view('admin.projects.create_project',compact('chains','project_id','project_types','chain_id','contacts'));
  }

  public function store(Request $request)
  {
    $this->validate($request,[
      'chain_id'          =>  'required',
      'project_name'      =>  'required',
      'project_type'      =>  'required',
      'primary_contact'   =>  'required',
      'billing_contact'   =>  'required',
      ],[
      "chain_id.required" => "Select Chain for the Project"
      ]);
    $inputs = $request->all();
    if (!$request->has('can_schedule')) {
      $inputs['can_schedule'] = 0;
    }
    $url = $request->input('url');

    if($request->input('project_id') == '') {
        //Add New Project
      $project = new Project($inputs);
      $project->save();
      $msg = 'Project added successfully!';
    }else{
        //Edit Project
      $project = Project::where(['id'=>$request->input('project_id')])->first();
      $project->update($inputs);
      $msg = 'Project saved successfully!';
    }
    return redirect($url)->with('success', $msg);
  }

  public function edit(Request $request,$id){


    $project = Project::findorFail($id);

    $project->updated = date_formats(AppHelper::getLocalTimeZone($project->updated_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT); 
    $project->created = date_formats(AppHelper::getLocalTimeZone($project->created_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT);

    $chains = ['' => 'Select Chain'] + Chain::lists('chain_name','id')->toArray();
    $round_id = Round::max('id');
    $round_id++;

    $contacts = ['' => 'Select Contact'];
    $chain = Chain::find($project->chain_id);
    if($chain->clients->contacts->count() > 0){
      $contacts = $contacts + $chain->clients->contacts->lists('first_name','id')->toArray();
    }

    $project_types = ['' => 'Select Project Type'] + Project::getProjectTypes();

    return view('admin.projects.create_project',compact('project','chains','round_id','project_types','contacts'));
  }

  public function destroy(Request $request){
    try{
      $project = Project::find($request->input('id'));
      $project->delete();
      return response()->json(array(
       "status" => "success",
       "message"=>"Project removed successfully!",
       )); 
    }
    catch(Exception $e){

      if($e instanceof \PDOException )
      {
        $error_code = $e->getCode();
        if($error_code == 23000){
         return response()->json([ 'message' => "Project can not be deleted, it has Rounds" ], 422);

       }
     }
   }
 }

 public function getdata(Request $request){
  $projects = DB::table('projects as p')
  ->leftJoin('rounds AS r', 'p.id', '=', 'r.project_id')
  ->leftJoin('chains as ch','ch.id','=','p.chain_id')
  ->leftJoin('clients as c','c.id','=','ch.client_id')
  ->select([
    'p.id',
    'p.chain_id',
    'p.project_name',
    'ch.chain_name',
    'c.client_logo',
    'c.client_name',
    DB::raw('sum((select COUNT(id) as assignment_count from assignments where round_id = r.id)) as assignment_count'),         
    DB::raw('(select COUNT(rounds.id) as round_count from rounds where project_id = p.id) as round_count'),
    DB::raw('(select COUNT(rounds.id) as round_count from rounds where project_id = p.id && rounds.status = 1) as active_round_count'),           
    'p.status'])
  ->groupBy('p.id');

  $datatables = Datatables::of($projects)
  ->addColumn('details_url', function($projects) {
    return url('/rounds/' . $projects->id);
  })
  ->addColumn('project_code', function($projects) {
    return $projects->id;
  })
  ->editColumn('id', function($projects) {
    return $projects->id;
  })
  ->editColumn('client_name', function($projects) {
    return $projects->client_name;
  })
  ->editColumn('client_logo', function ($projects) {
    $logo = AppHelper::getClientLogoImage($projects->client_logo);
    return $logo;
  })
  ->editColumn('project_name', function ($projects) {
    return $projects->project_name;
  })

  ->addColumn('action', function($projects) {
    $html = '';
    $html .= '<button class="btn btn-box-tool" type="submit" name="remove_project" data-id="'.$projects->id.'" value="delete" title="delete"><span class="fa fa-trash"></span></button>';
    return $html;

  })
  ->editColumn('id', function ($projects) {
    return '<a href='.url("/projects-edit/").'/'.$projects->id.'>'. format_code($projects->id).'</a>';
  })
  ->editColumn('round_count',function($projects){
    $html = '';
    $border_class = '';

    $count = $projects->round_count;
    $html .= '<a href="javascript:void(0)" id="round_count">'. $projects->round_count.' rounds<br></a>';
    $html .= '<a href='.url("/rounds-edit/project").'/'.$projects->id.' class="text text-gray"><i class="fa fa-plus"></i> New</a><br>';                
    return $html; 
  })
  ->editColumn('status', function ($chains) {
    if($chains->status == 1){
      return '<span class="label label-success">Active</span>';
    }else{
      return '<span class="label label-danger">Inactive</span>';
    }
  })
  ->removeColumn('round_name')
  ->removeColumn('active_round_count');

  if ($id = $request->get('chain_id')) {
           $datatables->where('p.chain_id', '=', "$id"); // additional users.name search
         }

         if ($request->get('status') != ''  ) {
           $status = $request->get('status');
           $datatables->where('p.status', 'like', "$status%"); 
         }       

         $keyword = $request->get('search')['value'];

         if (preg_match("/^".$keyword."/i", 'Active', $match)) :
          $datatables->filterColumn('p.status', 'where', '=', "1");
        endif;

        if (preg_match("/^".$keyword."/i", 'Inactive', $match)) :
          $datatables->filterColumn('p.status', 'where', '=', "0");
        endif;

        return $datatables->make(true);
      }
    }
