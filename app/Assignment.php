<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redirect;

use App\Http\AppHelper;

use App\Payment;
use App\AppData;
use App\_List;
use Excel;
use DB;
use App\Chain;
use App\Contact;
use App\FieldRep;
use App\Round;
use App\Instruction;
use App\FieldRepsCriteria;
use Auth;
use App\AssignmentsInstruction;
use Carbon;


class Assignment extends Model
{
    //
	protected $fillable = [
	'round_id', 'site_id', 'instruction_id', 'assignment_code', 'fieldrep_id', 
	'schedule_date',  'start_date', 'deadline_date', 'start_time', 'deadline_time',
	'actual_visit_date', 'is_scheduled', 'is_offered', 'is_reported', 'is_partial', 'is_approved',
	'approved_at', 'reported_at',
	];
	
	protected $user_role = '';

	protected $timezone = '';

	public function setDefaults(){

		if($this->user_role == ''){

// 			$this->user_role = Auth::user()->roles->slug;
            if(Auth::check()){
				$this->user_role = Auth::user()->roles->slug;
			}else{
				$this->user_role = config('constants.USERTYPE.ADMIN');
			}

		}

		if($this->timezone == ""){

			if($this->user_role == 'admin'){

				$this->timezone = AppHelper::getSelectedTimeZone();

			}else{

				$this->timezone = \Session::get('local_tz');

			}
		}
	}

	public function getStartDateAttribute($value){
		$this->setDefaults();

		if($value == NULL) {
			return NULL;
		}

		$date = $value;

		$time = AppHelper::convertTimeZone($this->start_time, $this->timezone, 'UTC');

		$time = date_formats($time,AppHelper::TIME_SAVE_FORMAT);

		$date_time = $date.' '.$time;

		$value = AppHelper::convertTimeZone($date_time, 'UTC', $this->timezone);

		return date_formats($value,AppHelper::DATE_DISPLAY_FORMAT);
	}
	
	public function getReportedAtAttribute($sTimeStamp){
		$this->setDefaults();

		if($sTimeStamp == NULL) {
			return NULL;
		}

		$sReportedAt = AppHelper::convertTimeZone($sTimeStamp, 'UTC', $this->timezone);

		return date_formats($sReportedAt,AppHelper::DATE_DISPLAY_FORMAT);
	}

	public function getDeadlineDateAttribute($value){
		$this->setDefaults();

		if($value == NULL) {

			return NULL;

		}

		$date = $value;

		$time = AppHelper::convertTimeZone($this->deadline_time, $this->timezone, 'UTC');

		$time = date_formats($time,AppHelper::TIME_SAVE_FORMAT);

		$date_time = $date.' '.$time;

		$value = AppHelper::convertTimeZone($date_time, 'UTC', $this->timezone);

		return date_formats($value,AppHelper::DATE_DISPLAY_FORMAT);

	}

	public function getScheduleDateAttribute($value){
		$this->setDefaults();

		if($value == NULL) {
			return NULL;
		}

		$date = $value;
		
		$time = AppHelper::convertTimeZone($this->start_time, $this->timezone, 'UTC');
		
		$time = date_formats($time,AppHelper::TIME_SAVE_FORMAT);

		$date_time = $date.' '.$time;
		
		$value = AppHelper::convertTimeZone($date_time, 'UTC', $this->timezone);

		return date_formats($value,AppHelper::DATE_DISPLAY_FORMAT);

	}

	public function getStartTimeAttribute($value){
		$this->setDefaults();

		if($value == NULL) {

			return NULL;

		}

		$value = AppHelper::convertTimeZone($value, 'UTC', $this->timezone);

		return format_time($value,AppHelper::TIME_DISPLAY_FORMAT);

	}

	public function getDeadlineTimeAttribute($value){

		$this->setDefaults();

		if($value == NULL) {

			return NULL;

		}

		$value = AppHelper::convertTimeZone($value, 'UTC', $this->timezone);

		return format_time($value,AppHelper::TIME_DISPLAY_FORMAT);

	}

	public function getApprovedAtAttribute($value){
		$this->setDefaults();

		$value = AppHelper::convertTimeZone($value, 'UTC', $this->timezone);

		return format_time($value,AppHelper::DATE_DISPLAY_FORMAT);
	}

	public function setStartDateAttribute($value){
		$this->setDefaults();

		if($value == ""){

			$this->attributes['start_date'] = NULL;

		}else{

			$date = $value;

			$time = AppHelper::convertTimeZone($this->start_time, $this->timezone, AppHelper::getSelectedTimeZone());

			$utc_date = AppHelper::getUTCDateTime($date, $time);

			$start_date = date_formats($utc_date,AppHelper::DATE_SAVE_FORMAT);

			$this->attributes['start_date'] = $start_date;

		}
	}

	public function setDeadlineDateAttribute($value){

		$this->setDefaults();

		if($value == ""){

			$this->attributes['deadline_date'] = NULL;

		}else{

			$date = $value;

			$time = AppHelper::convertTimeZone($this->deadline_time, $this->timezone, AppHelper::getSelectedTimeZone());

			$utc_date = AppHelper::getUTCDateTime($date, $time);

			$deadline_date = date_formats($utc_date,AppHelper::DATE_SAVE_FORMAT);

			$this->attributes['deadline_date'] = $deadline_date;
		}
	}

	public function setScheduleDateAttribute($value){

		$this->setDefaults();

		if($value == ""){

			$this->attributes['schedule_date'] = NULL;

		}else{

			$date = $value;

			$time = AppHelper::convertTimeZone($this->start_time, $this->timezone, AppHelper::getSelectedTimeZone());

			$utc_date = AppHelper::getUTCDateTime($date, $time);

			$schedule_date = date_formats($utc_date,AppHelper::DATE_SAVE_FORMAT);

			$this->attributes['schedule_date'] = $schedule_date;        

		}
	}

	public function setStartTimeAttribute($value){

		$this->setDefaults();
		

		if($value == ""){

			$this->attributes['start_time'] = NULL;

		}else{

			$date = '';

			$time = $value;

			$utc_date = AppHelper::getUTCDateTime($date, $time);

			$start_time = date_formats($utc_date,AppHelper::TIME_SAVE_FORMAT);

			$this->attributes['start_time'] = $start_time;

		}
	}

	public function setDeadlineTimeAttribute($value){

		$this->setDefaults();

		if($value == ""){

			$this->attributes['deadline_time'] = NULL;

		}else{

			$date = '';

			$time = $value;

			$utc_date = AppHelper::getUTCDateTime($date, $time);

			$deadline_time = date_formats($utc_date,AppHelper::TIME_SAVE_FORMAT);

			$this->attributes['deadline_time'] = $deadline_time;
		}
	} 


	public function instructions()
	{
		return $this->belongsToMany(Instruction::class,'assignments_instructions')->withPivot('assignment_id');
	}

	public function sites()
	{
		return $this->belongsTo(Site::class,'site_id');
	}

	public function rounds()
	{
		return $this->belongsTo(Round::class,'round_id');
	}

	public function fieldreps()
	{
		return $this->hasOne(FieldRep::class,'id','fieldrep_id');
	}

	public function offers()
	{
		return $this->hasMany(AssignmentsOffer::class,'assignment_id');
	}

	public function getAssignmentDetail(Assignment $assignment){

		$details['code']            =   format_code($this->id);
		
		$details['schedule_date']   =   $this->getAssignmentScheduleDateTime();
		
		$details['start_date']      =   $this->getAssignmentStartDate();
		
		$details['deadline_date']   =   $this->getAssignmentEndDate();
		
		$details['estimated_duration']= $this->rounds->estimated_duration;
		
		$details['round_name']      =   $this->rounds->round_name;
		
		$details['fieldrep_name']   =   $this->fieldreps->first_name.' '.$assignment->fieldreps->last_name;
		
		$details['site_name']       =   $this->sites->site_name;
		
		$details['site_code']       =   $this->sites->site_code;
		
		$details['project_name']    =   $this->rounds->projects->project_name;
		
		$details['client_name']     =   $this->rounds->projects->chains->clients->client_name;
		
		$details['client_logo']     =   $this->rounds->projects->chains->clients->client_logo;
		
		$details['location']        =   $this->getAssignmentLocation($assignment);

		return $details;

	}

	public function getAssignmentStatus(){

		$assignment_satus = "";

		if($this->is_approved){

			$assignment_satus = '<span class="label label-success">'.trans('messages.assignment_status.completed').'</span>';

		}elseif($this->is_partial){

			$assignment_satus = '<span class="label label-danger">'.trans('messages.assignment_status.rejected').'</span>';

		}elseif($this->is_reported){

			$assignment_satus = '<span class="label bg-purple">'.trans('messages.assignment_status.reported').'</span>';

		}elseif($this->is_scheduled){

			if($this->isSurveyDeadlinePast()){

				$assignment_satus = '<span class="label label-danger">'.trans('messages.assignment_status.late').'</span>';

			}else{

				$assignment_satus = '<span class="label label-primary">'.trans('messages.assignment_status.scheduled').'</span>';

			}

		}elseif(!$this->is_scheduled){

			$still_offered = $this->isStillOffered();

			if($still_offered){

				$assignment_satus = '<span class="label label-offered">'.trans('messages.assignment_status.offered').'</span>';

			}

			else{

				$assignment_satus = '<span class="label label-default">'.trans('messages.assignment_status.pending').'</span>';

			}

		}

		return $assignment_satus;

	}
	
	public function callGetAssignmentStatus(){
		$assignment_satus = "";
		if($this->is_approved){
			$assignment_satus = trans('messages.assignment_status.completed');
		}elseif($this->is_partial){
			$assignment_satus = trans('messages.assignment_status.partial');
		}elseif($this->is_reported){
			$assignment_satus = trans('messages.assignment_status.reported');
		}elseif($this->is_scheduled){
			if($this->isSurveyDeadlinePast()){
				$assignment_satus = trans('messages.assignment_status.late');
			}else{
				$assignment_satus = trans('messages.assignment_status.scheduled');
			}
		}elseif(!$this->is_scheduled){
			$still_offered = $this->isStillOffered();
			if($still_offered){
				$assignment_satus = trans('messages.assignment_status.offered');
			}
			else{
				$assignment_satus = trans('messages.assignment_status.pending');
			}
		}
		return $assignment_satus;
	}

	public function isStillOffered(){
		$offers_count = $this->offers->count();
		$still_offered = false;
		if($offers_count > 0){
			$offers = $this->offers;
			foreach($offers as $offer){
				if($offer->is_accepted === NULL){
					$still_offered = true;
				}
			}
		}
		return $still_offered;
	}

	public function getAssignmentLocation(){
		$location = $this->sites->site_name." ";
		$location .= format_location($this->sites->city,$this->sites->state,$this->sites->zipcode);
		return trim($location);
	}

	public function getAssignmentStartDate(){ 

		if($this->start_date == null){
			$time = $this->getAssignmentStartTime();
			$this->rounds()->attributes['start_time'] = $time;
			return $this->rounds->start_date;

		}else{
			return $this->start_date;
		}
	}

	public function getAssignmentEndDate(){
		if($this->deadline_date == null){
			$time = $this->getAssignmentDeadlineTime();
			$this->rounds()->attributes['deadline_time'] = $time;
			return $this->rounds->deadline_date;
		}else{
			return $this->deadline_date;
		}
	}

	public function getAssignmentScheduleDate(){

		if($this->schedule_date == null){
      $time = $this->getAssignmentStartTime(); //06:00 PM
      $this->rounds()->attributes['start_time'] = $time;
      return $this->rounds->schedule_date;
    }else{
    	return $this->schedule_date;
    }    
  }

  public function getAssignmentStartTime(){
  	$time = null;

  	if($this->start_time == null){      
  		$time = $this->rounds->start_time;
  	}
  	else{
  		$time = $this->start_time;
  	}
  	return $time;
  }

  public function getAssignmentDeadlineTime(){
  	$time = null;
  	if($this->deadline_time == null){
  		$time = $this->rounds->deadline_time;
  	}
  	else{
  		$time = $this->deadline_time;
  	}
  	return $time;
  }

  public function getAssignmentDuration(){
  	if($this->estimated_duration == null){
  		return $this->rounds->estimated_duration;
  	}else{
  		return $this->estimated_duration;
  	}
  }

  public function getAssignmentScheduleDateTime(){
  	$date_time = null;
  	$time = null;
  	$date = $this->getAssignmentScheduleDate();

  	$time = $this->getAssignmentStartTime();
  	$date_time = $date." ".$time;
  	return trim($date_time);
  }

  public function getSurveyStartDate(){
  	$start_date    = $this->getAssignmentStartDate();
  	$start_time    = $this->getAssignmentStartTime();
  	$start_dt       = Carbon::parse($start_date.' '.$start_time, $this->timezone);
  	$extension_days   = $this->rounds->survey_entry_before;
  	$survey_start_date  = $start_dt->subDays($extension_days);
  	return $survey_start_date;
  }

  public function getSurveyDeadlineDate(){
  	$deadline_date    = $this->getAssignmentEndDate();
  	$deadline_time    = $this->getAssignmentDeadlineTime();
  	$dadline_dt       = Carbon::parse($deadline_date.' '.$deadline_time, $this->timezone);
  	$extension_days   = $this->rounds->survey_entry_after;
  	$survey_end_date  = $dadline_dt->addDays($extension_days);
  	return $survey_end_date;
  }

  public function isSurveyAvailable(){
  	$this->setDefaults();
  	$current_date       = \Carbon::now($this->timezone);
  	$survey_start_date  = $this->getSurveyStartDate();
  	$survey_end_date    = $this->getSurveyDeadlineDate();
  	return $current_date->between($survey_start_date, $survey_end_date);
  }

  public function isSurveyDeadlinePast(){
  	$this->setDefaults();
  	if(!$this->isSurveyAvailable()){
  		$current_date     =  \Carbon::now($this->timezone);
  		$survey_end_date  = $this->getSurveyDeadlineDate();
  		return $current_date->gt($survey_end_date) ? true :false;

      // $current_date->hour = $survey_end_date->hour = 00;
      // $current_date->minute = $survey_end_date->minute = 00;
      // $current_date->second = $survey_end_date->second = 00;
      //return $current_date->gt($survey_end_date) ? true :false;
  	}
  	return false;
  }

  public function getAssignmentHasNoInstruction($round){

  	$assignment = $round->assignments;

  	$sites = [];
  	$assignments = $assignment->filter(function ($assignment) {

  		if($assignment->instruction_id == 0 || $assignment->instruction_id == null )
  			return $assignment;
  	});

  	$assignments = $assignments->all();

  	foreach($assignments as $assignment){

  		$sites[$assignment->id] = $assignment->sites->site_name;
  	}

  	return $sites;
  }

  public function markAsPartial(){
  	return $this->update(['is_partial' => true, 'is_reported' => false]);
  }

  public function markAsReported(){
  	return $this->update(['is_reported' => true, 'is_partial' => false, 'reported_at' => Carbon::now()]);
  }

  public function markAsApproved(){
  	try{
  		return $this->update(['is_approved' => true, 'approved_at' => Carbon::now()]);

  	}catch(Exception $e){
  	}
  }

  public function importData($request){
    http_response_code(500);
  	if(Input::hasFile('importfile')){
  		$path = Input::file('importfile')->getRealPath();
  		$data = Excel::load($path, function($reader) {})->get();
  		
  		$rounds = array_map('current',Round::get(['id'])->toArray());

  		$error['error_status'] = false;

  		$response['error'] = $error;
  		$response['success_records'] = [];

  		$indexed = [
  		'project_code',
  		'round_code',
  		'chain_code',
  		'site_code',
  		'date_intended_completion',
  		'time_intended_completion',
  		'notes',
  		'assignment_code',
  		'fieldrep_code',
  		'email_rep',
  		'debitcard_fund_amount'];

  		if($data->count() == 0){
  			$error['error_status'] = true;
  			$error['err'][]['message'] = 'No data available to import.';
  			$response['error'] = $error;
  			return $response;
  		}

  		$chains = array_map('current',Chain::get(['id'])->toArray());

  		$ins_arrs = array();
  		foreach ($data as $row => $value) {
  			$fields = $value->all();
  			$round = NULL;
  			$site = NULL;
  			$fieldrep = NULL;
  			$criteria = NULL;

  			$fields = array_values($fields);
  			if(count($indexed) != count($fields)){
  				$error['error_status'] = true;
  				$error['err'][]['message'] = 'Number of columns and order must be same as preview format.';
  				$response['error'] = $error;
  				return $response;
  			}
  			$AllFields = array_combine ($indexed ,$fields );
  			$row_num = $row + 2;
  			$is_error = false;

  			if($AllFields['round_code'] == ""){
  				$is_error = true;
  				$error['err'][$row]['row_number'] = $row_num;
  				$error['err'][$row]['message'][] = 'Round Code is required.';
  				$data[$row]['error'] = 'Round Code is required';
  			}else{
  				$round_id = $AllFields['round_id'] = $AllFields['round_code'];
  				$round = Round::find($round_id); 
  				if($round == NULL){
  					$is_error = true;
  					$error['err'][$row]['row_number'] = $row_num;
  					$error['err'][$row]['message'][] = 'Round with Round Code '.$AllFields['round_code'].' doesn\'t exists.';
  				}else{
  					if($round->isDeadlinePast()){
  						$is_error = true;
  						$error['err'][$row]['row_number'] = $row_num;
  						$error['err'][$row]['message'][] = 'Can not import Assignment as Deadline Date of Round is Passed Now.';
  					}
  				}
  			}

  			if($AllFields['site_code'] == ""){
  				$is_error = true;
  				$error['err'][$row]['row_number'] = $row_num;
  				$error['err'][$row]['message'][] = 'Site Code is required.';
  			}else{
  				if($round != NULL){
  					$site = Site::where(['chain_id' => $round->projects->chain_id,'site_code'=>$AllFields['site_code']])->first();
  					if($site == NULL){
  						$is_error = true;
  						$error['err'][$row]['row_number'] = $row_num;
  						$error['err'][$row]['message'][] = 'Chain with Chain Code '.$round->projects->chain_id .'  doesn\'t have Site having Site Code '.$AllFields['site_code'].'.';
  					}
  				}

  			}
  			
  			if($AllFields['fieldrep_code'] != ""){
  				$fieldrep = FieldRep::where(['fieldrep_code' => $AllFields['fieldrep_code']])->first();
  				if($fieldrep == NULL){
  					$is_error = true;
  					$error['err'][$row]['row_number'] = $row_num;
  					$error['err'][$row]['message'][] = 'Fieldrep doesn\'t exist.';
  				}else{
  					if(!$fieldrep->initial_status){
  						$is_error = true;
  						$error['err'][$row]['row_number'] = $row_num;
  						$error['err'][$row]['message'][] = 'Fieldrep is currently Inactive';
  					}
  					else{
  						$AllFields['fieldrep_id'] = $fieldrep->id;      
  					}
  				}
  			}else{

  				if(isset($site) && $site != NULL){
  					if($site->fieldreps != NULL){
  						$fieldrep = FieldRep::find($site->fieldreps->id);
  						if($fieldrep->initial_status){
  							$AllFields['fieldrep_id'] =  $site->fieldreps->id;   
  						}else{
  							$is_error = true;
  							$error['err'][$row]['row_number'] = $row_num;
  							$error['err'][$row]['message'][] = 'Preferred Fieldrep for this Store is currently Inactive';		
  						}
  					}else{
  						$AllFields['fieldrep_id'] = NULL;
  					}
  				}else{
  					$AllFields['fieldrep_id'] = NULL;
  				}
  			}

  			if(isset($site) && $site != NULL && $round != NULL){
  				$site_id = $site->id;
  				$template_id = $round->template_id;

  				$sites = get_available_sites($round);
  				if(!array_key_exists($site_id, $sites)){
  					$is_error = true;
  					$error['err'][$row]['row_number'] = $row_num;
  					$error['err'][$row]['message'][] = 'The Assignment for Site '.$AllFields['site_code'].' already exist';
  				}

  				if($template_id == NULL || $template_id == 0){
  					$is_error = true;
  					$error['err'][$row]['row_number'] = $row_num;
  					$error['err'][$row]['message'][] = 'You can not generate Assignment unless a survey has been selected for Round.';
  				}

  			}

  			/*Get Round Criteria and check if Filedrep match criteria*/
  			if($round != NULL){
  				$criteria = FieldRepsCriteria::where(['round_id'=>$round_id])->first();
  			}
  			if(isset($fieldrep) && $fieldrep != NULL && $criteria != NULL){


  				if($criteria->has_camera == true && (!$fieldrep->has_camera)){
  					$is_error = true;
  					$error['err'][$row]['row_number'] = $row_num;
  					$error['err'][$row]['message'][] = 'Fieldrep does not match criteria. Assignment needs Fieldrep to have Camera';
  				}


  				if($criteria->has_internet == true && (!$fieldrep->has_camera)){
  					$is_error = true;
  					$error['err'][$row]['row_number'] = $row_num;
  					$error['err'][$row]['message'][] = 'Fieldrep does not match criteria. Assignment needs Fieldrep to have Internet';
  				}

  				if($criteria->exp_match_project_type == true){
  					$has_project_experience = true;
  					$project_types = Project::getProjectTypes();
  					$round = Round::find($round_id);
  					$project_type = $round->projects->project_type;
  					if($fieldrep->have_done != ""){
  						$have_done = explode(',', $fieldrep->have_done);
  						if(!in_array($project_type, $have_done)){
  							$has_project_experience = false;
  						}
  					}else{
  						$has_project_experience = false;
  					}

  					if(!$has_project_experience){
  						$is_error = true;
  						$error['err'][$row]['row_number'] = $row_num;
  						$error['err'][$row]['message'][] = 'Fieldrep does not match criteria. Assignment requires Fieldrep to have experience in Project like '.$project_types[$project_type].'.';
  					}
  				}

  				if($criteria->distance != NULL){
  					$contact = DB::table('contacts as co')
  					->where('co.reference_id', '=', $fieldrep->id)
  					->where('co.entity_type', '=', '4')
  					->where('co.contact_type', '=', 'primary')
  					->where('co.lat', '!=', NULL)
  					->where('co.long', '!=', NULL)
  					->first();

  					$lat1 = $site->lat;
  					$long1 = $site->long;

  					$distance = 0;
  					if($lat1 != '' && $long1 != '' && $contact !=  NULL){
  						$distance = AppHelper::getDistance(['lat' => $lat1, 'long' => $long1], ['lat' => $contact->lat, 'long' => $contact->long]);
  					}
  					if($distance >= $criteria->distance || $distance == 0){
  						$is_error = true;
  						$error['err'][$row]['row_number'] = $row_num;
  						$error['err'][$row]['message'][] = 'Fieldrep does not match criteria. Assignment requires Fieldrep to be within '.$criteria->distance.' Miles from store location.';
  					}   
  				}

  				if($criteria->allowable_days != null){
  					$days = explode(',', $criteria->allowable_days);
  					$availability = true;
  					foreach($days as $day){
  						$avail = 'availability_'.$day;
  						if(!str_contains($fieldrep->$avail, 1)){
  							$availability = false;
  							break;
  						}
  					}
  					if(!$availability){
  						$is_error = true;
  						$error['err'][$row]['row_number'] = $row_num;
  						$error['err'][$row]['message'][] = 'Fieldrep does not match criteria. Assignment requires Fieldrep to be available on '.$criteria->allowable_days.'.';
  					}
  				}
  			}



  			if($is_error){
  				$error['error_status'] = true;
  				continue;
  			}

  			if($AllFields['assignment_code'] == ""){
  				$AllFields['assignment_code'] = get_unique_num();
  			}

  			$AllFields['site_id'] = $site_id;
  			// if(isset($fieldrep) && $fieldrep != NULL){
  			// 	$fieldrep =  FieldRep::where(['fieldrep_code'=>$AllFields['fieldrep_code']])->get(['id'])->first();
  			// 	$AllFields['fieldrep_id'] = $fieldrep->id;      
  			// }else{
  			// 	$AllFields['fieldrep_id'] = null;
  			// }

  			$intendedDate = $AllFields['date_intended_completion'];

  			if($intendedDate <= 0)
  				{    $AllFields['deadline_date'] = null;    }          
  			elseif($intendedDate !== date_formats($intendedDate,AppHelper::DATE_SAVE_FORMAT)){            
  				$AllFields['deadline_date'] = null;
  			}
  			else{
  				$AllFields['deadline_date'] = date_formats($intendedDate,AppHelper::DATE_SAVE_FORMAT);
  			}

  			$AllFields['email_rep'] = strtolower(trim($AllFields['email_rep']));

  			if($AllFields['email_rep'] == '0' || $AllFields['email_rep'] == 'false' || $AllFields['email_rep'] == 'no'){
  				$AllFields['email_rep'] = false;
  			}else if($AllFields['email_rep'] == '1' || $AllFields['email_rep'] == 'true' || $AllFields['email_rep'] == 'yes'){
  				$AllFields['email_rep'] = true;
  			}


  			unset($AllFields['project_code']);
  			unset($AllFields['chain_code']);
  			unset($AllFields['round_code']);
  			unset($AllFields['site_code']);
  			unset($AllFields['date_intended_completion']);
  			unset($AllFields['time_intended_completion']);
  			unset($AllFields['debitcard_fund_amount']);
  			unset($AllFields['assign_seq']);
  			unset($AllFields['fieldrep_code']);

  			if(!empty($AllFields)){
  				$ins_arrs[$row]['data'] = $AllFields;
  			}
  		}


  		if($error['error_status'] == true){
  			$response['error']  =  $error;
      //return $error;
  		}

  		if(!empty($ins_arrs)){
  		    
  			$response['success_records'] = array_keys($ins_arrs);

  			foreach($ins_arrs as $ins_arr){        
  				$notify_rep = $ins_arr['data']['email_rep'];
  				
  				unset($ins_arr['data']['email_rep']);

  				$assignment = Assignment::create(
  					[
  					'round_id' => $ins_arr['data']['round_id'],
  					'site_id' => $ins_arr['data']['site_id'],
  					'assignment_code' => $ins_arr['data']['assignment_code'],
  					]);
  				$instruction = Instruction::where(['round_id'=>$ins_arr['data']['round_id'], 'is_default' => true])->first();
  				if($instruction){
  					AssignmentsInstruction::applyDefault($instruction->id,  $assignment->id);
  				}

  				if($ins_arr['data']['fieldrep_id'] != NULL){
  					app('App\Http\Controllers\Admin\AssignmentController')->scheduelFieldrep($assignment, $ins_arr['data']['fieldrep_id'], NULL, NULL, $notify_rep);
  				}
  				// else{
  				// 	$site = Site::find($ins_arr['data']['site_id']);
  				// 	if($site->fieldreps != null){
  				// 		$fieldrep_id = $site->fieldreps->id;
  				// 		app('App\Http\Controllers\Admin\AssignmentController')->scheduelFieldrep($assignment, $fieldrep_id, NULL, NULL, $notify_rep);
  				// 	}
  				// }
  			}
  		}
    // return $error;
  		return $response;
  	}
  }
  
  public static function getPendingCount($nIdProject = "", $nIdRound = ""){
			$pendign_assignments =  DB::table('assignments as a')
			->when($nIdRound != "" || $nIdRound != NULL, function ($query) use ($nIdRound) {
				return $query->where('a.round_id', '=', $nIdRound);
			})->when($nIdProject != "" || $nIdProject != NULL, function ($query) use ($nIdProject) {
				return $query->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
				->leftJoin('projects as p', 'r.project_id', '=', 'p.id')
				->where('p.id', '=', $nIdProject );
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

		public static function getOfferedCount($project_id = "", $round_id = ""){
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

		public static function getScheduleCount($project_id = "", $round_id = ""){
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
			return $c;
		} 

		public static function getLateCount($project_id = "", $round_id = "")
		{
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

		public static function getReportedCount($project_id = "", $round_id = ""){
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
		}

		public static function getPartialCount($nProjectId = "", $round_id = ""){
			return DB::table('assignments as a')
			->when($round_id != "" || $round_id != NULL, function ($query) use ($round_id) {
				return $query->where('a.round_id', '=', $round_id);
			})->when($nProjectId != "" || $nProjectId != NULL, function ($query) use ($nProjectId) {
				return $query->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
				->leftJoin('projects as p', 'r.project_id', '=', 'p.id')
				->where('p.id', '=', $nProjectId );
			})
			->where('a.is_partial', '=', true)
			->count();
		}

		public static function getCompletedCount($project_id = "", $round_id = ""){
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
		}
}
