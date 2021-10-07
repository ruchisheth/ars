<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\AppHelper;
use Html;
use View;
use App;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exceptions\SurveyNotAvailableException;
use PDFS;
use Response;
use Carbon;
use App\surveys,
App\surveys_template,
App\Assignment,
App\Project,
App\Round,
App\FieldRep,
App\User,
App\Emailer,
App\Setting,
App\Site,
Auth,  
DB,
Crypt,
Datatables,
Excel;

// use Barryvdh\DomPDF\Facade as PDFF;

class SurveyController extends Controller
{


  public function index(){
    $res = parent::isDataAvailable('survey','');
    if($res === true){
      //$site_code = Site::select([DB::raw('lpad(trim(site_code), 10, 0) AS SCODE')])->orderBy('SCODE', 'desc')->distinct()->pluck('SCODE');
      $site_code = Site::select(['site_code'])->orderBy(DB::raw('lpad(trim(site_code), 10, 0)'), 'acs')->distinct()->pluck('site_code')->toArray();
      $site_code = array_combine($site_code, $site_code);
      $site_code = ['' => 'Select Site Code'] + $site_code;
      $project_list = ['' => 'Select Project'] + Project::lists('project_name','id')->all();
      $round_list = ['' => 'Select Round'] + Round::lists('round_name','id')->all();

      $data = [
      'project_list' => $project_list,
      'round_list'   => $round_list,
      'site_code'    => $site_code
      ];

      return view('admin.surveys.surveys', $data);
    }
    return $res;
  }

  public function ReviewSurvey($id){

    $OldSurvey = surveys::findorFail($id);
    $assingment = $OldSurvey->assignments;
    if(!$assingment->is_reported && !$assingment->is_partial){
      throw new SurveyNotAvailableException();
    }
    $CreatedTemplate = $OldSurvey;

    $survey_details = (object)$OldSurvey->getSurveyDetail($OldSurvey);

    $payment_types = ['' => 'Select Payment Types'] + DB::table('_list')->where('list_name','=','payment_types')->orderBy('list_order')->lists('item_name','id','list_order');

    $questions = unserialize($CreatedTemplate->keypairs);

    // if($questions[0]['que_no'] == 'template_name'){
    //   unset($questions[0]);
    // }

    //reindex question array start from index 1 instead 0
    //$questions = array_filter(array_merge(array(0), $questions));

    $data = [
    'id'                =>  $id,
    'survey_template'   =>  $CreatedTemplate,
    'survey_details'    =>  $survey_details,
    'payment_types'     =>  $payment_types,
    'assignment_id'     =>  $OldSurvey->assignments->id,
    'questions'         =>  $questions
    ];
    return view('admin.surveys.review_survey',$data);
  }

  public function exportSurvey($survey_id){
    $id = $survey_id;
    $oSurvey = surveys::find($id);

    $survey_details = (object)$oSurvey->getSurveyDetail($oSurvey);
    $oAssignment = Assignment::find($oSurvey->assignment_id);

    $data = [
      'id'                =>  $id,
      'oSurvey'           =>  $oSurvey,
      'survey_details'    =>  $survey_details,
      'oAssignment'       =>  $oAssignment,

    ];
    return app('snappy.pdf.wrapper')
    ->loadHTML(view('admin.surveys.export_survey', $data)->render())
    ->setPaper('a4')->setOption('margin-left', 0)->setOption('margin-right', 0)
    //->stream('survey' . rand(10000, 99999) . '.pdf');
    //->inline('survey' . rand(10000, 99999) . '.pdf');
    ->download('survey' . rand(10000, 99999) . '.pdf');
  }


  public function getdata(Request $request){
    $current_date = Carbon::now(\Session::get('timezone'))->toDateString();
    $status = $request->input('status');

    $api_key = Setting::where(['user_id' => Auth::id()])->first()->syi_api_key;
    $client = new \GuzzleHttp\Client();
    // if($round_id == ''){
    //$assignments = DB::table('assignments as a')
    // $surveys = surveys::with('assignments.rounds.projects.chains.sites')
    //       ->with('surveys_templates')
    //       ->with('assignments.sites')
    //       ->with('assignments.fieldreps')
    //       ->take(5)->get();

    $surveys = surveys::from('surveys as su')
    ->leftJoin('assignments as a', 'su.assignment_id', '=', 'a.id')
    ->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
    ->leftJoin('projects as p', 'r.project_id', '=', 'p.id')
    ->leftJoin('sites as s',  'a.site_id', '=', 's.id')
    ->leftJoin('fieldreps as f', 'a.fieldrep_id', '=', 'f.id')
    ->leftJoin('chains as ch','p.chain_id','=','ch.id')
    ->leftJoin('clients as c','ch.client_id','=','c.id')
    ->leftJoin('surveys_templates as t', 'su.template_id', '=', 't.id')
    ->where(function ($query) {
      $query->where('a.is_reported', '=', true)
      ->orWhere('a.is_partial', '=', true)
      ->orWhere('a.is_approved', '=', true);
    })
    //->where('su.service_code', '!=', '')
    ->select([
      'a.id as assignment_id',
      'a.fieldrep_id',
      'a.deadline_date',
      'a.is_reported',
      'a.is_partial',
      'a.is_approved',
      'c.client_logo',
      'p.id as project_id',
      'p.project_name',
      'r.id as round_id',
      'r.round_name',
      's.site_code',
      's.site_name',
      's.city',
      's.state',
      's.zipcode',
      'su.id as survey_id',
      'su.status',
      'su.service_code',
      't.template_name',
      DB::raw("DATE_FORMAT(CONVERT_TZ(TIMESTAMP(IFNULL( a.schedule_date, r.schedule_date), IFNULL(a.start_time, r.start_time)), 'GMT', '".AppHelper::getSelectedTimeZone()."'),'%d %b %Y %h:%i %p') as assignment_scheduled"),
    //   DB::raw("CONCAT(IFNULL( DATE_FORMAT(a.schedule_date,'%e/%c/%Y'), DATE_FORMAT(r.schedule_date,'%e/%c/%Y')), ' ' , IFNULL(a.start_time, r.start_time)) as assignment_scheduled"),
    //   DB::raw("CONCAT(IFNULL( DATE_FORMAT(a.schedule_date,'%d %b %Y'), DATE_FORMAT(r.schedule_date,'%d %b %Y')), ' ' , IFNULL(a.start_time, r.start_time)) as assignment_scheduled_date"),
      DB::raw("CONCAT(DATE_FORMAT(a.start_date,'%d %b %Y'),' ',a.start_time) as assignment_starts"),
      DB::raw('CONCAT(f.first_name," ",f.last_name) as schedule_to'),
      ]);

    if($request->columns[$request->order[0]['column']]['name'] == 's.site_code'){
      $surveys->orderBy(DB::raw('lpad(s.site_code, 10, 0)'), $request->order[0]['dir']);
    }
    if($request->columns[$request->order[0]['column']]['name'] == 's.city'){
      $surveys->orderBy(DB::raw("CONCAT(s.site_name,', ',s.city,',',s.state,' ',s.zipcode)"), $request->order[0]['dir']);
    }
    if($request->columns[$request->order[0]['column']]['name'] == 'assignment_scheduled'){
      $surveys->orderBy(DB::raw("str_to_date(assignment_scheduled,'%d/%m/%Y %H:%i')"), $request->order[0]['dir']);
    }
    if($request->columns[$request->order[0]['column']]['name'] == 'schedule_to'){
      $surveys->orderBy(DB::raw("CONCAT(f.first_name,' ',f.last_name)"), $request->order[0]['dir']);
    }

    $datatables = Datatables::of($surveys)
    ->editColumn('client_logo', function ($surveys) {
      $logo = AppHelper::getClientLogoImage($surveys->client_logo);
      return $logo;
    })
    ->editColumn('site_code', function ($surveys) {
      $html = '';
      $html .= '<a target="_balnk" href='.url("/review-survey/").'/'.$surveys->survey_id.'>'. $surveys->site_code.'</a>';
      return $html;
    })  
    ->editColumn('project_name', function ($surveys){
      return '<a target="_balnk" href='.url("/projects-edit/").'/'.$surveys->project_id.'>'. $surveys->project_name.'</a>'; 
    })
    ->editColumn('round_name', function ($surveys){
      return '<a target="_balnk" class="" href='.url("/rounds-edit/").'/'.$surveys->round_id.'>'.$surveys->round_name.'</a>'; 
    })
    ->editColumn('city', function ($surveys) {
      return $surveys->site_name.', '.format_location($surveys->city,$surveys->state,$surveys->zipcode);
    })
    ->editColumn('assignment_starts', function ($surveys) {
      $assignment = Assignment::find($surveys->assignment_id);
      $date =  $assignment->getAssignmentStartDate();
      $time =  $assignment->getAssignmentStartTime();
      return $date.' '.$time;
    })
    // ->editColumn('assignment_scheduled_date', function ($surveys) {
    //   return $surveys->assignment_scheduled_date;
    // })
    ->editColumn('status', function ($surveys) {
      $assignment = Assignment::find($surveys->assignment_id);
      $status = $assignment->getAssignmentStatus();
      return $status;
    })
    ->editColumn('schedule_to', function ($surveys) {
      return  $surveys->schedule_to;
    })
    ->addColumn('action', function ($surveys) {
      return '<a class="btn btn-box-tool" name="export-data" data-id="'.$surveys->survey_id.'" value="export-data" title="export data" href="'.env('APP_URL').'export-survey/'.$surveys->survey_id.'"><span class="fa fa-upload"></span></a>';
    })
    ->addColumn('is_invoiced', function ($surveys) use($client, $api_key)  {
      //return "";
      if($surveys->service_code != '' && $api_key != NULL){
        $api_key = Setting::find(1)->syi_api_key;
        $res = $client->get(AppHelper::SYI_URL.'api/is-invoiced?api_token='.$api_key.'&assignment_id='.$surveys->assignment_id.'&service_code='.$surveys->service_code);
        $stream = $res->getBody();
        $data = json_decode($stream->getContents()); 
        if($data->is_invoiced){
          return '<span class="btn-box-tool"><i class="fa fa-check text-success"></i></span>';
        } else{
          return '<span class="btn-box-tool"><i class="fa fa-times text-danger"></i></span>';
        }
      }
      return '';
    });

    if ($id = $request->get('round_id')) {
      $datatables->where('a.round_id', '=', "$id"); // additional users.name search
    }

    if ($project_id = $request->get('project_id')) {
      $datatables->where('r.project_id', '=', "$project_id"); // additional project filter
    }

    if ($id = $request->get('project_id')) {
      $datatables->where('r.project_id', '=', "$id"); // additional users.name search
    }

    if ($round_id = $request->get('round_id')) {
      $datatables->where('a.round_id', '=', "$round_id"); // additional users.name search
    } 

    if ($site_code = $request->get('site_code')) {
      $datatables->where('s.site_code', 'like', $site_code.'%'); // additional users.name search
    }

    if ($request->get('status') != ''  ) {
      $status = $request->get('status');
      if($status == "reported"){
        $datatables->where('a.is_reported', '=', true)->where('a.is_approved', '=', false);
      }elseif($status == "partial"){
        $datatables->where('a.is_partial', '=', true);
      }elseif($status == "approved"){
        $datatables->where('a.is_approved', '=', true);
      }
    }

    $keyword = $request->get('search')['value'];
    
    if($keyword != '' || $request->get('columns')['5']['search']['value'] != ""){
      $keyword = ($request->get('columns')['5']['search']['value'] != '') ? $request->get('columns')['5']['search']['value'] : $keyword ;
      $datatables->filterColumn('s.city', 'whereRaw', "CONCAT(s.site_name,', ',s.city,',',s.state,' ',s.zipcode) like ? ", ["%$keyword%"]);
    }

    if($keyword != '' || $request->get('columns')['6']['search']['value'] != ""){
      $keyword = ($request->get('columns')['6']['search']['value'] != '') ? $request->get('columns')['6']['search']['value'] : $keyword ;
      $datatables->filterColumn('assignment_scheduled', 'whereRaw', "DATE_FORMAT(CONVERT_TZ(TIMESTAMP(IFNULL( a.schedule_date, r.schedule_date), IFNULL(a.start_time, r.start_time)), 'GMT', '".AppHelper::getSelectedTimeZone()."'),'%d %b %Y %h:%i %p') like ? ", ["%$keyword%"]);
    }

    if($keyword != '' || $request->get('columns')['8']['search']['value'] != ""){
      $keyword = ($request->get('columns')['8']['search']['value'] != '') ? $request->get('columns')['8']['search']['value'] : $keyword ;     
      $datatables->filterColumn('schedule_to', 'whereRaw', "CONCAT(f.first_name,' ',f.last_name) like ? ", ["%$keyword%"]);
    }

    // $datatables->filterColumn('s.city', 'whereRaw', "CONCAT(s.site_name,', ',s.city,',',s.state,' ',s.zipcode) like ? ", ["%$keyword%"]);

    // $datatables->filterColumn('assignment_scheduled', 'whereRaw', "CONCAT(IFNULL( DATE_FORMAT(a.schedule_date,'%d %b %Y'), DATE_FORMAT(r.schedule_date,'%d %b %Y')), ' ' , IFNULL(a.start_time, r.start_time)) like ? ", ["%$keyword%"]);

    // $datatables->filterColumn('schedule_to', 'whereRaw', "CONCAT(f.first_name,' ',f.last_name) like ? ", ["%$keyword%"]);

    return $datatables->make(true);
  }



  public function getdatas(Request $request){

    $surveys = DB::table('surveys as s')
    ->whereIn('s.status',[2,3,4])
    ->leftJoin('surveys_templates as t', 's.template_id', '=', 't.id')
    ->leftJoin('fieldreps as f', 's.reference_id', '=', 'f.id')
    ->leftJoin('assignments as a','a.id','=','s.assignment_id')
    ->leftJoin('sites as si','a.site_id','=','si.id')
    ->leftJoin('rounds as r','r.id','=','a.round_id')
    ->leftJoin('projects as p','p.id','=','r.project_id')
    ->leftJoin('chains as ch','ch.id','=','p.chain_id')
    ->leftJoin('clients as c','c.id','=','ch.client_id')
    ->select([
      's.id',
      'r.id as round_id',
      'r.round_name',
      'a.id as assignment_id',
      'a.fieldrep_id',
      'c.client_logo',
      't.template_name',
      'p.id as project_id',
      'si.site_code',
      'f.first_name',
      'f.last_name',
      //DB::raw('CONCAT(f.first_name," ",f.last_name) as fieldrep_name'),
      's.status'
      ]);

    if($request->has('order')){
      if($request->columns[$request->order[0]['column']]['name'] == 'si.site_code'){
        $surveys->orderBy(DB::raw('lpad(si.site_code, 10, 0)'), $request->order[0]['dir']);
      }
    }else{
      $surveys->orderBy('s.status','asc')->orderBy('s.updated_at','desc');
      
    }

    $datatables = Datatables::of($surveys)
    ->addColumn('action', function ($surveys) {
      return '<a class="btn btn-box-tool" name="export-data" data-id="'.$surveys->id.'" value="export-data" title="export data" href="'.env('APP_URL').'export-survey/'.$surveys->id.'"><span class="fa fa-upload"></span></a>';
    })
    ->editColumn('client_logo', function ($surveys) {
      $logo = AppHelper::getClientLogoImage($surveys->client_logo);
      return $logo;
    })
    ->editColumn('first_name', function ($surveys) {
      return $surveys->first_name." ".$surveys->last_name;
    })
    ->editColumn('site_code', function ($surveys) {
      return '<a href='.url("/review-survey/").'/'.$surveys->id.'>'. $surveys->site_code.'</a>';
    })
    ->editColumn('round_name', function ($surveys) {
      $round_name = "[".format_code($surveys->round_id)."]-".$surveys->round_name;
      return $round_name;
    })
    ->editColumn('status', function ($surveys) {
      $survey_satus = '';
      $survey = surveys::find($surveys->id);
      $survey_satus = $survey->getSurveyStatus($survey->status);
      return $survey_satus;
    });

    if ($id = $request->get('project_id')) {
      $datatables->where('r.project_id', '=', "$id"); // additional users.name search
    }

    if ($round_id = $request->get('round_id')) {
      $datatables->where('a.round_id', '=', "$round_id"); // additional users.name search
    }

    if ($request->get('status') != ''  ) {
      $status = $request->get('status');
      $datatables->where('s.status', 'like', "$status%"); 
    }

    if ($keyword = $request->get('search')['value']) {
      $datatables->filterColumn('f.first_name', 'whereRaw', "CONCAT(f.first_name,' ',f.last_name) like ? ", ["%$keyword%"]);
    }


    return $datatables->make(true);
  }

  public function markAsPartial($survey_id)
  {
    $survey = surveys::where('id', $survey_id)->first();
    $assignment = Assignment::where(['id'=>$survey->assignment_id])->first();

    //$survey->update(['status'=>3]);
    $survey->update(['status'=>1]);
    return $assignment->markAsPartial();
    
  }

  public function markAsApproved($survey_id)
  {
    $survey = surveys::where('id', $survey_id)->first();
    $assignment = Assignment::where(['id'=>$survey->assignment_id])->first();
    $client = new \GuzzleHttp\Client();
    $api_key = Setting::find(1)->syi_api_key;

    
    $assignment->markAsApproved();
    $res = $client->get(AppHelper::SYI_URL.'api/mark-as-approved',[
      'query' => [  'api_token'       =>  $api_key,
      'assignment_id'   =>  $survey->assignment_id,
      'service_code'    =>  $survey->service_code]
      ]);

    return true;

  }

  public function changeStatus(Request $request){    
    $survey_id = $details['survey_code'] = $request->input('id');
    $status =  $assignment_status = $request->input('status');        
    $msg = '';

    if($status == 'partial'){
      $this->markAsPartial($survey_id);
    }elseif ($status == 'approved') {      
      $this->markAsApproved($survey_id);
    }

    $survey = surveys::where('id', $survey_id)->first();
    $Assignment = Assignment::where(['id'=>$survey->assignment_id])->first();

    if($status == 'partial')
      $msg = 'Survey Marked As '.trans('messages.assignment_status.rejected');
    elseif($status == 'approved')
      $msg = 'Survey is Approved';
    
    $fieldrep_id = $Assignment->fieldrep_id;
    $fieldrep = FieldRep::find($fieldrep_id);
    $user_id = $fieldrep->user_id;
    $user= User::where('id', '=', $user_id)->first();
    $details['fieldrep_email'] = $user->email;
    $details['client_name'] = Auth::user()->UserDetails->name;
    $round_id = $Assignment->round_id;
    $round = Round::find($round_id);
    $details['round_name'] = $round->round_name;
    $project_id = $round->project_id;
    $project = Project::find($project_id);
    $details['project_name'] =$project->project_name;
    $assignment = Assignment::find($survey->assignment_id);
    $details['assignment_code']= $assignment->sites->site_code;
    $details['site'] = $Assignment->getAssignmentLocation();
    if($survey && $Assignment && $status == 'partial'){
      $data = array(
        'details'=>$details,
        'fieldrep'=>$fieldrep
        );
      Emailer::SendEmail('admin.survey_partial',$data);
    }
    if($survey && $Assignment && $status == 'approved'){
      $data = array(
        'details'=>$details,
        'fieldrep'=>$fieldrep
        );
      Emailer::SendEmail('admin.survey_approved',$data);
    }
    return response()->json(array(
      "status" => "success",
      "message"=> $msg,
      ));

  }

  public function getSurveyData(){
    // $survey_datas = DB::table('surveys as s')
    // ->select(['id','keypairs'])->first();

    $survey_datas = surveys::where('status', '2')->get();
    $table;
    $columns[] = "Code";
    $setColumn = false;

    foreach($survey_datas as $key => $survey_data){
      $table[$key]['id'] =  $survey_data->id;
      $datas = unserialize($survey_data->keypairs);
        //dd($datas);
      foreach ($datas as $d_key => $d_value) {
        foreach ($d_value as $col_name => $col_val)
        {
          // $col_name = preg_replace("(^name_[0-9]{1,2}_+)", "", $col_name);
          // $col_name = trim(preg_replace("(\t+)", "", $col_name));
          $table[$key][$col_name] = $col_val;

          preg_match("(^name_[0-9]{1,2}_+)", $col_name, $question_name);
          if(!empty($question_name)){

            $question_name = $question_name[0];
                    //echo $question_name."<br>";
                    //echo trim(substr($string,strlen($question_name)));
            $col_name = "Q.".filter_var($question_name, FILTER_SANITIZE_NUMBER_INT);                    
          }else{
            $col_name = preg_replace("(^name_[0-9]{1,2}_+)", "", $col_name);
            $col_name = trim(preg_replace("(\t+)", "", $col_name));
          }

          if($setColumn == false){
            $columns[] = $col_name;
          }
        }
      }
      $setColumn = true;
    }
    unset($columns[1]);
    unset($table['template_name_']);
    array_unshift($table,$columns); //prepend column array to table array

    $table = Collection::make($table);

    $datatables = Datatables::of($table);

    return $datatables->make();

  }

  public function surveyData(){
    return view('admin.surveys.survey_data');
  }

  // public function exportData(){

  //   $survey_datas = surveys::where('status', '2')->get();

  //   $table;
  //   $columns[] = "Code";
  //   $setColumn = false;

  //   foreach($survey_datas as $key => $survey_data){
  //     $table[$key]['SurveyNO'] =  $survey_data->id;
  //     $datas = unserialize($survey_data->keypairs);

  //     foreach ($datas as $d_key => $d_value) {
  //       if($d_key == 0){
  //         continue;
  //       }
  //       // foreach ($d_value as $col_name => $col_val)
  //       // {
  //       $col_name = $d_value['que'];
  //       $col_val = $d_value['ans'];
  //       // if($d_value['type'] == 'file'){
  //       //     if($col_val  != ''){
  //       //         $col_val = AppHelper::APP_URL.AppHelper::SURVEY_UPLOAD.$survey_data->id."/".$col_val;
  //       //         //http://wts/kalan/public/assets/images/survey/2/
  //       //     }
  //       // }
  //       // $col_name = preg_replace("(^name_[0-9]{1,2}_+)", "", $col_name);
  //       // $col_name = trim(preg_replace("(\t+)", "", $col_name));

  //       //$table[$key][$col_name] = $col_val; //original question

  //       preg_match("(^name_[0-9]{1,2}_+)", $col_name, $question_name);
  //       if(!empty($question_name)){
  //         $question_name = $question_name[0];
  //         $col_name = "Q.".filter_var($question_name, FILTER_SANITIZE_NUMBER_INT);                    
  //       }else{
  //         $col_name = preg_replace("(^name_[0-9]{1,2}_+)", "", $col_name);
  //         $col_name = trim(preg_replace("(\t+)", "", $col_name));
  //       }

  //       if($setColumn == false){
  //         $columns[] = $col_name;
  //       }
  //       $table[$key][$col_name] = $col_val; // question number
  //       // } // for_each d_value
  //     }
  //     $setColumn = true;
  //   }
  //     // foreach($table as $tbl => $tbl_val){
  //     //     unset($table[$tbl]['template_name_']);
  //     // }
  //     //array_unshift($table,$columns); //prepend column array to table array
  //   unset($columns[1]);
  //   unset($table['template_name_']);

  //   $table = Collection::make($table);
  //   $datatables = Datatables::of($table);

  //     //$export=surveys::all();
  //   Excel::create('Export Data',function($excel) use ($table){
  //     $excel->sheet('Sheet 1',function($sheet) use ($table){
  //     //$sheet->freezeFirstColumn();
  //       $sheet->freezeFirstRowAndColumn();
  //       $sheet->fromArray($table);
  //     // Freeze the first row and column
  //     });
  //   })->export('csv');
  // }

  public function replaceKeyPairs(Request $req){
    $ss = surveys::get(['id','keypairs']);
    foreach ($ss as $key => $s) {

      $keyp = unserialize($s->keypairs);

      foreach( $keyp as $k => $v)
      {
        if($v['type'] == 'file'){
          if($v['ans'] != ""){
            if(config('app.url') == 'http://www.alpharepservice.com/'){

            }

            if(str_contains($v['ans'], 'http://www.alpharepservice.com/')){
              $keyp[$k]['ans'] = str_replace('http://www.alpharepservice.com/', config('app.url'), $v['ans']);
            }
            if(str_contains($v['ans'], 'https://www.alpharepservice.com/')){
              $keyp[$k]['ans'] = str_replace('https://www.alpharepservice.com/', config('app.url'), $v['ans']);
            }
            $s->update(['keypairs' => serialize($keyp)]);
          }
        }
      }
    }
    //dd($ss);
  }
}


