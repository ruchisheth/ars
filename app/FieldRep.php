<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Contact;
use App\Http\AppHelper;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\AppData,
App\AssignmentsOffer,
App\_List,
App\Assignments,
App\Project,
App\User,
App\FieldRep_Org,
Excel,
DB,
Auth;
use Config;
use Session;

class FieldRep extends Model
{
	
	protected $table = 'fieldreps';	

	protected $fillable = [
	'user_id', 'fieldrep_code', 'approved_for_work', 'classification', 
	'initial_status', 'paperwork_received',
	'first_name', 'last_name', 'organization_name', 'social_security',
	'highest_edu',
	'internet_browser','distance_willing_to_travel','is_employed',
	'occupation','as_merchandiser','merchandiser_exp',
	'can_print','has_camera','has_computer','has_smartphone',
	'has_internet','experience','cities',
	'merchandise_specialproject','merchandise_reset',
	'merchandise_product_cutin','merchandise_new_store_setup',
	'merchandise_display_setup','merchandise_continuity',
	'demoevent_product_sampling','mystery_inperson_shopping',
	'mystery_shopping_phone_shops',
	'availability_monday','availability_tuesday',
	'availability_wednesday','availability_thursday',
	'availability_friday','availability_saturday','availability_sunday','have_done',
	'interested_in','is_invited', 'is_pending'];

	public function contacts()
	{
		return $this->hasMany(Contact::class,'reference_id');
	}

	public function users()
	{
		return $this->belongsTo(User::class,'user_id');
	}

	public function assignments()
	{
		return $this->hasMany(Assignment::class,'fieldrep_id');
	}

	public function offers()
	{
		return $this->hasMany(AssignmentsOffer::class,'fieldrep_id');
	}

	public function getFullName(){
		return $this->first_name.' '.$this->last_name;
	}

	public function has_empty_value($values){
		return in_array("", $values); //returns false
	}

	public function has_duplicate_value($values_arr){
		// $data = $data->toArray();

		// $sheet_codes = array_column(AppHelper::array_values_recursive($data), 0);
		$values = implode(',', $values_arr);
		// Convert FieldRep code to string
		$vals = array_map('strval', explode(',', $values));
		$vals = array_map('trim', $vals);
		$vals = array_filter($vals, create_function('$value', 'return $value !== "";'));
		$value_counts = array_count_values($vals);
		$filtered = array_filter($vals, function ($value) use ($value_counts) {
			return $value_counts[$value] > 1;
		});
		
		if(max($value_counts) > 1){
			return true;
		}else{
			return false;
		}
	}

	public function importData($request)
	{	
		ini_set('max_execution_time', 1500);
		if(Input::hasFile('importfile'))
		{
			$path = Input::file('importfile')->getRealPath();
			$data = Excel::load($path, function($reader) {})->get();

			$res['error_status'] = false;
			$response['error'] = $res;
			$response['success_records'] = [];

			if($data->count() == 0){
				$res['error_status'] = true;
				$res['err'][]['message'] = 'No data available to import.';
				$response['error'] = $res;
				return $response;
				//return $res;
			}

			$indexed = [
			'fieldrep_code',
			'first_name',
			'mi',
			'last_name',
			'email',
			'password',
			'alternate_email',
			'address1','address2','city','state','zip',
			'phone','phone_ext','work_phone','work_phone_ext','other_phone','other_phone_ext',
			'fax','cellphone',
			'hourly_rate',
			'subcontractor_code',
			'distance_willing_to_travel',
			'has_digital_camera',
			'digital_camera_supports_minicd',
			'has_computer',
			'work_experience',
			'availability',
			'education',
			'active',
			'approved_for_work',
			'paperwork_received',
			'view_name',
			'element_name_level_1','element_name_level_2','element_name_level_3','element_name_level_4','element_name_level_5',
			'card_num',
			'have_done','interested_in',
			'languages','language_other',
			'employed',
			'occupation','ethnicity',
			'can_print','browser',
			'history1','history2','history3',
			'history1_exp','history2_exp','history3_exp',
			'apply_date',
			'has_smartphone','has_internet'];

			$appData = new AppData();
			$project_types = Project::getProjectTypes();
			$project_types = array_map('strtolower',$project_types);

			$distance_to_travel = DB::table('_list')->where('list_name','=','rep_distance_willing_to_travel')->orderBy('list_order')->lists('item_name','id');
			$highest_edu = DB::table('_list')->where('list_name','=','rep_highest_edu_level')->orderBy('list_order')->lists('item_name','id');
			$internet_browser = DB::table('_list')->where('list_name','=','rep_internet_browser')->orderBy('list_order')->lists('item_name','id');

			$ins_arrs = array();

			$indexed_data = AppHelper::array_values_recursive($data->toArray());
			
			//list all rep codes mention in file
			$rep_codes = array_column($indexed_data, 0);
			$rep_codes = implode(',', $rep_codes);

			// Convert FieldRep code to string
			$rep_codes = array_map('strval', explode(',', $rep_codes));

			// Trim Fieldrep Code
			$rep_codes = array_map('trim', $rep_codes);

			// Reindex array starting from 2.
			$rep_codes = array_combine(range(2, count($rep_codes) + 1), $rep_codes);

			//list all email codes mention in file
			$rep_emails = array_column($indexed_data, 4);
			$rep_emails = implode(',', $rep_emails);
			// Convert FieldRep code to string
			$rep_emails = array_map('strval', explode(',', $rep_emails));

			// Trim Fieldrep Code
			$rep_emails = array_map('trim', $rep_emails);

			// Reindex array starting from 2.
			$rep_emails = array_combine(range(2, count($rep_emails) + 1), $rep_emails);
			
			foreach ($data as $row => $value)
			{
				$availability_data = array();
				$availability_arr = array();
				$have_done_arr = array();
				$interested_in_arr = array();

				$AllFields = [];
				$AllFields = $value->all(); //get value of rows
				$is_error = false;
				$row_num = $row + 2;
				$fields = $value->all();
				$fields = array_values($fields);
				if(count($indexed) != count($fields))
				{
					$res['error_status'] = true;
					$res['err'][]['message'] = 'Number of columns and order must be same as preview format.';
					$response['error'] = $res;
					return $response;
					//return $res;
				}

				$AllFields = array_combine ($indexed ,$fields );

				if($AllFields['fieldrep_code'] == ''){
					$is_error = true;
					$res['err'][$row]['row_number'] = $row_num;
					$res['err'][$row]['message'][] = 'FieldRep Code is required.';
				}else{
					$fieldrep_codes = array_map('current',FieldRep::get(['fieldrep_code'])->toArray());
					$fieldrep_codes = array_map('strtolower',$fieldrep_codes);
					if(in_array(strtolower($AllFields['fieldrep_code']), $fieldrep_codes)){
						$is_error = true;
						$res['err'][$row]['row_number'] = $row_num;
						$res['err'][$row]['message'][] = 'The FieldRep Code has already been taken.';
					}
					$duplicate_rep_codes = array_keys($rep_codes, $AllFields['fieldrep_code']);
					$duplicate_codes_count = count($duplicate_rep_codes);
					if($duplicate_codes_count > 1){
						$duplicate_rep_codes = array_flip($duplicate_rep_codes);
						unset($duplicate_rep_codes[$row_num]);
						$duplicate_rep_codes = array_flip($duplicate_rep_codes);
						$duplicate_rep_code_rows = implode(', ', $duplicate_rep_codes);
						$is_error = true;
						$res['err'][$row]['row_number'] = $row_num;
						$res['err'][$row]['message'][] = 'Duplicate FieldRep Code at row '.$duplicate_rep_code_rows;
					}

				}
				if(trim($AllFields['first_name']) == ''){
					$is_error = true;
					$res['err'][$row]['row_number'] = $row_num;
					$res['err'][$row]['message'][] = 'First Name is required.';
				}
				if($AllFields['last_name'] == ""){
					$is_error = true;
					$res['err'][$row]['row_number'] = $row_num;
					$res['err'][$row]['message'][] = 'Last Name is required.';
				}

				$AllFields['email'] = trim($AllFields['email']);
				if($AllFields['email'] == ""){
					$is_error = true;
					$res['err'][$row]['row_number'] = $row_num;
					$res['err'][$row]['message'][] = 'Email is required.';
				}
				else{
					if(trim(filter_var($AllFields['email']), FILTER_VALIDATE_EMAIL) === false){
						$is_error = true;
						$res['err'][$row]['row_number'] = $row_num;
						$res['err'][$row]['message'][] = 'The Email must be a valid email address.';
					}

					$user_emails = array_map('current',User::where('client_code',Auth::user()->client_code)->get(['email'])->toArray());
					if(in_array($AllFields['email'], $user_emails)){
						$is_error = true;
						$res['err'][$row]['row_number'] = $row_num;
						$res['err'][$row]['message'][] = 'The Email has already been taken.';
					}

					$duplicate_rep_email = array_keys($rep_emails, $AllFields['email']);
					
					$duplicate_emails_count = count($duplicate_rep_email);
					if($duplicate_emails_count > 1){
						$duplicate_rep_email = array_flip($duplicate_rep_email);
						unset($duplicate_rep_email[$row_num]);
						$duplicate_rep_email = array_flip($duplicate_rep_email);
						$duplicate_rep_email_rows = implode(', ', $duplicate_rep_email);
						$is_error = true;
						$res['err'][$row]['row_number'] = $row_num;
						$res['err'][$row]['message'][] = 'Duplicate Email at row '.$duplicate_rep_email_rows;
					}
				}

				if($AllFields['password'] == ""){
					$is_error = true;
					$res['err'][$row]['row_number'] = $row_num;
					$res['err'][$row]['message'][] = 'Password is required.';
				}

				$haveDone = $AllFields['have_done'];					
				$interestedIn = $AllFields['interested_in'];
				$Availability = $AllFields['availability'];

				//For Users Login							
				$UserFields['email'] = trim($AllFields['email']);
				$UserFields['role'] = 3;       				
				$UserFields['password'] =bcrypt($AllFields['password']);
				$UserFields['client_code'] = Auth::user()->client_code;
				$UserFields['db_version'] = Auth::user()->db_version;


				//Contact Data
				$ContactFields['entity_type'] = 4;
				$ContactFields['contact_type'] = 'Primary';
				$ContactFields['first_name'] = $AllFields['first_name'];
				$ContactFields['last_name'] = $AllFields['last_name'];
				$ContactFields['email'] = trim($AllFields['email']);
				$ContactFields['phone_number'] = $AllFields['phone'];					
				$ContactFields['address1'] = $AllFields['address1'];
				$ContactFields['address2'] = $AllFields['address2'];
				$ContactFields['city'] = $AllFields['city'];
				$ContactFields['state'] = $AllFields['state'];
				$ContactFields['zipcode'] = $AllFields['zip'];
				$ContactFields['cell_number'] = ltrim($AllFields['cellphone']);


				//Othere Details

				$AllFields['highest_edu'] = $AllFields['education'];
				$AllFields['is_employed'] = $AllFields['employed'];
				$AllFields['has_camera'] = $AllFields['has_digital_camera'];				
				$AllFields['organization_name'] = $AllFields['subcontractor_code'];
				$AllFields['status'] = $AllFields['initial_status'] = $AllFields['active'];			
				
				$Distance = $AllFields['distance_willing_to_travel'];
				$Education = $AllFields['education'];
				$Browser = $AllFields['browser'];
				$AllFields['experience'] = $AllFields['work_experience'];
				
				if(trim($ContactFields['zipcode']) !== ""){
					if(!is_numeric($ContactFields['zipcode'])){
						$is_error = true;
						$res['err'][$row]['row_number'] = $row_num;
						$res['err'][$row]['message'][] = 'Zip Code must be a number';
					}
				}


				/* Check phone number format (222-222-2222) */
				if(trim($ContactFields['phone_number']) !== ""){
					if(!preg_match('/^(?! )[[0-9]{3}-[0-9]{3}-[0-9]{4}]*$/', $ContactFields['phone_number'])){
						$is_error = true;
						$res['err'][$row]['row_number'] = $row_num;
						$res['err'][$row]['message'][] = 'Phone number has invalid format';
					}
				}

				if($AllFields['organization_name'] != ""){
					$org = FieldRep_Org::where(['id' => $AllFields['organization_name']])->get();
					if($org->isEmpty()){
						$is_error = true;
						$res['err'][$row]['row_number'] = $row_num;
						$res['err'][$row]['message'][] = 'FieldRep Organization you have mentioned is not found';
					}
				}


				// if($is_error){
				// 	$res['error_status'] = true;
				// 	continue;
				// }

				// Have Done And Intersted in
				if($haveDone != NULL){

					$have_done_data= explode(',',$haveDone);

					foreach ($have_done_data as $pType){
						$have_done_arr[] = array_search(trim(strtolower($pType)),$project_types);
					}
					if(in_array(false,$have_done_arr)){
						$is_error = true;
						$res['err'][$row]['row_number'] = $row_num;
						$res['err'][$row]['message'][] = 'Project type not match for column HaveDone';
					}
					// if($is_error){
					// 	$res['error_status'] = true;
					// 	continue;
					// }
				}
				
				if($interestedIn != NULL){
					
					$interested_in_data= explode(',',$interestedIn);
					foreach ($interested_in_data as $pType){
						$interested_in_arr[] = array_search(trim(strtolower($pType)),$project_types);
					}
					if(in_array(false,$interested_in_arr)){
						$is_error = true;
						$res['err'][$row]['row_number'] = $row_num;
						$res['err'][$row]['message'][] = 'Project type not match for column Intersted in';
					}
					// if($is_error){
					// 	$res['error_status'] = true;
					// 	continue;
					// }
				}
				
				
				if($is_error){
					$res['error_status'] = true;
					continue;
				}

				$AllFields['approved_for_work'] = strtolower($AllFields['approved_for_work']);
				$AllFields['initial_status'] = strtolower($AllFields['initial_status']);

				//if approved for work is leave blank in csv set default value to true
				if($AllFields['approved_for_work'] === "" || $AllFields['approved_for_work'] == '1' || $AllFields['approved_for_work'] == 'true' || $AllFields['approved_for_work'] == 'yes'){
					$AllFields['approved_for_work'] = true;
				}else if($AllFields['approved_for_work'] == '0' || $AllFields['approved_for_work'] == 'false' || $AllFields['approved_for_work'] == 'no'){
					$AllFields['approved_for_work'] = false;
				}

				if($AllFields['initial_status'] === "" || $AllFields['initial_status'] == '1' || $AllFields['initial_status'] == 'true' || $AllFields['initial_status'] == 'yes'){
					$AllFields['initial_status'] = true;
				}else if($AllFields['initial_status'] == '0' || $AllFields['initial_status'] == 'false' || $AllFields['initial_status'] == 'no'){
					$AllFields['initial_status'] = false;
				}
				
				//if approved for work is leave blank in csv set default value to true
				if($AllFields['approved_for_work'] == true){
					$AllFields['approved_for_work'] = true;
					$UserFields['status'] = $AllFields['status'] = $AllFields['initial_status'] = ($AllFields['initial_status'] == true) ?: false;
				}else{
					$UserFields['status'] = $AllFields['status'] = $AllFields['initial_status'] = false;
				}

				$AllFields['have_done'] = implode(',', $have_done_arr);
				$AllFields['interested_in'] = implode(',', $interested_in_arr);

				// Availability calculations
				$mon = $tue = $wed = $thu = $fri = $sat = $sun = array(0,0,0);

				$check_days = array("Monday", "Tuesday","Wednesday","Thursday","Friday", "Saturday","Sunday", "Weekends", "Weekdays", "All");
				$check_availability = array("Mornings", "Afternoons", "Evenings","All");

				$availability_arr = explode(',',$Availability);

				foreach($availability_arr as $key => $val) {
					if($val == ""){
						continue;
					}
					if(strtolower($val) == 'all'){
						$val = 'All;All';
					}
					$v = explode(';',$val);

					if(count($v) < 2){
						$is_error = true;
						$res['err'][$row]['row_number'] = $row_num;
						$res['err'][$row]['message'][] = 'Invalid value in availability column';
						continue;
					}
					//print_r($v);
					$days = trim($v[0]);
					$avail = trim($v[1]);

					if (in_array($days,$check_days) && in_array($avail,$check_availability))
					{
						if ($days == "All" || $days == "Weekdays" || $days == "Monday")
						{

							$mon[0] = ($avail == "Mornings" || $avail == "All" ? 1 : $mon[0]);
							$mon[1] = ($avail == "Afternoons" || $avail == "All" ? 1 : $mon[1]);
							$mon[2] = ($avail == "Evenings" || $avail == "All" ? 1 : $mon[2]);								

						}
						if ($days == "All" || $days == "Weekdays" || $days == "Tuesday")
						{
							$tue[0] = ($avail == "Mornings" || $avail == "All" ? 1 : $tue[0]);
							$tue[1] = ($avail == "Afternoons" || $avail == "All" ? 1 : $tue[1]);
							$tue[2] = ($avail == "Evenings" || $avail == "All" ? 1 : $tue[2]);
						}
						if ($days == "All" || $days == "Weekdays" || $days == "Wednesday")
						{
							$wed[0] = ($avail == "Mornings" || $avail == "All" ? 1 : $wed[0]);
							$wed[1] = ($avail == "Afternoons" || $avail == "All" ? 1 : $wed[1]);
							$wed[2] = ($avail == "Evenings" || $avail == "All" ? 1 : $wed[2]);
						}		
						if ($days == "All" || $days == "Weekdays" || $days == "Thursday")
						{
							$thu[0] = ($avail == "Mornings" || $avail == "All" ? 1 : $thu[0]);
							$thu[1] = ($avail == "Afternoons" || $avail == "All" ? 1 : $thu[1]);
							$thu[2] = ($avail == "Evenings" || $avail == "All" ? 1 : $thu[2]);
						}	
						if ($days == "All" || $days == "Weekdays" || $days == "Friday")
						{
							$fri[0] = ($avail == "Mornings" || $avail == "All" ? 1 : $fri[0]);
							$fri[1] = ($avail == "Afternoons" || $avail == "All" ? 1 : $fri[1]);
							$fri[2] = ($avail == "Evenings" || $avail == "All" ? 1 : $fri[2]);
						}	
						if ($days == "All" || $days == "Weekends" || $days == "Saturday")
						{
							$sat[0] = ($avail == "Mornings" || $avail == "All" ? 1 : $sat[0]);
							$sat[1] = ($avail == "Afternoons" || $avail == "All" ? 1 : $sat[1]);
							$sat[2] = ($avail == "Evenings" || $avail == "All" ? 1 : $sat[2]);
						}	
						if ($days == "All" || $days == "Weekends" || $days == "Sunday")
						{
							$sun[0] = ($avail == "Mornings" || $avail == "All" ? 1 : $sun[0]);
							$sun[1] = ($avail == "Afternoons" || $avail == "All" ? 1 : $sun[1]);
							$sun[2] = ($avail == "Evenings" || $avail == "All" ? 1 : $sun[2]);
						}
					}	
				}

				$AllFields['availability_monday'] = implode(",",$mon);
				$AllFields['availability_tuesday'] = implode(",",$tue);
				$AllFields['availability_wednesday'] = implode(",",$wed);
				$AllFields['availability_thursday'] =implode(",",$thu);
				$AllFields['availability_friday'] =implode(",",$fri);
				$AllFields['availability_saturday'] =implode(",",$sat);
				$AllFields['availability_sunday'] =implode(",",$sun);

				foreach ($distance_to_travel as $key => $value ) {						
					($Distance == $value) ? $AllFields['distance_willing_to_travel'] = $key : '';					
				}

				foreach ($highest_edu as $key => $value ) {					
					(strtolower($Education) == strtolower($value)) ? $AllFields['highest_edu'] = $key : '';
				}
				foreach ($internet_browser as $key => $value ) {					
					(strtolower($Browser) == strtolower($value)) ? $AllFields['internet_browser'] = $key : '';
				}

				$removeKeys = array('password', 'availability', 'mi', 'email', 'alternate_email', 'phone', 
					'address1', 'address2', 'city', 'state', 'zip', 
					'cellphone', 'phone_ext', 'work_phone', 'work_phone_ext', 'other_phone', 'other_phone_ext', 'fax', 
					'education', 'employed', 'has_digital_camera', 'digital_camera_supports_minicd', 				
					'hourly_rate', 'active', 'view_name', 'element_name_level_1', 'element_name_level_2', 'element_name_level_3', 
					'element_name_level_4', 'element_name_level_5', 'card_num', 'languages', 'language_other', 
					'ethnicity', 'history1', 'history2', 'history3', 'history1_exp', 'history2_exp',
					'history3_exp', 'apply_date', 'browser');

				foreach($removeKeys as $key) {
					unset($AllFields[$key]);
				}
				if(!empty($AllFields)){

					$ins_arrs[$row]['data'] = $AllFields;
					$ins_arrs[$row]['contacts'] = $ContactFields;
					$ins_arrs[$row]['user'] = $UserFields;
				}
			}
			if($res['error_status'] == true){
				// return $res;
				$response['error'] = $res;
				// return $response;
			}

			if(!empty($ins_arrs)){
				$response['success_records'] = array_keys($ins_arrs);
				foreach($ins_arrs as $ins_arr){
					DB::beginTransaction();
					$ins_arr['data'] = array_map('trim', $ins_arr['data']);
					Config::set('database.default','mysql');
					$user_id = User::create($ins_arr['user'])->id;

					Config::set('database.default',Session::get('selected_database'));
					$ins_arr['data']['user_id'] = $user_id;
					$ins_arr['data']['classification'] = 1;
					if($ins_arr['data']['organization_name'] == NULL){
						unset($ins_arr['data']['organization_name']);
					}
					$fieldrep_id = FieldRep::create($ins_arr['data'])->id;

					$ins_arr['contacts']['reference_id'] = $fieldrep_id;
					Contact::create($ins_arr['contacts']);
					DB::commit();
				}
			}
			return $response;
			//return $res;
		}
	}
}

