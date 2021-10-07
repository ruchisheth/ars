<?php

namespace App\Http\Controllers\FieldRep;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\AppHelper;
use Illuminate\Database\Eloquent\Collection;
use Html;
use View;
use File;
use App\Round,
App\Assignment,
App\AssignmentsOffer,
App\FieldRep,
App\Project,
App\surveys_template,
App\surveys,
App\Instruction,
App\Setting,
Auth,
DB,
Datatables,
Crypt;

class AssignmentController extends Controller
{
	protected $rep_id;

	protected $classification = ['IC' => 1, 'employee' => 2];

	// public function __construct(){
		//parent::__construct();
		// $rep = FieldRep::where(['user_id' => Auth::id()])->first();
		// $this->rep_id = $rep->id;	
	// }

	public function index(){

		return view('fieldrep.assignments');
	}

	public function showOffers()
	{
		$fieldrep = FieldRep::find(Auth::user()->UserDetails->id);		
		$offers = $fieldrep->offers->where('is_accepted', NULL);

		$offers = $offers->filter(function ($offers) {
			return ($offers->assignments->rounds->status == 1 && $offers->assignments->rounds->projects->status == 1);
		});

		$offer_count = $offers->groupBy('assignment_id')->count();
		$data = [
		'offer_count'   =>  $offer_count,
		];
		return view('fieldrep.offers',$data);
	}

	public function getHistory(){

		return view('fieldrep.history');
	}

	public function showEvents(){

		return view('fieldrep.calendar');
	}

	public function responseOffer(Request $request){
		$offers = $request->get('offer');
		$is_accepted = $request->get('is_accepted');	
		foreach($offers as $offer => $response){
			$offerObj = AssignmentsOffer::find($offer);			
			
			$update['is_accepted'] = $is_accepted;
			if($is_accepted == 1){
				AssignmentsOffer::where('assignment_id', $offerObj->assignment_id)
				->where('fieldrep_id', $offerObj->fieldrep_id)
				->where('is_accepted', null)
				->update($update);

				$assignment = $offerObj->assignments;
				//app('App\Http\Controllers\Admin\AssignmentController')->scheduelFieldrep($assignment, $this->rep_id);
				app('App\Http\Controllers\Admin\AssignmentController')->scheduelFieldrep($assignment, Auth::user()->UserDetails->id);
			}
			else if($is_accepted == 0){
				$update['reject_reason'] = $request->get('reject_reason');
				if($request->get('reject_reason') == 5){
					$update['other_reason'] = $request->get('other_reason');
				}
				AssignmentsOffer::where('assignment_id', $offerObj->assignment_id)
				->where('fieldrep_id', $offerObj->fieldrep_id)
				->where('is_accepted', null)
				->update($update);
			}
		}
		return response()->json(array(
			"status" => "success",
			"message"=>"Successful!",
			));
	}

	public function getDetails(Request $request){
		$assignment = Assignment::find($request->input('assignment_id'));
		$details = $assignment->getAssignmentDetail($assignment);
		$details['start_date'] = date_formats($details['start_date'],AppHelper::DATE_DISPLAY_FORMAT);
		$details['deadline_date'] = date_formats($details['deadline_date'],AppHelper::DATE_DISPLAY_FORMAT);
		$date  = date_formats($details['schedule_date'],AppHelper::DATE_DISPLAY_FORMAT);		
		$time = date_formats($details['schedule_date'],AppHelper::TIME_DISPLAY_FORMAT);
		$details['schedule_date'] = $date." ".$time;	

		return response()->json(array(
			"status" => "success",
			"details"=>$details,
			));
	}

	public function getEvents(Request $request){
		//$assignments = Assignment::where(['fieldrep_id'=>$this->rep_id])->whereIn('status',[1,2,3,4])->get();
		//$assignments = Assignment::where(['fieldrep_id'=>$this->rep_id])->get();
		$assignments = Assignment::where(['fieldrep_id'=>Auth::user()->UserDetails->id])->get();
		$FilteredArray = array();
		foreach($assignments as $assignment){
			$title = '';
			$event_color = '';

			$assginment_code = format_code($assignment->id);
			$start_date = date_formats($assignment->getAssignmentStartDate(),AppHelper::DATE_DISPLAY_FORMAT);
			$deadline_date = date_formats($assignment->getAssignmentEndDate(),AppHelper::DATE_DISPLAY_FORMAT);
			$title .= 'Assignment: '.format_code($assignment->id)."\n";
			$title .= 'Start Date: '.$start_date."\n";
			$title .= 'Deadline Date: '.$deadline_date."\n";

			$schedule_date = date_formats($assignment->getAssignmentScheduleDate(),AppHelper::DATE_DISPLAY_FORMAT);
			$schedule_time = null;
			if($assignment->getAssignmentStartTime() != null){
				$schedule_time = date_formats($assignment->getAssignmentStartTime(),AppHelper::TIME_DISPLAY_FORMAT);
			}

			$schedule_date = $schedule_date." ".$schedule_time;

			$start_date = $assignment->getAssignmentScheduleDateTime();

			if($assignment->is_approved){
				$event_color = '#00A65A';
			}elseif($assignment->is_partial){
				$event_color = '#f39c12';
			}elseif($assignment->is_reported){
				$event_color = '#555299';
			}elseif($assignment->is_scheduled){
				$event_color = '#3c8dbc';
			}

			// if($assignment->status == 1)
			// 	$event_color = '#3c8dbc';
			// elseif($assignment->status == 2)
			// 	$event_color = '#555299';
			// elseif($assignment->status == 3)
			// 	$event_color = '#f39c12';
			// elseif($assignment->status == 4)
			// 	$event_color = '#00A65A';	
			
			$FilteredArray[] = [
			'title'=>	$title,
			'start'=>	$schedule_date,
			'color'=>	$event_color,
			'url'	=> $assignment->id,
			];
		}
		return response()->json($FilteredArray);
	}

	public function getAssignments(Request $request){
		$rep_id = Auth::user()->UserDetails->id;
		$status = $request->input('status');
		$project_status = $request->input('project_status');
		$round_status = $request->input('round_status');

		/*api*/
		$client = new \GuzzleHttp\Client();

		$setting = Setting::where(['user_id'	=>	Auth::id()])->first();
		$api_key = $setting != NULL ? $setting->syi_api_key : '';

		$assignments = DB::table('assignments as a')
		->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
		->leftJoin('surveys as su', 'su.assignment_id', '=', 'a.id')
		->leftJoin('projects as p', 'r.project_id', '=', 'p.id')
		->leftJoin('sites as s', 'a.site_id', '=', 's.id')
		->leftJoin('fieldreps as f', 'f.id', '=', 'a.fieldrep_id')
		->leftJoin('chains as ch','ch.id','=','p.chain_id')
		->leftJoin('clients as c','c.id','=','ch.client_id')
		->when($status === 'pending', function ($query) use ($status) {
			return $query->where('a.is_scheduled', '=', true)->where('a.is_reported', '=', false);
		})
		->when($project_status, function ($query) use ($project_status) {
			return $query->where('p.status', $project_status);
		})
		->when($round_status, function ($query) use ($round_status) {
			return $query->where('r.status', $round_status);
		})
		->when($rep_id, function ($query) use ($rep_id) {
			return $query->where('a.fieldrep_id', $rep_id);
		})
		->select([
			'a.id',
			'a.instruction_id',
			'a.deadline_date',
			'a.is_scheduled',
			'a.is_reported',
			'a.is_partial',
			'a.is_offered',
			'a.is_approved',
			'a.approved_at',
			'c.client_logo',
			'p.project_name',
			'r.round_name',
			's.site_code',
			's.site_name',
			's.street',
			's.city',
			's.state',
			's.zipcode',
			DB::raw("CONCAT(IFNULL( DATE_FORMAT(a.schedule_date,'%d %b %Y'), DATE_FORMAT(r.schedule_date,'%d %b %Y')), ' ' , IFNULL(TIME_FORMAT(a.start_time, '%h:%i %p'), TIME_FORMAT(r.start_time,'%h:%i %p'))) as assignment_scheduled"),
			DB::raw("CONCAT(IFNULL( DATE_FORMAT(a.deadline_date,'%d %b %Y'), DATE_FORMAT(r.deadline_date,'%d %b %Y')), ' ' , IFNULL(TIME_FORMAT(a.deadline_time, '%h:%i %p'), TIME_FORMAT(r.deadline_time,'%h:%i %p'))) as assignment_end"),
			//DB::raw("CONCAT(DATE_FORMAT(r.deadline_date,'%d %b %Y'), ' ' , TIME_FORMAT(r.deadline_time,'%h:%i %p')) as assignment_end"),
			'su.id as survey_id',
			'su.service_code'
			]);


		if($request->columns[$request->order[0]['column']]['name'] == 's.site_code'){
			$assignments->orderBy(DB::raw('lpad(trim(site_code), 10, 0)'), $request->order[0]['dir']);							
		}
		if($request->columns[$request->order[0]['column']]['name'] == 's.city'){
			$assignments->orderBy(DB::raw("CONCAT(s.site_name,', ',s.street,',',s.city,',',s.state,' ',s.zipcode)"), $request->order[0]['dir']);
		}
		if($request->columns[$request->order[0]['column']]['name'] == 'assignment_scheduled'){
			$assignments->orderBy(DB::raw("str_to_date(assignment_scheduled,'%d/%m/%Y %H:%i')"), $request->order[0]['dir']);
		}
		if($request->columns[$request->order[0]['column']]['name'] == 'assignment_end'){			
			$assignments->orderBy(DB::raw("str_to_date(assignment_end,'%d/%m/%Y %H:%i')"), $request->order[0]['dir']);
		}

		$datatables = Datatables::of($assignments)
		->addColumn('survey', function ($assignments) use($rep_id,	$status) {
			$assignment = Assignment::find($assignments->id);
			$assignment_instruction = Instruction::find($assignment->round_id);
			if($assignment->isSurveyAvailable() || ($assignments->is_partial || $assignments->is_reported || $assignments->is_approved))
			{
				$survey =  '<a target="_blank" href='.url("/survey/").'/'.Crypt::encrypt($assignments->survey_id).'/'.base64_encode(Auth::user()->client_code).' data-fieldrep-id="'.$assignments->survey_id.'">Survey</a>';
				$partial = '';
				if($assignments->is_partial){
					$partial = '<button class="btn btn-box-tool status-partial" type="button" name="partial" title="'.trans('messages.assignment_status.rejected').'"><span class="status-partial"><span class="fa fa-exclamation-triangle"></span></span></button>';
				}

				if(!$assignment->instructions->isEmpty()){
					$instruction = '<button class="btn btn-box-tool" type="button" name="instruction" onClick ="setInstruction(this,event,\'schedule\')" data-assignment-id="'.$assignments->id.'" title="Instruction"><span class="text-primary"><span class="fa fa-sticky-note"></span></span></button>';
					return $survey.$partial.$instruction;
				}
				return $survey.$partial;
			}
			else{
				if($assignment->isSurveyDeadlinePast()){
					if($status !== '' && $status == 'pending'){
						return '<span class="label label-danger">'.trans('messages.assignment_status.late').'</span>';
					}
					return '-';
				}else{
					return "<small>".trans('messages.survey_will_be_available_soon')."<small>";
				}
			}
		})
		->editColumn('site_code', function ($assignments) {
			$html  = '';
			$html .= '<a href="javascript:void(0)" onclick="SetAssignmentDetails(this,event)" data-id='.$assignments->id.'>'.$assignments->site_code.'</a>';
			return $html;
		})
		->editColumn('client_logo', function ($assignments) {
			$logo = AppHelper::getClientLogoImage($assignments->client_logo);
			return $logo;
		})
		->editColumn('project_name', function ($assignments) use($rep_id) {
			return $assignments->project_name;
		})
		->editColumn('round_name', function ($assignments) use($rep_id) {
			return $assignments->round_name;
		})
		->editColumn('city', function ($assignments) {
			return $assignments->site_name.',  '.format_location($assignments->city,$assignments->state,$assignments->zipcode);
		})
		// ->editColumn('deadline_date', function ($assignments) use($request) {
		// 	$assignment = Assignment::find($assignments->id);
		// 	$show_date = $date_time = date_formats($assignment->getAssignmentEndDate(),AppHelper::DATE_DISPLAY_FORMAT);
		// 	return $show_date;
		// })
		->editColumn('assignment_end', function ($assignments) use($request){
			$assignment = Assignment::find($assignments->id);
			$date = $assignment->getAssignmentEndDate();
			$time = $assignment->getAssignmentDeadlineTime();
			return $date.' '.$time;
		})
		->editColumn('approved_at', function ($assignments) use($request){
			if($assignments->approved_at  != NULL){
				$assignment = Assignment::find($assignments->id);
				return $assignment->approved_at;
				//return date_formats($assignments->approved_at, AppHelper::DATE_DISPLAY_FORMAT);
			}
			return NULL;
		})
		->editColumn('assignment_scheduled', function ($assignments) use($request){
			$html = '';
			$assignment = Assignment::find($assignments->id);

			$schedule_date   = $assignment->getAssignmentScheduleDateTime();

			if($assignment->rounds->projects->can_schedule == 1)
			{
				/* check if Current date is smaller than Assignment End Date */					
				$current_date = date_formats(\Carbon::now(),AppHelper::DATE_SAVE_FORMAT);
				$end_date 		= date_formats($assignment->getAssignmentEndDate(),AppHelper::DATE_SAVE_FORMAT);
				if($current_date < ($end_date)){
					$start_date = date_formats($assignment->getAssignmentStartDate(),AppHelper::DATE_DISPLAY_FORMAT);
					$end_date 	= date_formats($end_date,AppHelper::DATE_DISPLAY_FORMAT);
					$html .= '<a href="javascript:void(0)" class="text-primary" name="schedule_assignment"><i class="fa fa-edit"></i></a>';
					$html .=	'<div class="input-group hide" id="demo">
					<input type="text" data-id="'.$assignments->id.'" data-mindate="'.$start_date.'" data-maxdate="'.$end_date.'" value="'.$schedule_date.'" class="form-control input-sm datepicker">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div></div>';
				}
			}	
			return $schedule_date.' '.$html;
		})
		->editColumn('status', function ($assignments) {
			$assignment = Assignment::find($assignments->id);
			$status = $assignment->getAssignmentStatus();

			if($assignments->instruction_id != null){
				$html = '<button class="btn btn-box-tool" type="button" name="instruction" data-toggle="modal" onClick ="setInstruction(this,event,\'schedule\')" data-target="#instruction_modal" data-instruction-id="'.$assignments->instruction_id.'" title="instruction"><span class="text-primary"><span class="fa fa-sticky-note"></span></span></button>';
				return $status.$html;
			}else{
				return $status;
			}
		})
		->addColumn('action',function($assignments) use($client, $api_key) {
			if($assignments->is_reported || $assignments->is_approved || $assignments->is_partial){
				if($api_key != NULL){
					return "";
					$res = $client->get(AppHelper::SYI_URL.'api/is-invoiced?api_token='.$api_key.'&assignment_id='.$assignments->id.'&service_code='.$assignments->service_code);

					$stream = $res->getBody();
					$data = json_decode($stream->getContents()); 
					if($data->is_invoiced){
						return '<span class="text-success">Invoiced</span>';
					} else{
						$url = ['assignment_id' => base64_encode($assignments->id), 'service_code' => base64_encode($assignments->service_code)];
						if (Auth::user()->UserDetails->classification == $this->classification['IC']){
							return '<a class="" target="_blank" href="http://www.submityourinvoice.com/beta/api/v1/invoices/create?'.http_build_query($url).'">Submit Invoice</a>';
						}
						return '<span class="btn-box-tool"><i class="fa fa-times text-danger"></i></span>';
					}
				}
			}
			return '-';
		});

		$keyword = $request->get('search')['value'];

		$datatables->filterColumn('s.city', 'whereRaw', "CONCAT(s.site_name,', ',s.street,',',s.city,',',s.state,' ',s.zipcode) like ? ", ["%$keyword%"]);

		$datatables->filterColumn('assignment_scheduled', 'whereRaw', "CONCAT(IFNULL( DATE_FORMAT(a.schedule_date,'%d %b %Y'), DATE_FORMAT(r.schedule_date,'%d %b %Y')), ' ' , IFNULL(a.start_time, r.start_time)) like ? ", ["%$keyword%"]);

		$datatables->filterColumn('assignment_end', 'whereRaw', "CONCAT(DATE_FORMAT(r.deadline_date,'%d %b %Y'), ' ' , TIME_FORMAT(r.deadline_time,'%h:%i %p')) like ? ", ["%$keyword%"]);

		return $datatables->make(true);
	}

	public function getOffers(Request $request){	
		$rep_id = Auth::user()->UserDetails->id;
		$project_status = $request->input('project_status');
		$round_status = $request->input('round_status');
		$offer_status = false;
		if($request->has('offer_status')){
			$offer_status = $request->input('offer_status');
		}

		$offers = DB::table('assignments_offers as ao')
		->leftJoin('assignments as a', 'ao.assignment_id', '=', 'a.id')
		->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
		->leftJoin('projects as p', 'r.project_id', '=', 'p.id')
		->leftJoin('sites as s', 'a.site_id', '=', 's.id')
		->leftJoin('fieldreps as f', 'f.id', '=', 'a.fieldrep_id')
		->leftJoin('chains as ch','ch.id','=','p.chain_id')
		->leftJoin('clients as c','c.id','=','ch.client_id')
		//->leftJoin('instructions as ai','ai.id','=','a.instruction_id')
		->leftJoin('assignments_instructions as ai','ai.assignment_id','=','a.id')
		->leftJoin('instructions as i','ai.instruction_id','=','i.id')
		->where(['ao.fieldrep_id' => $rep_id])
		->when($offer_status === 'pending', function ($query) use ($offer_status) {
				//return $query->where('ao.is_accepted', null);
			return $query->where('ao.is_accepted', null)->where('a.is_scheduled', false);
		})
		->when($offer_status === 'all', function ($query) use ($offer_status) {
			return $query->where(function ($query) {
				$query->whereIn('ao.is_accepted',[0,1])
				->orWhere(function ($query) {
					$query->where('ao.is_accepted', '=', null)
					->where('a.is_scheduled', '=', false);
				});
			});
		})
		->when($project_status, function ($query) use ($project_status) {
			return $query->where('p.status', $project_status);
		})
		->when($round_status, function ($query) use ($round_status) {
			return $query->where('r.status', $round_status);
		})
		->groupBy('ao.is_accepted','ao.assignment_id')
		->select([
			'ao.id',
			'a.id as assignment_id',
			'a.instruction_id',
			'i.offer_instruction',
			'i.offer_attachment',
			'p.project_name',
			'r.round_name',
			's.site_name',
			's.city',
			's.state',
			's.zipcode',
			DB::raw("CONCAT(IFNULL( DATE_FORMAT(a.schedule_date,'%e/%c/%Y'), DATE_FORMAT(r.schedule_date,'%e/%c/%Y')), ' ' , IFNULL(a.start_time, r.start_time)) as assignment_scheduled"),
			DB::raw("CONCAT(IFNULL( DATE_FORMAT(a.schedule_date,'%d %b %Y'), DATE_FORMAT(r.schedule_date,'%d %b %Y')), ' ' , IFNULL(a.start_time, r.start_time)) as round_starts"),
			'ao.is_accepted',
			'is_scheduled'
			]);

		$datatables = Datatables::of($offers)
		->editColumn('city', function ($offers) {
			return $offers->site_name.', '.format_location($offers->city,$offers->state,$offers->zipcode);
		})
		->addColumn('checkbox', function ($offers) {
			$html = '';
			if($offers->is_accepted === null ){
				if($offers->is_scheduled == false){
					$html .= "<input type='checkbox' value='1' class='grp-checkbox minimal' data-id='".$offers->id."' name='offer[".$offers->id."]'>";
				}
				else{
					$html .= "Alreday Scheduled";				
				}
			}
			return $html;
		})
		->addColumn('round_starts', function ($offers) use($request){
			return  $offers->round_starts;
		})
		->editColumn('assignment_scheduled', function ($assignments) use($request){
			$assignment = Assignment::find($assignments->assignment_id);
			
			return $assignment->getAssignmentScheduleDateTime();
		})
		->editColumn('is_accepted', function ($offers) {
			$status = "";
			if($offers->is_accepted == "0"){
				$status = '<span class="label label-danger">Rejected</span>';
			}else if($offers->is_accepted == "1"){
				$status = '<span class="label label-success">Accepted</span>';
			}else if($offers->is_accepted === NULL){
				$status = '<span class="label label-default">Pending</span>';
			}
			if($offers->offer_instruction != null || $offers->offer_attachment != null){				
				$html = '<button class="btn btn-box-tool" type="button" name="instruction" onClick ="setInstruction(this,event,\'offer\')"    data-assignment-id="'.$offers->assignment_id.'"  data-instruction-id="'.$offers->instruction_id.'" title="Instruction"><span class="text-primary"><span class="fa fa-sticky-note"></span></span></button>';				
				return $status.$html;
			}
			else{
				return $status;				
			}
		});

		if ($keyword = $request->get('search')['value']) {

			$datatables->filterColumn('assignment_scheduled', 'whereRaw', "CONCAT(IFNULL( DATE_FORMAT(a.schedule_date,'%d %b %Y'), DATE_FORMAT(r.schedule_date,'%d %b %Y')), ' ' , IFNULL(a.start_time, r.start_time)) like ? ", ["%$keyword%"]);

			$datatables->filterColumn('s.city', 'whereRaw', "CONCAT(s.site_name,', ',s.city,',',s.state,' ',s.zipcode) like ? ", ["%$keyword%"]);
		}   

		return $datatables->make(true);
	}

	public function showPreview(){
		return view('fieldrep.preview_attachment');
	}

	public function getInstruction(Request $request){
		$attachments = [];
		$assignment_id = $request->input('assignment_id');
		$inst_type = $request->get('inst_type');
		$assignment = Assignment::find($assignment_id);
		$ass_instructions = $assignment->instructions;  /*->paginate();*/
		$instructions = $assignment->instructions;


		foreach($instructions as $instruct)
		{
			$assign_instruction[$instruct->id]['is_default'] = $instruct->is_default;
			$assign_instruction[$instruct->id]['instruction_id'] = $instruct->id;
			$assign_instruction[$instruct->id]['instruction_name'] = $instruct->instruction_name;
			if($inst_type == 'schedule'){
				$assign_instruction[$instruct->id]['instruction'] = $instruct->instruction;
				if($instruct->attachment){
					$attachments = unserialize($instruct->attachment);
				}

			}else if($inst_type == 'offer'){
				$assign_instruction[$instruct->id]['instruction'] = $instruct->offer_instruction;
				if($instruct->offer_attachment){
					$attachments = unserialize($instruct->offer_attachment);
				}
			}
			if(count($attachments) > 0){
				foreach($attachments as $attach_index => $attachment){
					$filepath = AppHelper::APP_URL.AppHelper::INSTRUCTION_IMG.$attachment;
					$extension = File::extension($filepath);
					$fileType = AppHelper::getFileType($extension);
					$previewIcon = AppHelper::getFilePrevieIcon($extension);
					$assign_instruction[$instruct->id]['attachments'][$attach_index]['file'] = $attachment  ;
					$assign_instruction[$instruct->id]['attachments'][$attach_index]['filepath'] = $filepath;
					$assign_instruction[$instruct->id]['attachments'][$attach_index]['previewIcon'] = $previewIcon;
					$assign_instruction[$instruct->id]['attachments'][$attach_index]['fileType'] = $fileType;
				}
			}
			if($instruct->is_default){
				$assign_instruction = array($instruct->id => $assign_instruction[$instruct->id]) + $assign_instruction;
			}
		}
		return View::make('fieldrep.set_instructions', compact('assign_instruction','instructions'));

		return response()->json(array(
			"status" => "success",
			"instruction" => $instruction,
			));
	}
}
