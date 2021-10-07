<?php

namespace App\Http\Controllers\Builder;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\AppHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Exceptions\SurveyAccessException;
use App\Exceptions\SurveyNotAvailableException;
use Form;
use Exception;
use App\surveys_template,
Validator,
App\surveys,
App\survey_template,
App\Assignment,
App\Round,
App\Project,
App\Site,
App\FieldRep,
App\User,
App\Emailer,
Datatables,
Crypt,
Auth;

class FormBuilderController extends Controller
{
    //
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

	public function PostTemplate(Request $oRequest){
        
		$type = $oRequest->input('type');
		$question_details = json_decode($oRequest->get('question_details'),true);
		$question_details = serialize($question_details);

		if($type=='template'){
			$nIdTemplate = $oRequest->input('id');
			$inputs = $oRequest->all();

			$inputs['questions_data'] = $question_details;
			$inputs['template'] = trim($inputs['template']);

			if($nIdTemplate == ""){
			    if($oRequest->copy_from != ''){
			        $oExistingTemplate = surveys_template::find($oRequest->copy_from);
			        $inputs['question_tags'] = $oExistingTemplate->question_tags;
			    }
				$inputs['template_name'] = trim($inputs['name']);
				$newTemplate = surveys_template::create($inputs);
			}
			else{
				$survey = surveys::where(['template_id' => $nIdTemplate])->get();
				if(!$survey->isEmpty()){
					return response()->json(array(
						"message"=>'You can not make changes to Template as it is being used!',
						),422);
				}
				$oldTemplate = surveys_template::find($nIdTemplate);
				$oldTemplate->update(['template' => $inputs['template'], 'questions_data' => $inputs['questions_data']]);
				//$oldTemplate->update(['template' => $inputs['template'],'template_name' => $inputs['template_name']]);
			}
		}else if($type=='survey'){
			$Survey = surveys::where(['id'=>$oRequest->input('id')])->first();
			$inputs = $oRequest->only(['template','filled_surveydata','status']);
			$filled_surveydata = $Survey->filled_surveydata;

			if($oRequest->input('status') == 2){
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
	public function GetSurvey(Request $requests,$id,$client_code){
		try {
			$id = Crypt::decrypt($id);
			$client_code = base64_decode($client_code);
		} catch (DecryptException $e) {
			$id = $id;
		}


		if($client_code != Auth::user()->client_code){
			throw new SurveyAccessException("You don't have Permission to Access this Survey",1);
		}

		$OldSurvey = surveys::findorFail($id);
		$assignment = Assignment::find($OldSurvey->assignment_id);

		if(!$assignment->isSurveyAvailable() && !$assignment->is_partial  && !$assignment->is_reported && !$assignment->is_approved)
		{
			throw new SurveyNotAvailableException();
		}

		if($assignment->is_partial && Auth::user()->hasrole('admin')){
			throw new SurveyNotAvailableException();
		}



		$survey_details = (object)$OldSurvey->getSurveyDetail($OldSurvey);

		$schedule_datetime = $assignment->getAssignmentScheduleDateTime();
		$survey_details->schedule = $schedule_datetime;
		$CreatedTemplate = $OldSurvey;


		$data = [
		'id'=>$id,
		'survey_template'=>$CreatedTemplate,
		'survey_details'    =>  $survey_details,
		];
		return view('builder.fill_survey',$data);
	}

	public function validateSurveyData(Request $request){
		//$inputs = $request->only(['template','status','KeyPairs']);

		$inputs = $request->all();
		$KeyPairs = json_decode($inputs['KeyPairs'],true);

		array_unshift($KeyPairs,"");
		unset($KeyPairs[0]);
		
		$rules = [];
		$message = [];
    
		foreach ($KeyPairs as $index => $aQuestion) 
		{
			$nQuestionNo = $index;
			$rules[$aQuestion['que_no']] = '';
			foreach ($aQuestion['validation'] as $sValidation => $sValidationValue) 
			{
				if($sValidationValue != false)
				{
					if($aQuestion['type'] == 'file' && $sValidation == 'required')
					{
						if($aQuestion['ans'] == ''){
							$rules['name_'.$nQuestionNo.'.*'] = $sValidation;
							$message['name_'.$nQuestionNo.'.*.'.$sValidation] = 'Question'.$nQuestionNo. ' - Answer for Question '.$nQuestionNo.' is required.';
						}
					}
					else
					{
						$rules[$aQuestion['que_no']] = $rules[$aQuestion['que_no']].$sValidation.'|';
						if($sValidation == 'required'){
							$message[$aQuestion['que_no'].'.'.$sValidation] = 'Answer for Question '.$nQuestionNo.' is required.';		
						}else if($sValidation == 'numeric'){
							$message[$aQuestion['que_no'].'.'.$sValidation] = 'Only numeric value allowed in Question'.$nQuestionNo;
						}
					}
				}
			}
			$rules[$aQuestion['que_no']] = trim($rules[$aQuestion['que_no']], '|');
		}
		$this->validate($request,$rules,$message);

		return response()->json(array(
			"status" => "success",
			));

	}

	public function PostSurvey(Request $request){
	    
        ini_set('upload_max_filesize','20971520M');
		$inputs = $request->only(['template','status','KeyPairs']);
		$KeyPairs = json_decode($inputs['KeyPairs'],true);
		array_unshift($KeyPairs,"");
		unset($KeyPairs[0]);

		$rules = array();
		$message = array();

		$count = count($KeyPairs);
		


		$files = array();
		$id = $request->input('id');
		$destinationPath = AppHelper::SURVEY_UPLOAD.strtoupper(Auth::user()->client_code).'/';
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
					$date = date_formats($date,AppHelper::DATE_SAVE_FORMAT);
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
				//$image_files = $file = Input::file($name);
				$KeyPairs[$i]['ans'] = array();

				foreach ($image_files as $file) {
					if($file->isValid()){
						$extension = strtolower($file->getClientOriginalExtension());
						if(AppHelper::getFileType($extension) == 'image'){
							$encrypted_name = $iamge_name_prefix.md5(uniqid().time()).".".$extension;
							$ImagePath = $file->getRealPath();
							
							$canvas = \Image::canvas(600,600);
							    try{
							        $image = \Image::make($ImagePath)->resize(600,600, function($constraint) {
								        $constraint->aspectRatio();
							        });
							        $canvas->insert($image,'center');
							        $canvas->save($destinationPath.$encrypted_name,'70');
							    }catch(Exception $e){
							        $file->move($destinationPath,$encrypted_name);
							    }
							
							
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
		$admin = User::where('role',2)->where('db_version',$user->db_version)->first();
		$details['client_email'] = $admin->email;
		$details['assignment_code'] = $Assignment->sites->site_code;


		if($Assignment && $Survey){
			$data = array(
				'details'=>$details,
				);
			//Emailer::SendEmail('fieldrep.submit_survey',$data);
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

		$templates = surveys_template::get(['id', 'template_name']);


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
}
