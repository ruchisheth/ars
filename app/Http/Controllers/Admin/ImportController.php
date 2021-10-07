<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Validator;
use App\AppData;

use App\Client;
use App\Chain;
use App\Site;
use App\FieldRep;
use App\FieldRep_Org;
use App\Contact;
use App\Project;
use App\Setting;
use App\_List;
use App\PrefBan;
use App\Assignment;
use DB;
use Excel;
use Datatables;


class ImportController extends Controller
{
	public function index(){

		return view('admin.imports.imports');
	}

	public function Import(Request $request)
	{
		// \Debugbar::disable();
		$this->validate($request, [
			"importfile"   =>  "required",
			]);

		$path = Input::file('importfile')->getRealPath();
		$data = Excel::load($path, function($reader) {})->get();


		$modelName = $request->input('entity');

		$obj = app()->make("App\\".$modelName);
		$res = null;
		$response = $obj->importData($request);
		$error_records = array_diff_key($data->toArray(), array_flip($response['success_records']));
		$res = $response['error'];

		
		if($res['error_status'] == true){
			$message = '';
			if(count($res['err'] > 0)){
				foreach($res['err'] as $key => $error){
					if(isset($error['row_number'])){
						$error_records[$error['row_number'] - 2]['error'] = "";
						foreach($error['message'] as  $err_message){
							$error_records[$error['row_number'] - 2]['error'] .= rtrim($err_message, '.').'. ';
						}
					}else{
						if(is_array($error['message'])){
							foreach($error['message'] as  $err_message){
							//$message .= $error['message'];
								$message .= $err_message."<br>";
							}
						}else{
							$message .= $error['message'];
						}
					}
				}

				if($message == ""){
					$file_path = 'storage/exports/';
					$file_name = strtolower($modelName).'_'.time();
				
					Excel::create($file_name,function($excel) use ($error_records){
						$excel->sheet('Sheet 1',function($sheet) use ($error_records){
							$sheet->fromArray($error_records);
						});
					})->store('csv');

					return response()->json([ 
						'error_log_file' 	=> $file_path.$file_name.'.csv',
						'message' 				=> 'You have an error in your uploaded data. Please check the error log file. <br>After correcting the issues mentioned in "error" column please remove "error" column before reuploading the file.'
						],422);

				}else{

					return response()->json([ 'message' => $message ], 422);
				}
			}
		}else{
			return response()->json(array(
				"status" => "success",
				"message"=>"Data Imported Successfully",
				)); 
			
		}
		// \Debugbar::enable();
	}
}
