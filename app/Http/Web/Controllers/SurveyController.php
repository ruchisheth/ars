<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Crypt;
use Validator;

use App\surveys;
use App\Assignment;
use App\Round;
use App\Project;
use App\FieldRep;
use App\User;
use App\Admin;
use App\Emailer;
use Exception;
use App\survey_template;
use App\surveys_template;


use App\Http\Requests;
use App\Http\AppHelper;
use Illuminate\Support\Facades\Input;
use App\Exceptions\SurveyAccessException;
use App\Exceptions\SurveyNotAvailableException;
use Form;
use App\Site;
use Datatables;
use Session;
// use ARS;

class SurveyController extends Controller
{
	public function __construct(){

		parent::__construct();
		$this->middleware('auth', [
			'only' => [
				'callShowAssignmentList',
			]
		]);
	}
	public function index(){

		return view('builder.home');
	}

	public function edit($template_id){
		$template_in_use = false;

		$template = surveys_template::findorFail($template_id);

		$survey_count = surveys::where(['template_id' => $template_id])->count();
		if($survey_count > 0){
		// $survey_count = surveys::where(['template_id' => $template_id])->get();
		// 	if(!survey_count->isEmpty()){
			$template_in_use = true;
		}

		return view('builder.home',compact('template', 'template_in_use'));
	}

	public function PostElement($type){
		$elementhtml = "";
		$data = [
			'type'=>$type
		];
		$elementhtml = view('builder.single-items.elements',$data)->render(); 
		return response()->json(array(
			"status" => "success",
			"elementHTML"=>$elementhtml,
		));
	}

	public function storeTemplateDetail(Request $request){
		Input::merge(array_map('trim', Input::all()));

		$this->validate($request, [
			"template_name"   =>  "required|unique:surveys_templates,template_name,$request->id,id",
		],[
			'template_name.unique' => 'Template Name has already been taken',
		]);
		$inputs = $request->all();
		$template = surveys_template::find($inputs['id']);
		$template->update(['template_name' => $inputs['template_name']]);

		return response()->json(array(
			"status" => "success",
			"message"=>'Template Details Changed Successfully!',
		));

	}

	public function validateTemplate(Request $request){

		//Input::merge(array_map('trim', Input::all()));
		$this->validate($request, [
			"template_name"   =>  "required|unique:surveys_templates",
		],[
			'template_name.unique' => 'Template Name has already been taken',
		]);

		return response()->json(array(
			"status" => "success",
		));
	}

	public function PostTemplate(Request $request){

		$type = $request->input('type');
		$question_details = json_decode($request->get('question_details'),true);
		$question_details = serialize($question_details);

		if($type=='template'){
			$template_id = $request->input('id');
			$inputs = $request->all();
			
			// $dt = json_decode($request->question_details);
			// array_unshift($dt,"");
			// unset($dt[0]);
			// dd($dt);

			$inputs['questions_data'] = $question_details;
			$inputs['template'] = trim($inputs['template']);

			if($template_id == ""){
				$inputs['template_name'] = trim($inputs['name']);
				$newTemplate = surveys_template::create($inputs);
			}
			else{
				$survey = surveys::where(['template_id' => $template_id])->get();
				if(!$survey->isEmpty()){
					return response()->json(array(
						"message"=>'You can not make changes to Template as it is being used!',
					),422);
				}
				$oldTemplate = surveys_template::find($template_id);
				$oldTemplate->update(['template' => $inputs['template'], 'questions_data' => $inputs['questions_data']]);
				//$oldTemplate->update(['template' => $inputs['template'],'template_name' => $inputs['template_name']]);
			}
		}else if($type=='survey'){
			$Survey = surveys::where(['id'=>$request->input('id')])->first();
			$inputs = $request->only(['template','filled_surveydata','status']);
			$filled_surveydata = $Survey->filled_surveydata;

			if($request->input('status') == 2){
				$filled_surveydata = trim($inputs['filled_surveydata']);
			}
			$inputs['filled_surveydata'] = $filled_surveydata;
			$inputs['template'] = trim($inputs['template']);

			$Survey->update([
				'surveydata'				=>	$inputs['template'],
				'filled_surveydata' => 	$inputs['filled_surveydata'],
				'status'						=>	$inputs['status']]);
		}
		return response()->json(array(
			"status" => "success",
			"message"=>'Template Saved Successfully!',
		));
	}
	public function GetSurvey(Request $requests, $nIdSurvey, $client_code){

		$oLoggedInUser = Auth::user();

		if($oLoggedInUser->user_type == config('constant.USERTYPE.ADMIN')){
			ARS::canORFail('constants.PERMISSIONS.SURVEY.EDIT');
		}

		try {
			$nIdSurvey = Crypt::decrypt($nIdSurvey);
			$sClientCode = base64_decode($client_code);
		} catch (DecryptException $e) {
			$nIdSurvey = $nIdSurvey;
		}


		if($sClientCode != $oLoggedInUser->client_code){
			throw new SurveyAccessException(trans('messages.survey_access_denied'),1);
		}

		$oSurvey = surveys::findorFail($nIdSurvey);
		$oAssignment = Assignment::find($oSurvey->assignment_id);

		if(!$oAssignment->isSurveyAvailable() && !$oAssignment->is_partial  && !$oAssignment->is_reported && !$oAssignment->is_approved)
		{
			throw new SurveyNotAvailableException();
		}

		if($oAssignment->is_partial && Auth::user()->hasrole('admin')){
			throw new SurveyNotAvailableException();
		}

		$oSurveyDetails = (object)$oSurvey->getSurveyDetail($oSurvey);

		$schedule_datetime = $oAssignment->getAssignmentScheduleDateTime();
		$oSurveyDetails->schedule = $schedule_datetime;

		$aViewData = [
			'id' => $nIdSurvey,
			'survey_template' => $oSurvey,
			'survey_details'  => $oSurveyDetails,
		];

		return \View::make('WebView::fieldrep.surveys.fill_survey', $aViewData);
	}

	public function validateSurveyData(Request $request){
		//$inputs = $request->only(['template','status','KeyPairs']);

		$inputs = $request->all();
		$KeyPairs = json_decode($inputs['KeyPairs'],true);

		array_unshift($KeyPairs,"");
		unset($KeyPairs[0]);
		
		$rules = array();
		$message = array();

		foreach ($KeyPairs as $index => $KeyPair) {

			// $que_no = $index + 1;
			$que_no = $index;

			//$que_no = intval(preg_replace('/[^0-9]+/', '', $KeyPair['que_no']));
			if($KeyPair['validation']['required']){
				if($KeyPair['type'] == 'file'){
					if($KeyPair['ans'] == ''){
						$rules['name_'.$que_no.'.*'] = 'required';
						$message['name_'.$que_no.'.*.required'] = 'Answer for Question '.$que_no.' is required.';
					}
				}else{
					$rules[$KeyPair['que_no']] = "required";
					$message[$KeyPair['que_no'].'.required'] = 'Answer for Question '.$que_no.' is required.';

				}
			}
		}
		$this->validate($request,$rules,$message);

		return response()->json(array(
			"status" => "success",
		));

	}

	public function PostSurvey(Request $request){
		$oLoggedInUser= Auth::user();
		$inputs = $request->only(['template','status','KeyPairs']);
		$KeyPairs = json_decode($inputs['KeyPairs'],true);
		array_unshift($KeyPairs,"");
		unset($KeyPairs[0]);

		$rules = array();
		$message = array();

		$count = count($KeyPairs);
		


		$files = array();
		$id = $request->input('id');
		$destinationPath = config('constants.SURVEYFOLDERURL').strtoupper($oLoggedInUser->client_code).'/';
		$result = \File::makeDirectory($destinationPath.$id,0775,true,true);
		chmod($destinationPath.$id,0777);
		//$destinationPath = $destinationPath.$id.DIRECTORY_SEPARATOR;
		$destinationPath = $destinationPath.$id."/";


		/*convert date format*/
		foreach ($KeyPairs as $arr_key => $arr_val) {
			if($arr_val['type'] == 'date'){
				$date = $arr_val['ans'];
				if($date != ''){
					//$date = AppHelper::convertTimeToUTC($date,AppHelper::DATE_DISPLAY_FORMAT);
					$date = date_formats($date, config('constants.DATEFORMAT.DATESAVE'));
				}
				$KeyPairs[$arr_key]['ans'] = $date;
			}
		}

		$Survey = surveys::where(['id'=>$id])->first();

		$s_keyparirs = unserialize($Survey->keypairs);
		// array_unshift($KeyPairs,"");
		// unset($KeyPairs[0]);

		for($i=1;$i<=$count;$i++){
			$name = 'name_'.$i.'';
			$iamge_name_prefix = $name.'_';


			// if($request->status == 2 && $request->hasFile($name) == false && $request->has($name) == false){
			//delete old uploaded files if submit survey is clicked.
			if($request->status == 2 ){
				if($request->hasFile($name) == false && $request->has($name) == false){
					$saved_image = $s_keyparirs[$i]['ans'];
					
					if($saved_image != ''){
						$saved_image = basename($saved_image);
						$old_file_list = \File::glob($destinationPath.$name.'_*');
						foreach($old_file_list as $old){
							if(basename($old) == $saved_image){
								continue;
							}
							\File::delete($old);
						}
					}else{
						$old_file_list = \File::glob($destinationPath.$name.'_*');
						foreach($old_file_list as $old){
							if(basename($old) == $saved_image){
								continue;
							}
							\File::delete($old);
						}
					}
				}
			}

			// check if control has file uploaded
			// if survey is submitted delete all previous file for control and upload the new one
			if(!$request->has($name) && $request->hasFile($name)){
				if($request->status == 2){
					$old_file_list = \File::glob($destinationPath.$name.'_*');

					foreach($old_file_list as $old){
						\File::delete($old);
					}
				}
				$image_files = $request->file($name);
				$KeyPairs[$i]['ans'] = array();

				foreach ($image_files as $file) {
					if($file->isValid()){
						$extension = strtolower($file->getClientOriginalExtension());
						if(getFileType($extension) == 'image'){
							$encrypted_name = $iamge_name_prefix.md5(uniqid().time()).".".$extension;
							$ImagePath = $file->getRealPath();
							$canvas = \Image::canvas(600,600);
							$image = \Image::make($ImagePath)->resize(600,600, function($constraint) {
								$constraint->aspectRatio();
							}); 
							$canvas->insert($image,'center');
							$canvas->save($destinationPath.$encrypted_name,'70');
							$image_data['name'] = $file->getClientOriginalName();
							$image_data['input_name'] = $name;
							$image_data['encrypted_name'] = URL($destinationPath.$encrypted_name);
							$image_data['id'] = $id;

							$files[$name][] = $image_data;
						//$KeyPairs[$i]['ans'][] = $image_data['encrypted_name'];
							$KeyPairs[$i]['ans'][] = $encrypted_name;
						}elseif($extension == 'txt' || $extension == 'pdf'){
							$image_data = UploadFile($file,$destinationPath,$name.'_');
							$image_data['input_name'] = $name;
							$encrypted_name = $image_data['encrypted_name'] = URL($destinationPath.$image_data['encrypted_name']);
							$image_data['id'] = $id;
							$KeyPairs[$i]['ans'][] = $encrypted_name;

							$files[$name][] = $image_data;
						}
					}
				}
				$KeyPairs[$i]['ans'] = implode(',', $KeyPairs[$i]['ans']);
			}else if($KeyPairs[$i]['ans'] == 'default.png'){ // if selected file removed.

				if($request->status == 2){
					$old_file_list = \File::glob($destinationPath.$name.'_*');

					foreach($old_file_list as $old){
						\File::delete($old);
					}
				}

				$image_data['name'] = '';
				$image_data['input_name'] = '';
				$image_data['encrypted_name'] = '';
				$image_data['id'] = '';
				$files[$name][] = $image_data;
				$KeyPairs[$i]['ans'] = '';
			}
		}
		

		$id = $request->input('id');

		//$Survey = surveys::where(['id'=>$id])->first();
		$Assignment = Assignment::where(['id'=>$Survey->assignment_id])->first();

		$inputs['template'] = trim(($inputs['template']));


		//$inputs['KeyPairs'] = serialize(json_decode($inputs['KeyPairs'],true));
		$inputs['KeyPairs'] = serialize($KeyPairs);

		$data_to_update = [
			'surveydata'=>$inputs['template'],
			'status'=>$inputs['status'],
			'keypairs'=>$inputs['KeyPairs'],
		];

		if($request->has('name_service_code'))
		{
			$data_to_update['service_code'] = $request->get('name_service_code');
		}

		$Survey->update($data_to_update);
		//$Survey->update(['surveydata'=>$inputs['template'],'status'=>$inputs['status'],'keypairs'=>$inputs['KeyPairs']]);

		if($inputs['status'] == 2){

			$Assignment->markAsReported();

		}
		$round_id = $Assignment->round_id;
		$round = Round::find($round_id);
		$details['round_name'] = $round->round_name;
		$project_id = $round->project_id;
		$project = Project::find($project_id);
		$details['project_name'] =$project->project_name;
		$details['site'] = $Assignment->getAssignmentLocation();
		//$details['fieldrep_name'] = Auth::user()->UserDetails->getFullName();
		$fieldrep = FieldRep::find($Assignment->fieldrep_id);
		$details['fieldrep_name'] = $fieldrep->getFullName();
		//$user = User::find($fieldrep->user_id);
		$user = User::find(Auth::user()->UserDetails->user_id);
		
		$oAdmin = Admin::where(['client_code' => $oLoggedInUser->client_code])->first();
		$details['client_email'] = $oAdmin->email;
		$details['assignment_code'] = $Assignment->sites->site_code;


		if($Assignment && $Survey){
			$data = array(
				'details'=>$details,
			);
			Emailer::SendEmail('fieldrep.submit_survey',$data);
		}
		

		//return redirect()->back()->with(['success'=>'Data Saved']);
		//return redirect()->back()->with(['success'=>'Data Saved','files'=>json_encode($files)]);
		return redirect()->back()->withInput()->with(['success'=>'Data Saved','files'=>json_encode($files)]);
	}

	public function DeleteFile(Request $request){
		$id = $request->input('id');
		$destinationPath = AppHelper::SURVEY_UPLOAD.$id.DIRECTORY_SEPARATOR;
		$name = $request->input('name');
		$old_file_list = \File::glob($destinationPath.$name.'_*');
		foreach($old_file_list as $old){
			\File::delete($old);
		}
		return response()->json(array(
			"status" => "success",
			"file" => $old_file_list,
		));
	}

	public function show(){
		$res = parent::isDataAvailable('Survey Template','form.builder');
		if($res === true){
			return view('admin.surveys.templates');
		}
		return $res;
	}

	public function destroy(Request $request){
		try{
			$template_id = $request->input('id');
			surveys_template::where(['id'=>$template_id])->delete();

			return response()->json(array(
				"status" => "success",
				"message"=>"Template removed successfully!",
			));
		}
		catch(Exception $e){
			if($e instanceof \PDOException )
			{
				$error_code = $e->getCode();
				if($error_code == 23000){
					$message = "Survey Template can't be deleted, it's in use for Round or Survey";
					return response()->json([ 'message' => $message ], 422);
				}
			}
		}
	}

	public function createQuestionTag(Request $request, $id){
		$template = surveys_template::findOrFail($id);
		$data['template'] = $template;
		return view('builder.question_tags', $data);
	}

	public function storeQuestionTag(Request $request){
		$template_id = base64_decode($request->template_id);
		$template = surveys_template::findOrFail($template_id);
		$tags = NULL;
		$tags = $request->questions;
		$tags = array_filter($tags);
		if($tags != NULL){
			$tags = serialize($tags);
			$template->update(['question_tags' => $tags]);
		}

		return redirect(url('/templates'))->with(['success'=>'Question Tags has been saved']);
		
	}

	public function getQuestionTag(Request $request){
		$id = $request->template_id;
		$template = surveys_template::findOrFail($id);
		$question_data = $template->questions_data;
		$question_data = unserialize($question_data);
		$question_data = array_filter(array_merge(array(0), $question_data));

		$question_tags = NULL;
		if($template->question_tags != NULL){
			$question_tags = unserialize($template->question_tags);
		}

		// dd($question_tags);


		foreach($question_data as $qkey => $question){
			$temp_arr = ['id' => $qkey, 'question' => "Q.".$qkey." ".$question['ques']];
			if($question_tags != NULL && array_key_exists($qkey, $question_tags)){
				$temp_arr['tag'] = $question_tags[$qkey];
			}
			else{
				$temp_arr['tag'] = '';
			}
			$questions[] = $temp_arr;
		}

		$questions = collect($questions);


		$datatables = Datatables::of($questions)
		->editColumn('name', function($questions){
			return '<input type="text" class="form-control" name="questions['.$questions['id'].']" placeholder="'.$questions['question'].'" value="'.$questions['tag'].'">';
		});
		return $datatables->make(true);
	}

	public function getdata(){

		$templates = surveys_template::all();


		$datatables = Datatables::of($templates)
		->addColumn('action', function ($templates) {
			$html = "";
			$html .= '<a href="'.route('get.question.tags', ['id' => $templates->id]).'" class="btn btn-box-tool" name="" data-id="'.$templates->id.'" title="Manage Question Tag"><span class="fa fa-tags"></span></a>';
			
			$html .= '<button class="btn btn-box-tool" type="submit" name="remove_template" data-id="'.$templates->id.'" value="delete" title="delete"><span class="fa fa-trash"></span></button>';
			return $html;
		})
		->editColumn('id', function ($templates) {
			return '<a href='.url("/survey-template-edit/").'/'.$templates->id.'>'. format_code($templates->id).'</a>';

		});
		return $datatables->make(true);
	}


	public function callShowSurvey(Request $oRequest, $nIdSurvey)
	{
		$oSurvey = surveys::findorFail($nIdSurvey);
		$oAssignment = Assignment::getAssignmentById($oSurvey->assignment_id);
		if(!$oAssignment->is_reported && !$oAssignment->is_partial){
			// throw new SurveyNotAvailableException();
			abort(404);
		}

		$oSurveyDetails = (object)$oSurvey->getSurveyDetail($oSurvey);

		$aSurveyQuestions = unserialize($oSurvey->keypairs);

		$oViewData = [
			'oSurvey'               => $oSurvey,
			'oAssignment'           => $oAssignment,
			'oSurveyDetails'        => $oSurveyDetails,
			'aSurveyQuestions'      => $aSurveyQuestions,
		];

		return \View::make('WebView::fieldrep.surveys.show_survey', $oViewData);

	}
}
