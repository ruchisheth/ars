<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Eloquent\Collection;
use App\Http\AppHelper;
use Exception;
use Html;
use App\Round;
use App\RoundsAcknowledge;
use	App\Assignment;
use	App\Instruction;
use	App\AssignmentsOffer;
use	App\AssignmentsInstruction;
use	App\FieldRep;
use	App\Project;
use	App\surveys_template;
use	App\surveys;
use	App\Payment;
use	App\Site;
use App\Setting;
use	Auth;
use	DB;
use	Datatables;
use	Crypt;
use	Carbon;
use App\Emailer;
use Validator;

class AssignmentController extends Controller
{
	public function index(Request $oRequest){

		$res = parent::isDataAvailable('assignment','');
		if($res === true){
			$round_list = ['' => 'Select Round'] + Round::orderBy('round_name','desc')->lists('round_name','id')->all();
			$project_list = ['' => 'Select Project'] + Project::orderBy('id','desc')->lists('project_name','id')->all();
			$fieldreps = ['' => 'Select FieldRep'] +FieldRep::select(DB::raw("CONCAT(first_name,' ',last_name) as full_name, id"))->lists('full_name', 'id')->all();
			$payment_types = ['' => 'Select Payment Types'] + DB::table('_list')->where('list_name','=','payment_types')->orderBy('list_order')->lists('item_name','id','list_order');

			$aViewData = [
    			'round_list'    => $round_list,
    			'project_list'  => $project_list,
    			'fieldreps'     => $fieldreps,
    			'payment_types' => $payment_types,
    			'round_filter'  => $oRequest->query('round_id'),
			];
			
			return view('admin.assignments.assignments',$aViewData);
		}
		return $res;
	}

	public function create()
	{
		return view('admin.assignments.create_assignment');
	}

	public function store(Request $request){
		$assignment = Assignment::where(['id'=>$request->input('assignment_id')])->first();
		if($assignment->is_approved){
			return response()->json([ 'message' => "Completed assingment can not be altered!" ], 422);
		}

		$rule = [];
		$message = [];

		$this->validate($request,[
      // 'start_date'          =>  'required',
			//'deadline_date'       =>  'equal_or_after:start_date,'.$assignment->getAssignmentStartDate(),
			'schedule_date'       =>  'equal_or_after:start_date,'.$assignment->getAssignmentStartDate().'|equal_or_before:deadline_date,'.$assignment->getAssignmentEndDate(),
			],[
			//'deadline_date.equal_or_after'      =>  'Deadline date must be greater than Start date.',
			'schedule_date.equal_or_after'      =>  'Schedule Date must be between Start date and Deadline Date.',
			'schedule_date.equal_or_before'     =>  'Schedule Date must be between Start date and Deadline Date.',
			]);

		if($request->has('role')){
			$request->input('schedule_datetime');
			$schedule_datetime =  date_formats($request->input('schedule_datetime'),AppHelper::TIMESTAMP_FORMAT);
			$schedule_date = date_formats($schedule_datetime,AppHelper::DATE_SAVE_FORMAT);
			$start_time = date_formats($schedule_datetime,AppHelper::TIME_SAVE_FORMAT);
			$assignment->update(
				[
				'schedule_date' => $schedule_date,
				'start_time' =>	$start_time ,
				]
				);

			return response()->json(array(
				"status" => "success",
				"message"=>"Schedule Date/Time Changed Successfully",
				));
		}
		
		// if($request->input('schedule_date') != null){
		// 	//$schedule_date =  AppHelper::convertTimeToUTC(date_formats($request->input('schedule_date'),AppHelper::DATE_SAVE_FORMAT),AppHelper::DATE_SAVE_FORMAT,$request);           
		// 	$schedule_date = date_formats($request->input('schedule_date'),AppHelper::DATE_SAVE_FORMAT);
		// }
		// else{
		// 	$schedule_date = null;
		// }

		// if($request->input('start_date')){
		// 	//$start_date =  AppHelper::convertTimeToUTC(date_formats($request->input('start_date'),AppHelper::DATE_SAVE_FORMAT),AppHelper::DATE_SAVE_FORMAT,$request);
		// 	$start_date = date_formats($request->input('start_date'),AppHelper::DATE_SAVE_FORMAT);
		// }
		// else{
		// 	$start_date = null;
		// }	

		// if($request->input('deadline_date')){
		// 	//$deadline_date =  AppHelper::convertTimeToUTC(date_formats($request->input('deadline_date'),AppHelper::DATE_SAVE_FORMAT),AppHelper::DATE_SAVE_FORMAT,$request);
		// 	$deadline_date = date_formats($request->input('deadline_date'),AppHelper::DATE_SAVE_FORMAT);
		// }
		// else{
		// 	$deadline_date = null;
		// }

		// if($request->input('start_time')){
		// 	$start_time =  	format_time($request->input('start_time'),AppHelper::TIME_SAVE_FORMAT);
		// }
		// else{
		// 	$start_time = null;
		// }

		// if($request->input('deadline_time')){
		// 	$deadline_time =  	format_time($request->input('deadline_time'),AppHelper::TIME_SAVE_FORMAT);
		// }
		// else{
		// 	$deadline_time = null;
		// }
		
		$assignment->update(
			[
			'start_time' 		=> 	$request->start_time,
			'deadline_time' => 	$request->deadline_time,			
			'start_date' 		=>	$request->start_date ,
			'deadline_date' => 	$request->deadline_date,
			'schedule_date' => 	$request->schedule_date,
			]
			);

		return response()->json(array(
			"status" => "success",
			"message"=>"Assignment Saved Successfully",
			));
	}

	public function generate(Request $request){
		
		$round_id = $request->input('round_id');
		$round = Round::where(['id'=>$round_id])->first();

		if($request->has('available_store')){
			$stores = array_unique(($request->input('available_store')));


			$instruction = Instruction::where(['round_id'=>$round_id, 'is_default' => true])->first();
			foreach($stores as $store){
			
				$assignment_code = get_unique_num();
				$assignment = Assignment::create(
					[
					'round_id' => $round_id,
					'site_id' => $store,
					'assignment_code' => $assignment_code,
					]);

				if($instruction){
					AssignmentsInstruction::applyDefault($instruction->id,	$assignment->id);
				}

				//Schedule default fieldrep
				$site = Site::find($store);
				if($site->fieldreps != null){
					$fieldrep_id = $site->fieldreps->id;
					$field_rep = Fieldrep::find($fieldrep_id);
					if($field_rep != NULL && $field_rep->initial_status){
						self::scheduelFieldrep($assignment, $fieldrep_id);
					}
				}
			}
		}

		$sites = get_available_sites($round);
		return response()->json(array(
			"status" => "success",
			"message"=>"Assignment Created Successfully",
			"sites" => $sites
			));
	}

	public function scheduelFieldrep($assignment, $fieldrep_id, $schedule_date = NULL, $schedule_start_time = NULL,$notify_via_email=null){
		
		$template_id = $assignment->rounds->template_id;
		$assignment_id = $assignment->id;
		$round_id = $assignment->rounds->id;

		if($template_id == null || $template_id == 0){

			$res['status'] =  false;
			$res['message'] =  'You can not schedule Fieldrep to Assignment unless a survey has been selected.';
			return $res;
		}		
		$assignment->update([
			'fieldrep_id' => $fieldrep_id,
			'is_scheduled' => true
			]);

		if($schedule_date != NULL){
			$assignment->update(['schedule_date' => $schedule_date]);
		}
		if($schedule_start_time != NULL){
			$assignment->update(['start_time' => $schedule_start_time]);
		}
		$template = surveys_template::where(['id'=>$template_id])->first();

		$survey = new surveys;
		$survey->template_id = $template_id;
		$survey->assignment_id =$assignment_id;
		$survey->reference_id = $fieldrep_id;
		$survey->template = $template->template;
		$survey->keypairs = $template->questions_data;
		$survey->save();

		$ackno = RoundsAcknowledge::where(['round_id' => $round_id, 'fieldrep_id' => $fieldrep_id])->first();
		if($ackno == NULL){
			RoundsAcknowledge::create(['round_id' => $round_id, 'fieldrep_id' => $fieldrep_id]);
		}

		$fieldrep = Fieldrep::find($fieldrep_id);
		$setting = Setting::find(1);
		$details['client_name'] = strtolower(Auth::user()->UserDetails->name);
		$details['location'] = $assignment->getAssignmentLocation();
		$details['fieldrep_email']=$fieldrep->users->email;
		$details['site_title'] = 'ARS';
		//$details['site_title'] = strtolower($setting->title);
		$sites = $assignment->sites;
		$details['site_code'] = $sites->site_code;
		if($assignment && $survey && $notify_via_email != null){
			$data = array(
				'assignment'=>$assignment,
				'survey'=>$survey,
				'details'=>$details,
				'fieldrep' => $fieldrep
				);
			Emailer::SendEmail('admin.schedule_fieldrep',$data);
		}
		$res['status'] =  true;
		return $res;
	}

	public function schedule(Request $request){
		$this->validate($request, [
			'schedule_start_time'	=>  'required',
			]);		
		$assignment_id = $request->input('assignment_id');
		$assignment = Assignment::where(['id'=>$assignment_id])->first();
		$fieldrep_id = $request->input('fieldrep_id');

		if($request->input('schedule_date')){          
			$schedule_date = date_formats($request->input('schedule_date'),AppHelper::DATE_SAVE_FORMAT);
		}
		
		if($request->input('schedule_start_time')){
			$schedule_start_time = format_time($request->input('schedule_start_time'),AppHelper::TIME_SAVE_FORMAT);
		}		
		$notify_via_email = $request->input('notify_via_email');		
		$res = self::scheduelFieldrep($assignment, $fieldrep_id, $schedule_date, $schedule_start_time,$notify_via_email);
		if($res['status'] == false){
			return response()->json([ 'message' => $res['message'] ], 422);
		}

		return response()->json([
			"status" => "success",
			"message"=>"Assignment Scheduled Successfully!",
			]);
	}

	public function edit(Request $request,$assignment_id){
		// SELECT 
		// a.id,a.assignment_code,a.start_date,a.start_time,a.estimated_duration,
		// r.id as round_id,r.start_time,r.estimated_duration,r.deadline_date,
		// CONCAT(f.first_name,' ',f.last_name) as fieldrep_name
		// FROM `assignments` as a
		// left join rounds as r on a.round_id = r.id
		// left join fieldreps as f on a.fieldrep_id = f.id
		// WHERE a.id = 1

		$assignment = Assignment::where(['id' => $assignment_id])->with('rounds')->first();	

		$assignments_data = DB::table('assignments as a')
		->where('a.id','=',$assignment_id)
		->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
		->leftJoin('fieldreps as f', 'a.fieldrep_id', '=', 'f.id')
		->leftJoin('sites as s', 'a.site_id', '=', 's.id')
		->select(
			'a.id',
			'a.assignment_code',
			'a.schedule_date',
			'a.start_date',
			'a.deadline_date',
			'a.start_time',
			'a.deadline_time',
			'a.created_at',
			'a.updated_at',
			'r.schedule_date as round_schedule_date',
			'r.start_date as round_start_date',
			'r.deadline_date as round_deadline_date',
			'r.start_time as round_start_time',
			'r.deadline_time as round_deadline_time',
			DB::raw('CONCAT(f.first_name," ",f.last_name) as fieldrep_name'),
			's.site_name'
			)
		->first();


		$assignments_data->updated = date_formats(AppHelper::getLocalTimeZone($assignments_data->updated_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT); 
		$assignments_data->created = date_formats(AppHelper::getLocalTimeZone($assignments_data->created_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT);

		$minDate = date_formats($assignment->getAssignmentStartDate(),AppHelper::DATE_DISPLAY_FORMAT);		
		$maxDate = date_formats($assignment->getAssignmentEndDate(),AppHelper::DATE_DISPLAY_FORMAT);		

		$assignmentArr = [
		'assignment_id'				=>	["type"	=>	"hidden",	'value'	=>	$assignments_data->id],
		'site_name'						=>	["type"	=>	"text",		'value'	=>	$assignments_data->site_name],
		'fieldrep_name'				=>	["type"	=>	"text",		'value'	=>	$assignments_data->fieldrep_name],
		'round_schedule_date'	=>	["type"	=>	"text",		'value'	=>	$assignment->rounds->schedule_date],
		'round_start_date'		=>	["type"	=>	"text",		'value'	=>	$assignment->rounds->start_date],
		'round_deadline_date'	=>	["type"	=>	"text",		'value'	=>	$assignment->rounds->deadline_date],
		'round_start_time'		=>	["type"	=>	"text",		'value'	=>	$assignment->rounds->start_time],
		'round_deadline_time'	=>	["type"	=>	"text",		'value'	=>	$assignment->rounds->deadline_time],
		'schedule_date'				=>	["type"	=>	"text",		'value'	=>	$assignment->schedule_date],
		'start_date'					=>	["type"	=>	"text",		'value'	=>	$assignment->start_date],
		'deadline_date'				=>	["type"	=>	"text",		'value'	=>	$assignment->deadline_date],
		'start_time'					=>	["type"	=>	"text",		'value'	=>	$assignment->start_time],
		'deadline_time'				=>	["type"	=>	"text",		'value'	=>	$assignment->deadline_time],
		'created_at'					=>	["type"	=>	"label",	'value'	=>	$assignments_data->created],
		'updated_at'					=>	["type"	=>	"label",	'value'	=>	$assignments_data->updated],
		'status'							=>	["type"	=>	"label",	'value'	=>	$assignment->getAssignmentStatus()],
		];

		return response()->json(array(
			"status" => "success",
			"inputs"=>		$assignmentArr,
			"minDate"	=>	$minDate,
			"maxDate"	=>	$maxDate,
			));
	}

	/**
    * Remove the specified resource from storage.
    *
    * @param  $request
    * @param  array $request->assignment_id
    * @return \Illuminate\Http\Response
  */
	public function destroy(Request $oRequest){
		$aSites = "";
		if($oRequest->assignment_ids == NULL && $oRequest->round_id != ""){
			try{
				$oRound = Round::where(['id' => $oRequest->round_id])->first();
				$oAssignments = Assignment::where(['round_id' => $oRequest->round_id, 'is_reported' => false, 'is_partial' => false, 'is_approved' => false])->get();

				if($oAssignments->isEmpty()){
					return response()->json([ 'message' => trans('messages.no_pending_scheduled_assignment') ], 422);
				}
				
				foreach($oAssignments as $oAssignment){
					if($oAssignment->is_scheduled){
						$oRequest->merge([ 'assignment_id' => $oAssignment->id]);
						$this->unscheduleRep($oRequest);
					}
					$oAssignment->delete();					
				}

				$aSites = get_available_sites($oRound);

				return response()->json([
					'status'    => true,
					'message'	=>	trans('message.assginment_delete_success'),
					'sites'     => $aSites,
					]);
				
			}catch(Exception $e){
				if($e instanceof \PDOException )
				{
					$error_code = $e->getCode();

					$sMessage = trans('messages.no_pending_scheduled_assignment') ;
					
					return response()->json([ 
						'success'	=> false,
						'message' 	=> $sMessage,
						'data'		=> [], 
					], 422);
				}
			}
		}else{
			foreach ($oRequest->assignment_ids as $nIdAssignment) {
				$oAssignment = Assignment::find($nIdAssignment);
				$oRound = Round::where(['id' => $oAssignment->round_id])->first();

				if($oAssignment->is_reported || $oAssignment->is_partial || $oAssignment->is_completed){
					return response()->json([
						'success' 	=> false,
						'message'	=> trans('messages.reported_assignment_delete_error'),
						'data'		=> [],
					], 422);
				}else{
					try{
						if($oAssignment->is_scheduled){
							$oRequest->merge([ 'assignment_id' => $nIdAssignment]);
							$this->unscheduleRep($oRequest);
						}
						surveys::where(['assignment_id'=> $nIdAssignment ])->delete();
						AssignmentsOffer::where(['assignment_id'=> $nIdAssignment ])->delete();
						$oAssignment->delete();
						$aSites = get_available_sites($oRound);
					}
					catch(Exception $e){
						if($e instanceof \PDOException )
						{
							$error_code = $e->getCode();
							if($error_code == 23000){
								$sMessage = trans('messages.assignment_delete_errors');
								return response()->json([
									'success'	=> false,
									'message' 	=> $sMessage,
									'data'		=> [],
								], 422);
							}
						}
					}
				}
			}
			return response()->json([
				'success'	=> 	true,
				'message'	=>	trans('messages.assignment_delete_success'),
				'sites' 	=> 	$aSites
			]);


		}
	}

	public function scheduleEdit(Request $request){
		$assignment_id = $request->input('assignment_id');

		$fieldrep_id = $request->input('fieldrep_id');

		$assignment = Assignment::find($assignment_id);

		$fieldrep = Fieldrep::find($fieldrep_id);
		$fieldrep_name = $fieldrep->first_name ." ".$fieldrep->last_name;

		$payment_data = Payment::where('assignment_id',$assignment_id)->where('payment_type','121')->first();
		$pay_type = 'h';

		if($payment_data != null){
			$pay_type = $payment_data->pay_type;
			$pay_rate = $payment_data->pay_rate;
		}

		$minDate 							= 	$assignment->getAssignmentStartDate();
		$maxDate 							=		$assignment->getAssignmentEndDate();
		$schedule_date 				= 	$assignment->getassignmentScheduleDate();
		$schedule_start_time 	= 	$assignment->getAssignmentStartTime();


		//Get Assignment Location
		$location = $assignment->sites->site_name." ".format_location($assignment->sites->city,
			$assignment->sites->state,
			$assignment->sites->zipcode
			);

		$scheduleArr = [
		'assignment_id'=>["type"=>"hidden",'value'=>$assignment_id],
		'fieldrep_id'=>["type"=>"hidden",'value'=>$fieldrep_id],
		'schedule_date'=>["type"=>"text",'value'=>$schedule_date],
		'schedule_start_time'=>["type"=>"text",'value'=>$schedule_start_time],
		'pay_rate'=>["type"=>"text",'value'=>@$pay_rate],
		'pay_type'=>["type"=>"select",'value'=>@$pay_type],
		'location'=>["type"=>"label",'value'=>$location],
		'fieldrep_name'=>["type"=>"label",'value'=>$fieldrep_name],
		];

		return response()->json(array(
			"status" => "success",
			"inputs"=>$scheduleArr,
			"minDate"	=>	$minDate,
			"maxDate"	=>	$maxDate,
			));
	}

	public function offer(Request $request){
		$assignment_id = $request->input('assignment_id');
		if($assignment_id == 0){
			return response()->json([ 'message' => "Something went wrong. Please try again" ], 422);
		}
		$fieldrep_id = $request->input('fieldrep_id');
		$offer = AssignmentsOffer::where(['assignment_id' => $assignment_id, 'fieldrep_id' => $fieldrep_id])->first();
		$ao = AssignmentsOffer::create(
			[
			'assignment_id' => $assignment_id,
			'fieldrep_id' => $fieldrep_id,
			]);		
		
		$assignment = $assignment_details = Assignment::find($assignment_id);
		$input['is_offered'] = true;
		$assignment->update($input);
		
		$fieldrep = Fieldrep::find($fieldrep_id);
		// $assignment_details = Assignment::find($assignment_id);
		$sites = $assignment_details->sites;
		$details['client_name'] = Auth::user()->UserDetails->name;
		$details['site_code'] = $sites->site_code;
		$details['location'] = $assignment_details->getAssignmentLocation();
		$details['fieldrep_email']=$fieldrep->users->email;
		if($assignment && $ao){
			$data = array(
				'assignment'=>$assignment_details,
				'offer'=>$offer,
				'details'=>$details,
				'fieldrep' => $fieldrep
				);
			Emailer::SendEmail('admin.offer_fieldrep',$data);
		}
		
		return response()->json(array(
			"status" => "success",
			"message"=>"Offer Sent Successfully!",
			));
	}

	public function unscheduleRep(Request $request){
		$assignment_id = $request->input('assignment_id');
		$assignment = Assignment::where(['id'=>$assignment_id])->first();

		$survey_id = $assignment->rounds->template_id;
		$rep_id = $assignment->fieldrep_id;

		surveys::where(['template_id'=>$survey_id,'reference_id'=>$rep_id, 'assignment_id'=> $assignment_id ])->delete();

		$assignment->update(
			[
			'fieldrep_id' => null,
			'is_scheduled' => false,
			]
			);

		return response()->json(array(
			"status" => "success",
			"message"=>"Fieldrep Unscheduled Successfully!",
			));
	}

	public function getdata(Request $request,$nRoundId = NULL){
		$current_date = Carbon::now(\Session::get('local_tz'))->toDateString();
		$status = $request->input('status');

		$oAssignments = Assignment::from('assignments as a')
		->when($nRoundId != "" || $nRoundId != NULL, function ($query) use ($nRoundId) {
			return $query->where('a.round_id', '=', $nRoundId);
		})
		->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
		->leftJoin('surveys as su', 'a.id', '=', 'su.assignment_id')
		->leftJoin('projects as p', 'r.project_id', '=', 'p.id')
		->leftJoin('sites as s',  'a.site_id', '=', 's.id')
		->leftJoin('fieldreps as f', 'a.fieldrep_id', '=', 'f.id')
		->leftJoin('chains as ch','p.chain_id','=','ch.id')
		->leftJoin('clients as c','ch.client_id','=','c.id')
		->when(is_string($status) && $status != "", function ($query) use ($status, $current_date) {
			if($status == 'late'){
				//$current_date = Carbon::now(\Session::get('local_tz'))->toDateString();
				return $query->where(function ($query) use ($current_date) {
					$query->where(function ($query)  {
						$query->where(DB::raw('IFNULL( DATE_FORMAT(a.deadline_date,"%Y-%c-%e"), DATE_FORMAT(r.deadline_date,"%Y-%c-%e"))'), '<', DB::raw('CURDATE()'));
					})
					->where(function ($query) use ($current_date) {
						$query->where('a.is_scheduled', '=', true)
						->where('a.is_reported', '=', false)
						->where('a.is_partial', '=', false);
					});
				});
			}else if($status == 'scheduled'){
				//$current_date = Carbon::now(\Session::get('timezone'))->toDateString();
				return $query->where(function ($query) use ($current_date) {
					$query->where(function ($query)  {
						$query->where(DB::raw('IFNULL( DATE_FORMAT(a.deadline_date,"%Y-%c-%e"), DATE_FORMAT(r.deadline_date,"%Y-%c-%e"))'), '>=', DB::raw('CURDATE()'));
					})
					->where(function ($query) use ($current_date) {						
						$query->where('a.is_scheduled', '=', true)
						->where('a.is_reported', '=', false)
						->where('a.is_partial', '=', false);
					});
				});
			}
			else if($status == 'pending'){
				return $query->leftJoin('assignments_offers as ao', function($join)
				{
					$join->on('a.id', '=', 'ao.assignment_id');
				})
				->where('a.is_scheduled', '=', false)
				->where(DB::raw('(SELECT count(id) as offer_count FROM assignments_offers where assignment_id = a.id and is_accepted is null)'), '<=', '0')
				->groupBy('a.id');
			}else if($status == 'offered'){
				return $query->leftJoin('assignments_offers as ao', function($join)
				{
					$join->on('a.id', '=', 'ao.assignment_id');
				})
				->where(['a.is_scheduled' => false, 'a.is_offered' => true])->where(['ao.is_accepted' => NULL])
				->groupBy('ao.assignment_id');
			}else if($status == 'reported'){
				return $query->where(['a.is_reported' => true, 'is_approved' => false]);
			}else if($status == 'partial'){
				return $query->where('a.is_partial', true);
			}else if($status == 'completed' || $status == 'approved'){
				return $query->where('a.is_approved', true);
			}
		})
		->select([
			'a.id',
			'a.fieldrep_id',
			'a.deadline_date',
			'a.is_scheduled',
			'a.is_reported',
			'a.is_partial',
			'a.is_offered',
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
			DB::raw("DATE_FORMAT(CONVERT_TZ(TIMESTAMP(IFNULL( a.schedule_date, r.schedule_date), IFNULL(a.start_time, r.start_time)), 'GMT', '".AppHelper::getSelectedTimeZone()."'),'%d %b %Y %h:%i %p') as assignment_scheduled"),
			DB::raw("DATE_FORMAT(CONVERT_TZ(TIMESTAMP(IFNULL( a.deadline_date, r.deadline_date), IFNULL(a.deadline_time, r.deadline_time)), 'GMT', '".AppHelper::getSelectedTimeZone()."'),'%d %b %Y %h:%i %p') as assignment_end"),
			DB::raw('CONCAT(f.first_name," ",f.last_name) as schedule_to'),
			DB::raw('(select COUNT(id) as offer_count from assignments_offers where assignment_id = a.id and is_accepted is null group by assignment_id) as offer_count'),
			]);



		if($request->columns[$request->order[0]['column']]['name'] == 's.site_code'){
			$oAssignments->orderBy(DB::raw('lpad(trim(s.site_code), 10, 0)'), $request->order[0]['dir']);
		}
		if($request->columns[$request->order[0]['column']]['name'] == 's.city'){
			$oAssignments->orderBy(DB::raw("CONCAT(s.site_name,', ',s.city,',',s.state,' ',s.zipcode)"), $request->order[0]['dir']);
		}
		if($request->columns[$request->order[0]['column']]['name'] == 'assignment_scheduled'){
			$oAssignments->orderBy(DB::raw("str_to_date(assignment_scheduled,'%d/%m/%Y %H:%i')"), $request->order[0]['dir']);
		}
		if($request->columns[$request->order[0]['column']]['name'] == 'assignment_end'){
			$oAssignments->orderBy(DB::raw("str_to_date(assignment_end,'%d/%m/%Y %H:%i')"), $request->order[0]['dir']);
		}
		if($request->columns[$request->order[0]['column']]['name'] == 'schedule_to'){
			$oAssignments->orderBy(DB::raw("CONCAT(f.first_name,' ',f.last_name)"), $request->order[0]['dir']);
		}

		$datatables = Datatables::of($oAssignments)
		->editColumn('client_logo', function ($oAssignments) {
			$logo = AppHelper::getClientLogoImage($oAssignments->client_logo);
			return $logo;
		})
		->editColumn('site_code', function ($oAssignments) {
			$html = '';
			$html .= '<a href="javascript::void(0)" onclick="SetAssignmentEdit(this,event)" data-id='.$oAssignments->id.'>'.$oAssignments->site_code.'</a>';
			return $html;
		})	
		->editColumn('project_name', function ($oAssignments){
		    $html = '';
			$html .= '<a href='.url("/projects-edit/").'/'.$oAssignments->project_id.'>'. $oAssignments->project_name.'</a>';
			return $html;
		})
		->editColumn('round_name', function ($oAssignments){
			return '<a class="" href='.url("/rounds-edit/").'/'.$oAssignments->round_id.'>'.$oAssignments->round_name.'</a>';	
		})
		->editColumn('city', function ($oAssignments) {
			return $oAssignments->site_name.', '.format_location($oAssignments->city,$oAssignments->state,$oAssignments->zipcode);
		})
		// ->editColumn('assignment_scheduled_date', function ($oAssignments) {
		// 	return $oAssignments->assignment_scheduled_date;
		// })
		->addColumn('bulk_delete', function ($oAssignments) {
			$html = '';
			if($oAssignments->is_reported || $oAssignments->is_partial || $oAssignments->is_approved){
			}else{
				$html .= "<input type='checkbox'  class='minimal entity_chkbox' value='".$oAssignments->id."'  />";
			}	
			return $html;
		})
		->addColumn('status', function ($oAssignments) {
// 			$assignment = Assignment::find($oAssignments->id);
			$status = $oAssignments->getAssignmentStatus();
			return $status;
		})
// 		->editColumn('assignment_scheduled', function ($oAssignments) {
// 			$assingment = Assignment::find($oAssignments->id);
// 			return $assingment->getassignmentScheduleDateTime();
// 		})
// 		->editColumn('assignment_end', function ($oAssignments) {
// 			$assignment = Assignment::find($oAssignments->id);
// 			$date =  $assignment->getassignmentEndDate();
// 			$time =  $assignment->getassignmentDeadlineTime();
// 			return $date.' '.$time;
// 		})
		->editColumn('schedule_to', function ($oAssignments) {
// 			$assignment = Assignment::find($oAssignments->id);
			$is_deadline_past = $oAssignments->isSurveyDeadlinePast();
			if($oAssignments->fieldrep_id == null || $oAssignments->fieldrep_id == 0){
				if(!$is_deadline_past)
				{
					return '<a href="#" data-toggle="modal" data-round-id='.$oAssignments->round_id.' data-id='.$oAssignments->id.' data-target="#fieldrep_schedule_modal">Schedule</a>';
				}else{
					return '<a href="javascript:void(0)" id="ded_past" data-round-id='.$oAssignments->round_id.'>Schedule</a>';
					return '<small>Change Assignment deadline date to Schedule Fieldrep</small>';
				}
			}else{
				$html = $oAssignments->schedule_to;

				if((!$oAssignments->is_reported && !$oAssignments->is_partial && !$oAssignments->is_approved) && (!$oAssignments->isSurveyDeadlinePast()))
				{
					$html .= '<button title="Unschedule" value="unschedule" data-id="'.$oAssignments->id.'" onClick="unscheduleRep(this,event)" name="unschedule_rep" type="submit" class="btn btn-box-tool"><i class="fa fa-times-circle"></i></button>';
				}
				return $html;
			}	
		})
		// ->addColumn('offer_count', function ($oAssignments) {
  //     $assignment = Assignment::find($oAssignments->id);
  //     return $assignment->offers->where('is_accepted', NULL)->count();
  //     // $status = $assignment->getAssignmentStatus();
  //     // return $status;
  //   })
		->addColumn('action', function ($oAssignments) {
			$html = '';
			if($oAssignments->is_reported || $oAssignments->is_partial || $oAssignments->is_approved){

				$html .= '<a target="_balnk" title="view survey" href='.url("/review-survey/").'/'.$oAssignments->survey_id.' class="btn btn-box-tool"><i class="fa fa-eye"></i></a>';
				//$html .= '<button class="btn btn-box-tool" disabled type="button" title="delete"><span class="fa fa-trash"></span></button>';
			}else{
				$html .= '<button class="btn btn-box-tool" type="button" name="remove_assignment" data-id="'.$oAssignments->id.'" value="delete" title="delete"><span class="fa fa-trash"></span></button>';
			}
			return $html;
		});

		if ($id = $request->get('round_id')) {
      $datatables->where('a.round_id', '=', "$id"); // additional users.name search
    }

    if ($project_id = $request->get('project_id')) {
    	$datatables->where('r.project_id', '=', "$project_id"); // additional project filter
    }

    $keyword = $request->get('search')['value'];

    if($nRoundId == "" || $nRoundId == NULL){

    	if($keyword != '' || $request->get('columns')['6']['search']['value'] != ""){
    		$keyword = ($request->get('columns')['6']['search']['value'] != '') ? $request->get('columns')['6']['search']['value'] : $keyword ;
    		$datatables->filterColumn('s.city', 'whereRaw', "CONCAT(s.site_name,', ',s.city,',',s.state,' ',s.zipcode) like ? ", ["%$keyword%"]);
    	}

    	if($keyword != '' || $request->get('columns')['7']['search']['value'] != ""){
    		$keyword = ($request->get('columns')['7']['search']['value'] != '') ? $request->get('columns')['7']['search']['value'] : $keyword ;
    // 		$datatables->filterColumn('assignment_scheduled', 'whereRaw', "CONCAT(IFNULL( DATE_FORMAT(a.schedule_date,'%d %b %Y'), DATE_FORMAT(r.schedule_date,'%d %b %Y')), ' ' , IFNULL(TIME_FORMAT(a.start_time, '%h:%i %p'), TIME_FORMAT(r.start_time, '%h:%i %p'))) like ? ", ["%$keyword%"]);
    $datatables->filterColumn('assignment_scheduled', 'whereRaw', "DATE_FORMAT(CONVERT_TZ(TIMESTAMP(IFNULL( a.schedule_date, r.schedule_date), IFNULL(a.start_time, r.start_time)), 'GMT', '".AppHelper::getSelectedTimeZone()."'),'%d %b %Y %h:%i %p') like ? ", ["%$keyword%"]);
    	}

    	if($keyword != '' || $request->get('columns')['8']['search']['value'] != ""){
    		$keyword = ($request->get('columns')['8']['search']['value'] != '') ? $request->get('columns')['8']['search']['value'] : $keyword ;
    		$datatables->filterColumn('assignment_end', 'whereRaw', "DATE_FORMAT(CONVERT_TZ(TIMESTAMP(IFNULL( a.deadline_date, r.deadline_date), IFNULL(a.deadline_time, r.deadline_time)), 'GMT', '".AppHelper::getSelectedTimeZone()."'),'%d %b %Y %h:%i %p') like ? ", ["%$keyword%"]);
    // 		$datatables->filterColumn('assignment_end', 'whereRaw', "CONCAT(DATE_FORMAT(r.deadline_date,'%d %b %Y'), ' ' ,TIME_FORMAT(r.deadline_time,'%h:%i %p')) like ? ", ["%$keyword%"]);
    	}

    	if($keyword != '' || $request->get('columns')['9']['search']['value'] != ""){
    		$keyword = ($request->get('columns')['9']['search']['value'] != '') ? $request->get('columns')['9']['search']['value'] : $keyword ;    	
    		$datatables->filterColumn('schedule_to', 'whereRaw', "CONCAT(f.first_name,' ',f.last_name) like ? ", ["%$keyword%"]);
    	}
    }

    // $datatables->filterColumn('s.city', 'whereRaw', "CONCAT(s.site_name,', ',s.city,',',s.state,' ',s.zipcode) like ? ", ["%$keyword%"]);

    // $datatables->filterColumn('assignment_scheduled', 'whereRaw', "CONCAT(IFNULL( DATE_FORMAT(a.schedule_date,'%d %b %Y'), DATE_FORMAT(r.schedule_date,'%d %b %Y')), ' ' , IFNULL(TIME_FORMAT(a.start_time, '%h:%i %p'), TIME_FORMAT(r.start_time, '%h:%i %p'))) like ? ", ["%$keyword%"]);

    // $datatables->filterColumn('assignment_end', 'whereRaw', "CONCAT(DATE_FORMAT(r.deadline_date,'%d %b %Y'), ' ' ,TIME_FORMAT(r.deadline_time,'%h:%i %p')) like ? ", ["%$keyword%"]);

    // $datatables->filterColumn('schedule_to', 'whereRaw', "CONCAT(f.first_name,' ',f.last_name) like ? ", ["%$keyword%"]);

    return $datatables->make(true);
  }

  public function getAssignmentCounts(Request $request){
  	$project_id = $request->get('project_id');
  	$round_id = $request->get('round_id');
  	$counts = [
  	'pending_count' => $this->getPendingCount($project_id, $round_id),
  	'offered_count' => $this->getOfferedCount($project_id, $round_id),
  	'scheduled_count' => $this->getScheduleCount($project_id, $round_id),
  	'late_count' => $this->getLateCount($project_id, $round_id),
  	'reported_count' => $this->getReportedCount($project_id, $round_id),
  	'partial_count' => $this->getPartialCount($project_id, $round_id),
  	'completed_count' => $this->getCompletedCount($project_id, $round_id),
  	];
  	return response()->json([ 
  		'counts' => $counts,
  		]);
  }  

  public function getPendingCount($project_id = "", $round_id = ""){
  	$pendign_assignments =  DB::table('assignments as a')
  	->when($round_id != "" || $round_id != NULL, function ($query) use ($round_id) {
  		return $query->where('a.round_id', '=', $round_id);
  	})->when($project_id != "" || $project_id != NULL, function ($query) use ($project_id) {
  		return $query->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
  		->leftJoin('projects as p', 'r.project_id', '=', 'p.id')
  		->where('p.id', '=', $project_id );
  	})
  	->leftJoin('assignments_offers as ao', function($join)
  	{
  		$join->on('a.id', '=', 'ao.assignment_id');
  	})
  	//->where(['a.is_scheduled' => false, 'a.is_offered' => true, 'ao.is_accepted' => NULL])
  	->where('a.is_scheduled', '=', false)
  	->where(DB::raw('(SELECT count(id) as offer_count FROM assignments_offers where assignment_id = a.id and is_accepted is null)'), '<=', '0')
  	->groupBy('a.id')
  	->get();

  	return count($pendign_assignments);
  }

  public function getOfferedCount($project_id = "", $round_id = ""){
  	$count =  DB::table('assignments as a')
  	->when($round_id != "" || $round_id != NULL, function ($query) use ($round_id) {
  		return $query->where('a.round_id', '=', $round_id);
  	})->when($project_id != "" || $project_id != NULL, function ($query) use ($project_id) {
  		return $query->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
  		->leftJoin('projects as p', 'r.project_id', '=', 'p.id')
  		->where('p.id', '=', $project_id );
  	})
  	->leftJoin('assignments_offers as ao', function($join)
  	{
  		$join->on('a.id', '=', 'ao.assignment_id');
  	})
  	//->where(['a.is_scheduled' => false, 'a.is_offered' => true, 'ao.is_accepted' => NULL])
  	->where(['a.is_scheduled' => false, 'a.is_offered' => true])
  	->where(DB::raw('(SELECT count(id) as offer_count FROM assignments_offers where assignment_id = a.id and is_accepted is null)'), '>', '0')
  	->groupBy('ao.assignment_id');
  	// ->toSql();
  	// return $count;
  	$count = $count->get();
  	$total = count($count);
  	return $total;
  }

  public function getScheduleCount($project_id = "", $round_id = ""){
  	//$current_date = Carbon::now(\Session::get('timezone'))->toDateString();
  	$c =  DB::table('assignments as a')
  	->when($round_id != "" || $round_id != NULL, function ($query) use ($round_id) {
  		return $query->where('a.round_id', '=', $round_id);
  	})->when($project_id != "" || $project_id != NULL, function ($query) use ($project_id) {
  		return $query->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
  		->leftJoin('projects as p', 'r.project_id', '=', 'p.id')
  		->where('p.id', '=', $project_id );
  	})
  	->when($project_id == "" || $project_id == NULL, function ($query) use ($project_id) {
  		return $query->leftJoin('rounds as r', 'a.round_id', '=', 'r.id');
  	})
  	->where(function ($query){
  		//$current_date = Carbon::now(\Session::get('timezone'))->toDateString();
  		return $query->where(function ($query) {
  			$query->where(function ($query)  {
  				$query->where(DB::raw('IFNULL( DATE_FORMAT(a.deadline_date,"%Y-%c-%e"), DATE_FORMAT(r.deadline_date,"%Y-%c-%e"))'), '>=', DB::raw('CURDATE()'));
  			})
  			->where(function ($query){						
  				$query->where('a.is_scheduled', '=', true)
  				->where('a.is_reported', '=', false)
  				->where('a.is_partial', '=', false);
  			});
  		});
  	})
  	->count();
  	//->toSql();
  	return $c;
  } 

  public function getLateCount($project_id = "", $round_id = ""){
  	return DB::table('assignments as a')
  	->when($round_id != "" || $round_id != NULL, function ($query) use ($round_id) {
  		return $query->where('a.round_id', '=', $round_id);
  	})->when($project_id != "" || $project_id != NULL, function ($query) use ($project_id) {
  		return $query->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
  		->leftJoin('projects as p', 'r.project_id', '=', 'p.id')
  		->where('p.id', '=', $project_id );
  	})
  	->when($project_id == "" || $project_id == NULL, function ($query) use ($project_id) {
  		return $query->leftJoin('rounds as r', 'a.round_id', '=', 'r.id');
  	})
  	//->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
  	->where(function ($query){
  		$current_date = Carbon::now(\Session::get('timezone'))->toDateString();
  		return $query->where(function ($query) use ($current_date) {
  			$query->where(function ($query)  {
  				$query->where(DB::raw('IFNULL( DATE_FORMAT(a.deadline_date,"%Y-%c-%e"), DATE_FORMAT(r.deadline_date,"%Y-%c-%e"))'), '<', DB::raw('CURDATE()'));
  			})
  			->where(function ($query) use ($current_date) {
  				$query->where('a.is_scheduled', '=', true)
  				->where('a.is_reported', '=', false)
  				->where('a.is_partial', '=', false);
  			});
  		});
  	})->count();
  }

  public function getReportedCount($project_id = "", $round_id = ""){
  	return DB::table('assignments as a')
  	->when($round_id != "" || $round_id != NULL, function ($query) use ($round_id) {
  		return $query->where('a.round_id', '=', $round_id);
  	})->when($project_id != "" || $project_id != NULL, function ($query) use ($project_id) {
  		return $query->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
  		->leftJoin('projects as p', 'r.project_id', '=', 'p.id')
  		->where('p.id', '=', $project_id );
  	})
  	->where('a.is_reported', '=', true)
  	->where('a.is_approved', '=', false)->count();
  	// return Assignment::where([
  	// 	'is_reported' => true,
  	// 	'is_approved' => false,
  	// 	])->count();
  }

  public function getPartialCount($project_id = "", $round_id = ""){
  	return DB::table('assignments as a')
  	->when($round_id != "" || $round_id != NULL, function ($query) use ($round_id) {
  		return $query->where('a.round_id', '=', $round_id);
  	})->when($project_id != "" || $project_id != NULL, function ($query) use ($project_id) {
  		return $query->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
  		->leftJoin('projects as p', 'r.project_id', '=', 'p.id')
  		->where('p.id', '=', $project_id );
  	})
  	->where('a.is_partial', '=', true)
  	->count();

  	// return Assignment::where([
  	// 	'is_partial' => true
  	// 	])->count();
  }

  public function getCompletedCount($project_id = "", $round_id = ""){
  	return DB::table('assignments as a')
  	->when($round_id != "" || $round_id != NULL, function ($query) use ($round_id) {
  		return $query->where('a.round_id', '=', $round_id);
  	})->when($project_id != "" || $project_id != NULL, function ($query) use ($project_id) {
  		return $query->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
  		->leftJoin('projects as p', 'r.project_id', '=', 'p.id')
  		->where('p.id', '=', $project_id );
  	})
  	->where('a.is_approved', '=', true)
  	->count();

  	// return Assignment::where([
  	// 	'is_approved' => true
  	// 	])->count();
  }
}