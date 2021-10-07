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
use App\Contact;
use Validator;
use App\ContactType;

class FieldRep_Org extends Model
{
	protected $table = 'fieldrep_orgs';

	protected $fillable = ['fieldrep_org_name','notes','status'];


	public function contacts()
	{
		return $this->hasMany(Contact::class,'reference_id');
	}

	public function importData($request){
		if(Input::hasFile('importfile')){

			$res['error_status'] = false;
			$response['error'] = $res;
			$response['success_records'] = [];

			$path = Input::file('importfile')->getRealPath();
			$data = Excel::load($path, function($reader) {})->get();
			
			
			$indexed = 					['code', 'fieldrep_org_name', 'first_name', 'last_name', 'address1', 'address2', 'city', 'state', 'zip', 'phone', 'fax', 'ship_address1', 'ship_address2', 'ship_city', 'ship_state', 'ship_zip', 'notes'];
			$validate_indexed = ['Code', 'Organization Name', 'First Name', 'Last Name', 'Address',  'Address2', 'City', 'State', 'Zip Code', 'Phone', 'Fax', 'ship_address1', 'ship_address2', 'ship_city', 'ship_state', 'ship_zip', 'Notes'];

			if($data->count() == 0){
				$res['error_status'] = true;
				$res['err'][]['message'] = 'No data available to import.';
				$response['error'] = $res;
				return $response;
			}
			
			if(count($indexed) != count(array_first($data->toArray()))){
				$res['error_status'] = true;
				$res['err'][]['message'][] = 'Number of columns and order must be same as preview format';
				$response['error'] = $res;
				return $response;
			}

			$arr = array_map(function($v, $k) use($validate_indexed, $indexed){
				$arr['validating_arr'] = array_combine($validate_indexed, $k);
				$arr['contact_arr'] = array_combine($indexed, $k);
				$arr['contact_arr']['zipcode'] = $arr['contact_arr']['zip'];
				$arr['contact_arr']['phone_number'] = $arr['contact_arr']['phone'];
				$arr['contact_arr']['entity_type'] = 5;
				$arr['contact_arr']['contact_type'] = 'Primary';
				unset($arr['contact_arr']['zip']);
				unset($arr['contact_arr']['phone']);

				$arr['org_arr']['details']['fieldrep_org_name'] = $arr['contact_arr']['fieldrep_org_name'];
				$arr['org_arr']['details']['notes'] = $arr['contact_arr']['notes'];
				$arr['org_arr']['contact'] = $arr['contact_arr'];

				unset($arr['org_arr']['contact']['code']);
				unset($arr['org_arr']['contact']['name']);			
				unset($arr['org_arr']['contact']['fax']);
				unset($arr['org_arr']['contact']['ship_address1']);
				unset($arr['org_arr']['contact']['ship_address2']);
				unset($arr['org_arr']['contact']['ship_city']);
				unset($arr['org_arr']['contact']['ship_state']);
				unset($arr['org_arr']['contact']['ship_zip']);
				unset($arr['org_arr']['contact']['notes']);

				return $arr;
			}, $data->toArray(), array_values($data->toArray()));

			$org_datas = array_pluck($arr, 'org_arr');
			array_unshift($org_datas,"");
			array_unshift($org_datas,"");
			unset($org_datas[0]);
			unset($org_datas[1]);
			
			$validate_data = array_pluck($arr, 'validating_arr');
			array_unshift($validate_data,"");
			array_unshift($validate_data,"");
			unset($validate_data[0]);
			unset($validate_data[1]);

			
			$validator =  Validator::make($validate_data, [
				'*.Organization Name' => 'required',
				'*.First Name' 				=> 'required',
				'*.Last Name' 				=> 'required',
				'*.Address'						=>	'required',
				'*.City'							=>	'required',
				'*.State'							=>	'required',
				'*.Zip Code'					=>	'required|numeric_with_arr',
				'*.Phone'							=>	'regex:/^(?! )[[0-9]{3}-[0-9]{3}-[0-9]{4}]*$/', //numeric_with_arr',
				],[
				'*.Organization Name.required' 	=> "Organization Name is required",
				'*.First Name.required' 				=> "First Name is required",
				'*.Last Name.required' 					=> "Last Name is required",
				'*.Address.required' 						=> "Address is required",
				'*.City.required' 							=> "City is required",
				'*.State.required' 							=> "State is required",
				'*.Zip Code.required' 					=> "ZipCode required",
				'*.Zip Code.numeric_with_arr' 	=> "ZipCode must be a number",
				'*.Phone.regex' 								=> "Phone number has invalid format",
				// '*.Organization Name.required' => "Error at row :Attribute is required",
				]);

			if ($validator->fails() || $res['error_status'] == true) {
				$messages = $validator->errors()->toArray();
				if(count($messages) > 0){
					$res['error_status'] = true;

					foreach ($messages as $key => $message) {
						$row_number = $row_nums[] = explode('.', $key)[0];
						$res['err'][$row_number]['row_number'] = $row_number;
						unset($org_datas[$row_number]);
						if(is_array($message)){
							foreach ($message as $msg_key => $msg) {
								$res['err'][$row_number]['message'][] = $msg;
							}
						}else{
							$res['err'][$row_number]['message'][] = $message;
						}
					}

				}
				$response['error'] = $res;
				// $res['error_status'] = true;
				// $res['err'][]['message'] = $validator->errors()->all();
				// return $res;
			}


			foreach($org_datas as $key => $org_data){

				$response['success_records'][] = $key - 2;
				
				$referance_id = FieldRep_Org::create($org_data['details'])->id;
				$org_data['contact']['reference_id'] = $referance_id;
				Contact::create($org_data['contact']);
			}
			return $response;
		}
	}
}
