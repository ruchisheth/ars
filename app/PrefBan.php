<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\AppData;
use App\_List;
use Excel;
use DB;
use App\Chain;
use App\Site;
use App\FieldRep;
use App\Contact;
use App\ContactType;

class PrefBan extends Model
{
	protected $table = 'fieldrep_prefbans';

	protected $fillable = ['chain_id', 'site_id','fieldrep_id','activity', 'pref_ban'];

	public function importData($request)
	{

		if(Input::hasFile('importfile')){
			$path = Input::file('importfile')->getRealPath();
			$data = Excel::load($path, function($reader) {})->get();
			

			$error['error_status'] = false;
			$response['error'] = $error;
			$response['success_records'] = [];

			$indexed = [
			'setting_type',
			'fieldrep_code',
			'chain_code',
			'site_code',
			'activity',
			'ForAll'
			];
			if($data->count() == 0){
				$error['error_status'] = true;
				$error['err'][]['message'] = 'No data available to import.';
				$response['error'] = $error;
				return $response;
			}

			$chains = array_map('current',Chain::get(['id'])->toArray());
			$fieldreps = array_map('current',Fieldrep::get(['fieldrep_code'])->toArray());
			$fieldreps = array_map('strtolower',$fieldreps);
			
			$project_types = Project::getProjectTypes();
			$rep_activity = DB::table('_list')->where('list_name','=','rep_activity')->orderBy('list_order')->lists('item_name','id');

			$ins_arrs = array();
			foreach ($data as $row => $value) {
				$fields = $value->all();
				$fields = array_values($fields); 
				if(count($indexed) != count($fields)){
					$error['error_status'] = true;
					$error['err'][]['message'] = 'Number of columns and order must be same as preview format.';
					$response['error'] = $error;
					return $response;
				}
				$AllFields = array_combine ($indexed ,$fields);
				$row_num = $row + 2;
				$is_error = false;

				if($AllFields['setting_type'] == ""){
					$is_error = true;
					$error['err'][$row]['row_number'] = $row_num;
					$error['err'][$row]['message'][] = 'Setting Type is required.';
				}

				if($AllFields['fieldrep_code'] == ""){
					$is_error = true;
					$error['err'][$row]['row_number'] = $row_num;
					$error['err'][$row]['message'][] = 'FieldRep Code is required.';
				}else if(!in_array(strtolower($AllFields['fieldrep_code']), $fieldreps)){
					$is_error = true;
					$error['err'][$row]['row_number'] = $row_num;
					$error['err'][$row]['message'][] = 'Fieldrep doesn\'t exist.';
				}

				if($AllFields['chain_code'] == ""){
					$is_error = true;
					$error['err'][$row]['row_number'] = $row_num;
					$error['err'][$row]['message'][] = 'Chain Code is required.';
				}else if(!in_array($AllFields['chain_code'], $chains)){
					$is_error = true;
					$error['err'][$row]['row_number'] = $row_num;
					$error['err'][$row]['message'][] = 'Chain with Chain Code '.$AllFields['chain_code'].' doesn\'t exists.';
				}

				if($AllFields['site_code'] == ""){
					$is_error = true;
					$error['err'][$row]['row_number'] = $row_num;
					$error['err'][$row]['message'][] = 'Site Code is required.';
				}

				if(!in_array($AllFields['activity'], $project_types)){
					$is_error = true;
					$error['err'][$row]['row_number'] = $row_num;
					$error['err'][$row]['message'][] = 'Activity you entered is not Found';
				}

				if($is_error){
					$error['error_status'] = true;
					continue;
				}

				$site = Site::where(['chain_id' => $AllFields['chain_code'],'site_code'=>$AllFields['site_code']])->first();
				if($site == NULL){
					$is_error = true;
					$error['err'][$row]['row_number'] = $row_num;
					$error['err'][$row]['message'][] = 'Chain '.$AllFields['chain_code'].' doesn\'t have Site having Site Code '.$AllFields['site_code'].'.';
				}

				if($is_error){
					$error['error_status'] = true;
					continue;
				}

				$prefBan = strtolower($AllFields['setting_type']);
				$fieldrep =  FieldRep::where(['fieldrep_code'=>$AllFields['fieldrep_code']])->get(['id'])->first();
				$AllFields['fieldrep_id'] = $fieldrep->id;
				//$AllFields['fieldrep_id'] = $AllFields['fieldrep_code'];
				$AllFields['chain_id'] = $AllFields['chain_code'];

				$site_id = $site->id;
				$AllFields['site_id'] = $site_id;
				$activity = $AllFields['activity'];

				$AllFields['activity'] = array_search($activity,$project_types);

				if($prefBan == 'p'){
					$AllFields['pref_ban'] = 0;
				}
				else{
					$AllFields['pref_ban'] = 1;
				}

				$is_pref_exit = PrefBan::where(['fieldrep_id' => $AllFields['fieldrep_id'],'chain_id' => $AllFields['chain_code'],'site_id'=>$AllFields['site_id'],'activity'=>$AllFields['activity'] ])->count() > 0 ? true : false;
				if($is_pref_exit){
					$is_error = true;
					$error['err'][$row]['row_number'] = $row_num;
					$error['err'][$row]['message'][] = 'The Preferance already exist.';
				}

				if($is_error){
					$error['error_status'] = true;
					continue;
				}
				

				unset($AllFields['setting_type']);
				unset($AllFields['fieldrep_code']);
				unset($AllFields['chain_code']);
				unset($AllFields['site_code']);
				unset($AllFields['ForAll']);				

				if(!empty($AllFields)){
					$ins_arrs[$row]['data'] = $AllFields;
				}
			}
			if($error['error_status'] == true){
				$response['error'] = $error;
				//return $response;
			}
			if(!empty($ins_arrs)){
				$response['success_records'] = array_keys($ins_arrs);
				foreach($ins_arrs as $ins_arr){
					PrefBan::create($ins_arr['data']);
				}
			}
			return $response;
		}
	}
}
