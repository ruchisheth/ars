<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

use App\AppData;
use App\Http\AppHelper;
use Validator;
use App\_List;
use Excel;
use DB;
use App\Chain;
use App\Contact;
use App\PrefBan;
use App\ContactType;

class Site extends Model
{    
	protected $fillable = ['site_code','chain_id','fieldrep_id','site_name', 'distribution_center','street', 'city', 'state', 'zipcode','lat','long','phone_number','fax_number', 'notes','status'];

	// protected $casts = [
 //    'site_code' => 'integer',
	// ];
	
	public function contacts()
	{
		return $this->hasMany(Contact::class,'reference_id');
	}

	public function prefbans()
	{
		return $this->hasMany(PrefBan::class,'site_id');
	}

	public function fieldreps()
	{
		return $this->hasOne(FieldRep::class,'id','fieldrep_id');
	}

	public function getPreferedFieldRep($activity){
		$prefered_reps = [];
		$preferds = $this->prefbans->where('pref_ban', 0)->where('activity', $activity);
		foreach($preferds as $preferd){
			$prefered_reps[] = $preferd->fieldrep_id;
		}
		return $prefered_reps;
	}

	public function getBanFieldRep($activity){
		$ban_reps = [];
		$bans = $this->prefbans->where('pref_ban', 1)->where('activity', $activity);
		
		foreach($bans as $ban){
			$ban_reps[] = $ban->fieldrep_id;
		}
		return $ban_reps;
	}

	public function isPrefered($fieldrep_id, $activity){
		$prefered_reps = $this->getPreferedFieldRep($activity);
		return in_array($fieldrep_id, $prefered_reps) ? true : false;
	}

	public function isBan($fieldrep_id, $activity){
		$ban_reps = $this->getBanFieldRep($activity);
		return in_array($fieldrep_id, $ban_reps) ? true : false;
	}

	public function has_duplicate_value($sheet_codes){

		// $sheet_codes = implode(',', $sheet_codes);

		// // Convert FieldRep code to string
		// $integerIDs = array_map('strval', explode(',', $sheet_codes));

		// //remove the empty site codes to check for duplicates.
		// $codes = array_filter($integerIDs);

		// $value_counts = array_count_values($codes);
		// if(!empty($value_counts)){
		// 	if(max($value_counts) > 1){
		// 		return true;
		// 	}else{
		// 		return false;
		// 	}
		// }
		// return false;
	}

	public function generateNewSiteCode( $site_code, &$s_codes ) {
		if(in_array($site_code, $s_codes)){
			$site_code = $this->generateNewSiteCode($site_code + 1, $s_codes);
		}
		$s_codes[] = $site_code;
		return $site_code;
	}

	public function importData($request)
	{		
		
		if(Input::hasFile('importfile')){

			$res['error_status'] = false;
			$response['error'] = $res;
			$response['success_records'] = [];
			
			
			$path = Input::file('importfile')->getRealPath();
			$data = Excel::load($path, function($reader) {})->get();
			
			$indexed = ['chain_id','site_code','address','city','state','zip','site_name'];
			$validate_indexed = ['Chain Code','Site Code','Address','City','State','Zip Code','Site Name'];

			if($data->count() == 0){
				$res['error_status'] = true;
				$res['err'][]['message'] = 'No data available to import';
				$response['error'] = $res;
				return $response;
				//return $res;
			}
			// $s_codes = array_column(AppHelper::array_values_recursive($data->toArray()), 1);

			// if($this->has_duplicate_value($s_codes)){
			// 	$error['error_status'] = true;
			// 	$error['err'][]['message'] = 'Duplicate Code in Site Code Column';
			// 	return $error;
			// }
			if(count($indexed) != count(array_first($data->toArray()))){
				$res['error_status'] = true;
				$res['err'][]['message'][] = 'Number of columns and order must be same as preview format';
				$response['error'] = $res;
				return $response;
				//return $res;
			}

			/* combain validate and insert index with csv data*/
			$arr = array_map(function($v, $k) use($validate_indexed, $indexed){
				$arr['inserting_arr'] = array_combine($indexed, $k);
				$arr['inserting_arr']['street'] = $arr['inserting_arr']['address'];
				$arr['inserting_arr']['zipcode'] = $arr['inserting_arr']['zip'];
				unset($arr['inserting_arr']['address']);
				unset($arr['inserting_arr']['zip']);
				$arr['validating_arr'] = array_combine($validate_indexed, $k);
				return $arr;
			}, $data->toArray(), array_values($data->toArray()));


			$validate_data = array_pluck($arr, 'validating_arr');

			/* Start validating index form 2*/
			array_unshift($validate_data,"");
			array_unshift($validate_data,"");
			unset($validate_data[0]);
			unset($validate_data[1]);

			
			$dataInserted = array_pluck($arr, 'inserting_arr');
			array_unshift($dataInserted,"");
			array_unshift($dataInserted,"");
			unset($dataInserted[0]);
			unset($dataInserted[1]);
			$maxRecords = 3000;

			$site_codes = array_column($validate_data, 'Site Code');
			$site_codes = implode(',', $site_codes);

			// Convert FieldRep code to string
			$site_codes = array_map('strval', explode(',', $site_codes));

			// Trim Fieldrep Code
			$site_codes = array_map('trim', $site_codes);

			// Reindex array starting from 2.
			$site_codes = array_combine(range(2, count($site_codes) + 1), $site_codes);

			$validator =  Validator::make($validate_data, [
				'*.Chain Code' => 'required|exists:chains,id',
				'*.Site Code'	=>	'distinct|unique_with_arr:sites,site_code,NULL,id,chain_id,*.Chain Code',
				'*.Address'	=>	'required',
				'*.City'	=>	'required',
				'*.State'	=>	'required',
				'*.Zip Code'	=>	'required|numeric_with_arr',
				'*.Site Name'	=>	'required',
				],[
				'*.Chain Code.required' => "Chain Code is required",
				'*.Chain Code.exists' => "Chain Code not exists.",
				'*.Site Code.distinct'	=>	'Site Code have Duplicate Code in Column',
				'*.Site Code.unique_with_arr' => "Site Code has already been taken",
				'*.Address.required' => "Address is required",
				'*.City.required' => "City is required",
				'*.State.required' => "State is required",
				'*.Zip Code.required' => "Zip Code is required",
				'*.Site Name.required' => "Site Name is required",
				'*.Zip Code.numeric_with_arr' => "Zip Code must be a number",
				]);

			if ($validator->fails() || $res['error_status'] == true) {
				$messages = $validator->errors()->toArray();
				
				if(count($messages) > 0){
					$res['error_status'] = true;

					foreach ($messages as $key => $message) {
						$row_number = $row_nums[] = explode('.', $key)[0];
						unset($dataInserted[$row_number]);
						$res['err'][$row_number]['row_number'] = $row_number;
						if(is_array($message)){
							foreach ($message as $msg_key => $msg) {
								$res['err'][$row_number]['message'][] = $msg;
							}
						}else{
							$res['err'][$row_number]['message'][] = $message;
						}
					}

				}
				
			}

			foreach($validate_data as $v_key =>  $v_site){
				if($v_site['Site Code'] != ""){
					$duplicate_site_codes = array_keys($site_codes, $v_site['Site Code']);
					$duplicate_codes_count = count($duplicate_site_codes);
					$chain_code = strval($v_site['Chain Code']);
					if($duplicate_codes_count > 1){
						foreach ($duplicate_site_codes as $d_key => $d_value) {
							if($chain_code != strval($validate_data[$d_value]['Chain Code'])){
								unset($duplicate_site_codes[$d_key]);	
							}
						}
						$duplicate_codes_count = count($duplicate_site_codes);
						if($duplicate_codes_count > 1){

							$duplicate_site_codes = array_flip($duplicate_site_codes);
							unset($duplicate_site_codes[$v_key]);
							$duplicate_site_codes = array_flip($duplicate_site_codes);
							$duplicate_site_code_rows = implode(', ', $duplicate_site_codes);
							$is_error = true;
							$res['err'][$v_key]['row_number'] = $v_key;
							$res['err'][$v_key]['message'][] = 'Duplicate Site Code Code at row '.$duplicate_site_code_rows;
						}
					}
				}
			}

			if($res['error_status'] == true){
				$response['error'] = $res;
			}
			//dd($response);

			

			foreach($dataInserted as $key => $sd){

				$response['success_records'][] = $key - 2;

				$site = Site::create($sd);
			}
			DB::select('CALL  setMaxSiteCode()');

			return $response;

		}
	}
}
