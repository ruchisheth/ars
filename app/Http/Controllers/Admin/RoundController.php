<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\AppHelper;
use Redirect;
use Exception;
use Datatables;
use Auth;
use DB;

use App\FieldRepsCriteria;
use App\Project;
use App\Site;
use App\Assignment;
use App\FieldRep;
use App\survey_template;
use App\surveys;
use App\Chain;
use App\Round;


class RoundController extends Controller
{
  public function index(Request $oRequest)
  { 
    $res = parent::isDataAvailable('round','create.round');
    if($res === true)
    {
      
      $aProjectList = ['' => 'Select Project'] + Project::lists('project_name','id')->all();

      $sStatus = ($oRequest->has('status') ? (($oRequest->status == 'active') ? 1 : 0) : 1 );

      $aViewData = [
        'project_list' => $aProjectList,
        'status_filter' => $sStatus,
      ];

      return view('admin.rounds.rounds', $aViewData);
    }
    return $res;        
  }

  public function create(Request $request,$project_id = ''){

    $nRoundCode = DB::table('INFORMATION_SCHEMA.TABLES')->where(['TABLE_SCHEMA' => \Session::get('selected_database'), 'TABLE_NAME' => 'rounds'])->first()->AUTO_INCREMENT;
    
    $project_id = $request->project_id;
    $chain_name = '';
    $surveys = [NULL => 'Select Survey'] + DB::table('surveys_templates')->orderBy('id')->lists('template_name','id');

    if($project_id != ''){
      $project = Project::find($project_id);
      if($project){
        $chain = Chain::find($project->chain_id);
        $chain_name = $chain->chain_name;
      }
    }else{
      $res = parent::isDataAvailable('project','create.project');
      if($res === true){
        $projects = Project::with('chains')->get();
        $project_list = ['' => 'Select Project'];
        foreach ($projects as $project) {
          $project_list[$project->id] = $project->project_name ." - ". $project->chains->chain_name;
        }
      }
      else{
        return $res;
      }
    }
    return view('admin.rounds.create_round',compact('nRoundCode','project_id','surveys','project','chain_name', 'project_list'));

    //$projects = [NULL => 'Select Project'] + DB::table('projects')->orderBy('id')->lists('project_name','id');

  }

  public function store(Request $request)
  {
    $this->validate($request,[
      'project_id'          =>  'required',
      'round_name'          =>  'required',
      'start_date'          =>  'required',
      'deadline_date'       =>  'required|equal_or_after:start_date',
      'schedule_date'       =>  'required|equal_or_after:start_date|equal_or_before:deadline_date',
      // 'estimated_duration'  =>  'regex:/[0-9]{2}\:[0-9]{2}/',
      'bulletin_text'       =>  'required_if:is_bulletin,1',
      ],[
      'project_id.required'  =>  'Select a Project for Round',
      //'estimated_duration.regex'  =>  'The Estimated Duration should be in HH:MM format',
      'deadline_date.equal_or_after'      =>  'Deadline date must be greater than Start date.',
      'schedule_date.equal_or_after'      =>  'Schedule Date must be between Start date and Deadline Date.',
      'schedule_date.equal_or_before'     =>  'Schedule Date must be between Start date and Deadline Date.',
      'bulletin_text'                     =>  'Please enter something for pop up alert for fieldrep'
      ]);
    if($request->input('round_id') == '')
    {

      $this->validate($request,[
        'template_id'       =>  'required_if:status,1',            
        ],[
        'template_id.required_if'   =>  'You can not activate round unless a survey has been selected',
        ]);
      $inputs = $request->all(); 
      //dd($inputs);
      krsort($inputs);

      $round = new Round($inputs);
      
      //$round = new Round($request->all());

      //$round = Round::create($request->all());
      //dd($round);
      //$round->start_time = null;     
      // if($request->input('start_date')){
      //   $round->start_date =  date_formats($request->input('start_date'),AppHelper::DATE_SAVE_FORMAT);
      // }
      // if($request->input('deadline_date')){        
      //   $round->deadline_date = date_formats($request->input('deadline_date'),AppHelper::DATE_SAVE_FORMAT);
      // }
      // if($request->input('schedule_date')){
      //   $round->schedule_date = date_formats($request->input('schedule_date'),AppHelper::DATE_SAVE_FORMAT);
      // }
      // if($request->has('start_time')){              
      //   $round->start_time =  format_time($request->input('start_time'),AppHelper::TIME_SAVE_FORMAT);
      // }
      // if($request->has('deadline_time')){              
      //   $round->deadline_time =  format_time($request->input('deadline_time'),AppHelper::TIME_SAVE_FORMAT);
      // }
      //$round->estimated_duration =  format_time($request->input('estimated_duration'),AppHelper::TIME_SAVE_FORMAT);
      if($request->template_id == ""){
        $round->template_id = null;
      }
      $round->save();

            // make trigger for this
      $criteria = new FieldRepsCriteria;
      $criteria->round_id = $round->id;
      $criteria->save();
      $url = $request->input('url');
      return redirect()->route('edit.round',[$round->id])->with('success', 'Round added successfully!');
      
    }else{
      $round = Round::where(['id'=>$request->input('round_id')])->first();

      $round_id = $request->input('round_id');
      $template_id = $round->template_id;
      if($template_id == null){
        $this->validate($request,[
          'template_id'       =>  'required_if:status,1',            
          ],[
          'template_id.required_if'   =>  'You can not activate round unless a survey has been selected',
          ]);

      }

      $round->update($request->except(['_token']));
      if (!$request->has('is_bulletin'))
        $round->update(['is_bulletin'=>false]);

      //$round->start_time = null;

      // if($request->input('start_date')){
      //   $start_date =  date_formats($request->input('start_date'),AppHelper::DATE_SAVE_FORMAT);
      // }
      // if($request->input('deadline_date')){
      //   $deadline_date = date_formats($request->input('deadline_date'),AppHelper::DATE_SAVE_FORMAT);
      // }
      // if($request->input('schedule_date')){
      //   $schedule_date = date_formats($request->input('schedule_date'),AppHelper::DATE_SAVE_FORMAT);
      // }
      // if($request->input('start_time')){
      //   $start_time =  format_time($request->input('start_time'),AppHelper::TIME_SAVE_FORMAT);
      // }
      // if($request->input('deadline_time')){
      //   $deadline_time =  format_time($request->input('deadline_time'),AppHelper::TIME_SAVE_FORMAT);
      // }
      // if ($request->has('start_date')) {
      //   $round->update(['start_date'=> $start_date]);
      // }
      // if ($request->has('deadline_date')) {
      //   $round->update(['deadline_date'=> $deadline_date]);
      // }
      // if ($request->has('schedule_date')) {
      //   $round->update(['schedule_date'=> $schedule_date]);
      // }
      // if ($request->has('start_time')) {
      //   $round->update(['start_time'=> $start_time]);
      // }
      // if ($request->has('deadline_time')) {
      //   $round->update(['deadline_time'=> $deadline_time]);
      // }
      // if ($request->has('estimated_duration')) {
      //   $round->update(['estimated_duration'=>format_time($request->input('estimated_duration'),AppHelper::TIME_SAVE_FORMAT)]);
      // }
      if (!$request->has('is_receipt')) {
        $round->update(['is_receipt'=>'0']);
      }      
      $url = $request->input('url'); 
      $url2 = route('project.round.create', ['id' => $round->project_id]);
      if($url == $url2){
        return redirect()->route('edit.round',[$round->id])->with('success', 'Round saved successfully!');
      }

      return redirect($url)->with('success', 'Round saved successfully!');
    }
  }

  public function edit(Request $request,$id)
  {
    $round = Round::findorFail($id);
    
    $project = Project::find($round->project_id);
    $chain = Chain::find($project->chain_id);
    $chain_name = $chain->chain_name;

    $round->updated = date_formats(AppHelper::getLocalTimeZone($round->updated_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT); 
    $round->created = date_formats(AppHelper::getLocalTimeZone($round->created_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT);
    
    // $round->startDate = date_formats($round->start_date,AppHelper::DATE_DISPLAY_FORMAT);
    // $round->deadlineDate = date_formats($round->deadline_date,AppHelper::DATE_DISPLAY_FORMAT);
    // $round->scheduleDate = date_formats($round->schedule_date,AppHelper::DATE_DISPLAY_FORMAT);
    // $round->start_time = date_formats($round->start_time,AppHelper::TIME_DISPLAY_FORMAT); 
    // $round->deadline_time = date_formats($round->deadline_time,AppHelper::TIME_DISPLAY_FORMAT);


    $surveys = [NULL => 'Select Survey'] + DB::table('surveys_templates')->orderBy('id')->lists('template_name','id');

    $criteria = FieldRepsCriteria::where(['round_id'=>$id])->first();
    //dd($criteria);
    $assignment_count = $round->assignments->count();

    if($criteria == null){
      $criteria = new FieldRepsCriteria;
      $criteria->round_id = $id;
      $criteria->save();
    }


    $sites = get_available_sites($round);
    $payment_types = ['' => 'Select Payment Types'] + DB::table('_list')->where('list_name','=','payment_types')->orderBy('list_order')->lists('item_name','id','list_order');

    $data = [
    'round' => $round,
    'sites' => $sites,
    'payment_types' => $payment_types,
    'criteria' => $criteria,
    'surveys' => $surveys,
    'assignment_count' => $assignment_count,
    'project' => $project,
    'chain_name' =>  $chain_name
    ];  
    //return view('admin.rounds.create_round',compact('round','sites','payment_types','criteria','surveys','assignment_count','project','chain_name'));
    return view('admin.rounds.create_round',$data);

  }/* edit */

    function destroy(Request $oRequest){
    ARS::canOrFail('delete_round');
    try{
      //$round = Round::destroy($request->input('id'));
      $oRound = Round::find($oRequest->id);
      $oRound->delete();
      return response()->json(array(
       "status" => "success",
       "message"=>"Round removed successfully!",
     ));
    }catch(Exception $e){
      if($e instanceof \PDOException )
      {
        $nErrorCode = $e->getCode();
        if($nErrorCode == 23000){
          return response()->json([ 'message' => trans('messages.round_delete_error') ], 422);
        }
      }
    }

  }


  public function getdata(Request $request,$project_id = null)
  {
    $round_status = null;
    if($request->has('round_status')){
      $round_status = $request->get('round_status');
    }
    if($project_id != null){
      $projects = Project::find($project_id);
    }

    if(Auth::user()->roles->slug == 'admin'){
      $timezone = AppHelper::getSelectedTimeZone();
    }else{
      $timezone = \Session::get('timezone');
    }


    $rounds = DB::table('rounds as r')
    ->leftJoin('projects AS p', 'p.id', '=', 'r.project_id') 
    ->leftJoin('chains AS ch', 'ch.id', '=', 'p.chain_id') 
    ->leftJoin('clients AS c', 'c.id', '=', 'ch.client_id') 
    ->leftJoin('assignments AS a', 'a.round_id', '=', 'r.id')
    ->when(isset($round_status), function ($query) use ($round_status) {
      return $query->where('r.status', $round_status);
    })
    ->when(isset($projects), function ($query) use ($project_id) {
      return $query->where('r.project_id', $project_id);
    })    
    ->select([              
      'r.id',
      'r.round_name',                
      'r.start_date',
      'r.deadline_date',
      'r.schedule_date',
      'r.start_time',
      'r.deadline_time',
    //   DB::raw("CONVERT_TZ(CONCAT(DATE_FORMAT(r.start_date,'%e/%c/%Y'), ' ' , r.start_time), '+00:00','+04:00') as round_start"),
    //DB::raw("CONCAT(DATE_FORMAT(r.start_date,'%e/%c/%Y'), ' ' , r.start_time) as round_start"),
      DB::raw("CONVERT_TZ(CONCAT(DATE_FORMAT(r.start_date,'%e/%c/%Y'), ' ' , r.start_time), '+00:00','".$timezone."') as round_start"),
      
      DB::raw("CONCAT(DATE_FORMAT(r.deadline_date,'%e/%c/%Y'), ' ' , r.deadline_time) as round_end"),
      DB::raw('(select COUNT(id) from assignments where (is_scheduled = true) && round_id = r.id) as scheduled'),
      DB::raw('(select COUNT(id) from assignments where (is_reported = true) && round_id = r.id) as reported'),
      DB::raw('(select COUNT(id) from assignments where round_id = r.id) as assignment_count'),
      'r.status',
      'c.client_logo',
      'c.id as client_id',
      'p.id as project_id',
      'p.project_name',
      ])
    ->groupBy('r.id');

    if($request->order){
      if($request->columns[$request->order[0]['column']]['name'] == 'round_start'){
        $rounds->orderBy(DB::raw("str_to_date(round_start,'%d/%m/%Y %H:%i')"), $request->order[0]['dir']);
      }
      if($request->columns[$request->order[0]['column']]['name'] == 'round_end'){
        $rounds->orderBy(DB::raw("str_to_date(round_end,'%d/%m/%Y %H:%i')"), $request->order[0]['dir']);
      }
      if($request->columns[$request->order[0]['column']]['name'] == 'scheduled'){
        $rounds->orderBy('scheduled', $request->order[0]['dir']);
      }
      if($request->columns[$request->order[0]['column']]['name'] == 'reported'){
        $rounds->orderBy('reported', $request->order[0]['dir']);
      }
      if($request->columns[$request->order[0]['column']]['name'] == 'assignment_count'){
        $rounds->orderBy('assignment_count', $request->order[0]['dir']);
      }
    }

    $datatables = Datatables::of($rounds)
    
    ->addColumn('client_logo', function ($rounds) {
      $logo = AppHelper::getClientLogoImage($rounds->client_logo);
      return '<a href='.url("/clients-edit/").'/'.$rounds->client_id.'>'.$logo.'</a>';      
    })
    ->editColumn('project_name', function ($rounds) use($round_status){
      if($round_status == null){
        return $rounds->project_name;   
      }else{
        return '<a href='.url("/projects-edit/").'/'.$rounds->project_id.'>'. $rounds->project_name.'</a>';
      }
    })
    ->editColumn('round_name', function ($rounds) use($round_status){
      if($round_status == null){
        return $rounds->round_name;
      }else{
        return '<a href='.url("/rounds-edit/").'/'.$rounds->id.'>'. $rounds->round_name.'</a>';
      }
    })
    ->addColumn('action', function ($rounds) {
      return '<button class="btn btn-box-tool" type="button" name="remove_round" data-id="'.$rounds->id.'" value="delete" ><span class="fa fa-trash"></span></button>';
    })
    ->editColumn('id', function ($rounds) {
      return '<a href='.url("/rounds-edit/").'/'.$rounds->id.'>'. format_code($rounds->id).'</a>';
    })
    ->editColumn('assignment_count', function ($rounds) {
      return '<a target="_blank" href='.url("/assignments/").'?round_id='.$rounds->id.'>'. $rounds->assignment_count.'</a>';
    })
    ->editColumn('round_start', function ($rounds) use($timezone){
      $start_date = date_formats($rounds->start_date,AppHelper::DATE_DISPLAY_FORMAT);
      $start_time = date_formats($rounds->start_time,AppHelper::TIME_DISPLAY_FORMAT);
      $start_dt = $start_date.' '.$start_time;
      $start_dt = AppHelper::convertTimeZone($start_dt, 'UTC', $timezone);
      $start_date = date_formats($start_dt,AppHelper::DATE_DISPLAY_FORMAT);
      $start_time = date_formats($start_dt,AppHelper::TIME_DISPLAY_FORMAT);
      $start_dt = $start_date.' '.$start_time;
      return $start_dt;

      // $start_date = date_formats($rounds->start_date,AppHelper::DATE_DISPLAY_FORMAT);
      // $start_time = date_formats($rounds->start_time,AppHelper::TIME_DISPLAY_FORMAT) ?: '00:00 AM';
      // return $start_date . ' ' . $start_time;
    })
    ->editColumn('round_end', function ($rounds) use($timezone){
      $deadline_date = date_formats($rounds->deadline_date,AppHelper::DATE_DISPLAY_FORMAT);
      $deadline_time = date_formats($rounds->deadline_time,AppHelper::TIME_DISPLAY_FORMAT);
      $deadline_dt = $deadline_date.' '.$deadline_time;
      $deadline_dt = AppHelper::convertTimeZone($deadline_dt, 'UTC', $timezone);
      $deadline_date = date_formats($deadline_dt,AppHelper::DATE_DISPLAY_FORMAT);
      $deadline_time = date_formats($deadline_dt,AppHelper::TIME_DISPLAY_FORMAT);
      $deadline_dt = $deadline_date.' '.$deadline_time;
      return $deadline_dt;

      $deadline_date = date_formats($rounds->deadline_date,AppHelper::DATE_DISPLAY_FORMAT);
      $deadline_time = date_formats($rounds->deadline_date,AppHelper::TIME_DISPLAY_FORMAT) ?: '00:00 AM';
      return $deadline_date . ' ' . $deadline_time;
    })

    ->editColumn('schedule_date', function ($rounds) use($timezone) {
      $schedule_date = date_formats($rounds->schedule_date,AppHelper::DATE_DISPLAY_FORMAT);
      $start_time = date_formats($rounds->start_time,AppHelper::TIME_DISPLAY_FORMAT);
      $schedule_dt = $schedule_date.' '.$start_time;
      $schedule_dt = AppHelper::convertTimeZone($schedule_dt, 'UTC', $timezone);
      $schedule_date = date_formats($schedule_dt,AppHelper::DATE_DISPLAY_FORMAT);
      return $schedule_date;
      //return date_formats($schedule_date,AppHelper::DATE_DISPLAY_FORMAT);
    })
    ->editColumn('status', function ($rounds) {
      if($rounds->status == 1){
        return '<span class="label label-success">Active</span>';
      }elseif($rounds->status == 0){
        return '<span class="label label-danger">Inactive</span>';
      }elseif($rounds->status == 3){
        return '<span class="label label-default">Pending</span>';
      }
    });
    //->removeColumn('start_time');

    if($id = $request->get('project_id')) {
      $datatables->where('r.project_id', '=', "$id"); // additional users.name search
    }

    if ($request->get('status') != ''  ) {
      $status = $request->get('status');
      $datatables->where('r.status', 'like', "$status%"); 
    }

    $keyword = $request->get('search')['value'];

    $datatables->filterColumn('round_start', 'whereRaw', "CONCAT(DATE_FORMAT(r.start_date,'%d %b %Y'), ' ' , TIME_FORMAT(r.start_time, '%h:%i %p')) like ? ", ["%$keyword%"]);

    $datatables->filterColumn('round_end', 'whereRaw', "CONCAT(DATE_FORMAT(r.deadline_date,'%d %b %Y'), ' ' , TIME_FORMAT(r.deadline_time, '%h:%i %p')) like ? ", ["%$keyword%"]);

    $datatables->filterColumn('r.schedule_date', 'whereRaw', "DATE_FORMAT(r.schedule_date,'%d %b %Y') like ? ", ["%$keyword%"]);

    if (preg_match("/^".$keyword."/i", 'Active', $match)) :
      $datatables->filterColumn('r.status', 'where', '=', "1");
    endif;

    if (preg_match("/^".$keyword."/i", 'Inactive', $match)) :
      $datatables->filterColumn('r.status', 'where', '=', "0");
    endif;    

    return $datatables->make(true);
  }/* getdata*/
}
