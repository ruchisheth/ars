<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\AppData;
use App\_List;
use App\Contact;
use App\Chain;
use App\Site;
use App\Http\AppHelper;
use Validator;
use Excel;
use DB;
use App\ContactType;

class Client extends Model
{
	protected $primaryKey = 'id';

	protected $table = 'clients';
	
	protected $fillable = ['id_user', 'client_code', 'client_name', 'client_abbrev', 'notes', 'client_logo', 'client_logo_name', 'status'];
	
	public function user()
    {
        return $this->belongsTo('App\User', 'id_user');
    }

	public function contacts()
	{
		return $this->hasMany(Contact::class,'reference_id')->where('entity_type',1);
	}

	public function chains()
	{
		return $this->hasMany(Chain::class,'client_id');
	}

	public function projects()
	{
		return $this->hasManyThrough(
			'App\Project', 'App\Chain',
			'client_id', 'chain_id', 'id'
			);
	}

	public function importData($request)
	{
		if(Input::hasFile('importfile')){
			$res['error_status'] = false;

			$response['error'] = $res;
			$response['success_records'] = [];

			$path = Input::file('importfile')->getRealPath();
			$data = Excel::load($path, function($reader) {})->get();
			$clients = [];

			$indexed = 					['client_name','first_name', 'last_name', 'address1', 'address2',  'city','state','zip','phone','notes'];
			$validate_indexed = ['Client Name','First Name', 'Last Name', 'Address',  'Address 2', 'City','State','Zip Code','Phone', 'Notes'];

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
				//return $res;
			}

			$arr = array_map(function($v, $k) use($validate_indexed, $indexed){
				$arr['validating_arr'] = array_combine($validate_indexed, $k);
				$arr['contact_arr'] = array_combine($indexed, $k);
				$arr['contact_arr']['zipcode'] = $arr['contact_arr']['zip'];
				$arr['contact_arr']['phone_number'] = $arr['contact_arr']['phone'];
				$arr['contact_arr']['entity_type'] = 1;
				$arr['contact_arr']['contact_type'] = 'Primary';

				$arr['client_arr']['details']['client_name'] = $arr['contact_arr']['client_name'];
				$arr['client_arr']['details']['notes'] = $arr['contact_arr']['notes'];
				unset($arr['contact_arr']['client_name']);
				unset($arr['contact_arr']['zip']);
				unset($arr['contact_arr']['phone']);
				unset($arr['contact_arr']['notes']);
				$arr['client_arr']['contact'] = $arr['contact_arr'];
				return $arr;
			}, $data->toArray(), array_values($data->toArray()));

			$clients_data = array_pluck($arr, 'client_arr');
			
			$validate_data = array_pluck($arr, 'validating_arr');
			array_unshift($validate_data,"");
			array_unshift($validate_data,"");
			unset($validate_data[0]);
			unset($validate_data[1]);
			
			array_unshift($clients_data,"");
			array_unshift($clients_data,"");
			unset($clients_data[0]);
			unset($clients_data[1]);

			
			$validator =  Validator::make($validate_data, [
				'*.Client Name' => 'required',
				'*.First Name' => 'required',
				'*.Last Name' => 'required',
				'*.Address'	=>	'required',
				'*.City'	=>	'required',
				'*.State'	=>	'required',
				'*.Zip Code'	=>	'required|numeric_with_arr',
				'*.Phone'	=>	'regex:/^(?! )[[0-9]{3}-[0-9]{3}-[0-9]{4}]*$/',
				],[
				'*.Client Name.required' 			=> "Client Name is required",
				'*.First Name.required' 			=> "First Name is required",
				'*.Last Name.required' 				=> "Last Name is required",
				'*.Address.required' 					=> "Address is required",
				'*.City.required' 						=> "City is required",
				'*.State.required' 						=> "State is required",
				'*.Zip Code.required' 				=> "Zip Code is required",
				'*.Zip Code.numeric_with_arr' => "Zip Code must be a number",
				// '*.Phone.required'				 		=> "Phone is required",
				'*.Phone.regex' 						=> "Phone number has invalid format",
				// '*.Client Name.required' => "Error at row :Attribute is required",
				]);

			if ($validator->fails() || $res['error_status'] == true) {
				$messages = $validator->errors()->toArray();
				if(count($messages) > 0){
					$res['error_status'] = true;

					foreach ($messages as $key => $message) {
						$row_number = $row_nums[] = explode('.', $key)[0];
						$res['err'][$row_number]['row_number'] = $row_number;
						unset($clients_data[$row_number]);
						if(is_array($message)){
							foreach ($message as $msg_key => $msg) {
								$res['err'][$row_number]['message'][] = $msg;
							}
						}else{
							$res['err'][$row_number]['message'][] = $message;
						}
					}

				}
				$response['error']  =  $res;
				
				//return $res;
			}
			if(!empty($clients_data)){
				//$response['success_records'] = array_keys($clients_data);
				
				foreach($clients_data as $key => $cd){

					$response['success_records'][] = $key - 2;

					$referance_id = Client::create($cd['details'])->id;
					$cd['contact']['reference_id'] = $referance_id;
					Contact::create($cd['contact']);

				}
			}
			return $response;
		}
	}
}
