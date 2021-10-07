<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\AppHelper;
use DB;
use File;
use Datatables;
use App\Round;
use App\Assignment;
use App\Instruction;
use App\AssignmentsInstruction;
use Illuminate\Support\Facades\Input;

class InstructionController extends Controller
{
	public function store(Request $request)
	{
		$this->validate($request, [
			'is_default' => 'unique_with:instructions,round_id=>'.$request->input('round_id').',id,'.$request->input('instruction_id'),
			"instruction_name"   =>  "required",
			"instruction"   =>  "required_without:offer_instruction",
			//"offer_instruction"   =>  "required_without:instruction",
			],[
			'is_default.unique_with'  =>  'There can be only one Default Instruction',
			]);
		if($request->input('instruction_id') == '' || $request->input('instruction_id') == '0')
		{
			$instruction = new Instruction($request->except(['_token','attachment','offer_attachment','offer_instruction_name']));
			$destinationPath = AppHelper::INSTRUCTION_IMG;
			if(Input::hasFile('attachment'))
			{
				$files = $request->file('attachment');

				foreach($files as $file)
				{
					if($file->isValid())
					{
						$attachment_names[] = upload_file($destinationPath,$file);
					}
				}
				$attachments = serialize($attachment_names);
				$instruction->attachment = $attachments;
			}

			if(Input::hasFile('offer_attachment'))
			{
				$offer_files = $request->file('offer_attachment');

				foreach($offer_files as $file)
				{
					if($file->isValid())
					{
						$offer_attachment_names[] = upload_file($destinationPath,$file);
					}
				}

				$offer_attachments = serialize($offer_attachment_names);
				$instruction->offer_attachment = $offer_attachments;
			}
			$instruction->save();

			if($request->has('is_default')){
				$round = Round::find($request->get('round_id'));
				$assignments = $round->assignments;
				if(!$assignments->isEmpty())
				{
					foreach ($assignments as $assignment) {
						$data[] = ['instruction_id' => $instruction->id, 'assignment_id' => $assignment->id];
					}
					AssignmentsInstruction::insert($data);
				}
			}

			return response()->json(array(
				"status" => "success",
				"message"=>"Instruction Added Successfully!",
				));
		}else{
			$instruction = Instruction::where(['id'=>$request->input('instruction_id')])->first();
			$round_id = $instruction->round_id;

			$instruction->update($request->except(['_token','attachment','offer_attachment','offer_instruction_name']));

			if (!$request->has('is_default')) {
				$instruction->update(['is_default'=>false]);
			}

			$destinationPath =AppHelper::INSTRUCTION_IMG;

			$attachments = (unserialize($instruction->attachment)) ? unserialize($instruction->attachment) : [];

			$offer_attachments = (unserialize($instruction->offer_attachment)) ? unserialize($instruction->offer_attachment) : [];

			if(Input::hasFile('attachment'))
			{
				$files = $request->file('attachment');
				foreach($files as $file)
				{
					if($file->isValid())
					{
						$attachment_names = upload_file($destinationPath,$file);
						array_push($attachments, $attachment_names);
					}
				}
				$attachments = serialize($attachments);
				$instruction->update(['attachment'=>$attachments]);
			}

			if(Input::hasFile('offer_attachment'))
			{
				$offer_files = $request->file('offer_attachment');

				foreach($offer_files as $file)
				{
					if($file->isValid())
					{
						$offer_attachment_names = upload_file($destinationPath,$file);

						array_push($offer_attachments, $offer_attachment_names);
					}
				}

				$offer_attachments = serialize($offer_attachments);
				$instruction->update(['offer_attachment'=>$offer_attachments]);
			}

			if($request->has('is_default')){
				$round = Round::find($request->get('round_id'));
				$assignments = $round->assignments;

				if($assignments)
				{
					foreach ($assignments as $assignment) {
						$data[] = ['instruction_id' => $instruction->id, 'assignment_id' => $assignment->id];
					}
					AssignmentsInstruction::where(['instruction_id' => $instruction->id])->delete();
					AssignmentsInstruction::insert($data);
				}
			}

			return response()->json(array(
				"status" => "success",
				"message"=>"Instruction Saved Successfully!",
				));
		}
	}


	public function edit($instruction_id){

		$inputs = Instruction::find($instruction_id);

		$noReading = ['image', 'pdf', 'xls', 'doc'];
		$noPreview = ['image'];

		$attachments['src'] = $offer_attachments['src'] = [];
		$attachments['config'] = $offer_attachments['config'] = [];
		$url = AppHelper::APP_URL.'delete-attachment/'.$inputs->id;

		if($inputs->attachment != '')
		{
			$assignmentAttachments = unserialize($inputs->attachment);;
			foreach($assignmentAttachments as $key => $assignmentAttachment){
				$filepath = AppHelper::APP_URL.AppHelper::INSTRUCTION_IMG.$assignmentAttachment;
				$extension = File::extension($filepath);
				$fileType = AppHelper::getFileType($extension);
				
				if(in_array($fileType, $noReading)){
					$attachments['src'][] = $filepath;
				}else{
					$attachments['src'][] = File::get(AppHelper::INSTRUCTION_IMG.$assignmentAttachment);
				}
				$attachments['config'][] = ['type' => $fileType, 'filename' =>  $filepath, 'url'=> $url, 'key' => $key, 'showDelete' => true, 'extra'=> ['type' => 'attachment']];
				//if(in_array($fileType, $noPreview)){
					//$attachments['config'][] = ['type' => $fileType, 'height' => '20%', 'width' => '100%', 'url'=> $url,'key' => $key,'showDelete' => true,'showZoom' => false, 'extra'=> ['type' => 'attachment']];
				// }else{
				// 	$attachments['config'][] = ['type' => $fileType, 'height' => '20%', 'width' => '100%', 'url'=> $url,'key' => $key,'showDelete' => true,'showZoom' => true, 'extra'=> ['type' => 'attachment']];
				// }
			}
		}

		if($inputs->offer_attachment != '')
		{
			$offerAttachments = unserialize($inputs->offer_attachment);;
			foreach($offerAttachments as $key => $offerAttachment){

				$filepath = AppHelper::APP_URL.AppHelper::INSTRUCTION_IMG.$offerAttachment;
				$extension = File::extension($filepath);
				$fileType = AppHelper::getFileType($extension);
				if(in_array($fileType, $noReading)){
					$offer_attachments['src'][] = $filepath;
					//$attachments['config'][] = ['type' => $fileType, 'height' => '20%', 'width' => '100%', 'url'=> $url,'key' => $key,'showDelete' => true,'showZoom' => false, 'extra'=> ['type' => 'attachment']];
				}else{
					$offer_attachments['src'][] = File::get(AppHelper::INSTRUCTION_IMG.$offerAttachment);
				}

				$offer_attachments['config'][] = ['type' => $fileType, 'filename' =>  $filepath, 'url'=> $url, 'key' => $key, 'showDelete' => true, 'extra'=> ['type' => 'offer_attachment']];
				// if(in_array($fileType, $noPreview)){
				// 	$offer_attachments['config'][] = ['type' => $fileType, 'height' => '20%', 'width' => '100%', 'url'=> $url,'key' => $key,'showDelete' => true,'showZoom' => false, 'extra'=> ['type' => 'offer_attachment']];
				// }else{
				// 	$offer_attachments['config'][] = ['type' => $fileType, 'height' => '20%', 'width' => '100%', 'url'=> $url,'key' => $key,'showDelete' => true,'showZoom' => true, 'extra'=> ['type' => 'offer_attachment']];
				// }
			}
		}

		$filteredArr = [
		'round__id'=>["type"=>"hidden",'value'=>$inputs->round_id],
		'instruction_id'=>["type"=>"hidden",'value'=>$inputs->id],
		'instruction_name'=>["type"=>"text",'value'=>$inputs->instruction_name],
		'offer_instruction_name'=>["type"=>"text",'value'=>$inputs->instruction_name],
		'instruction'=>["type"=>"textarea",'value'=>$inputs->instruction],
		'offer_instruction'=>["type"=>"textarea",'value'=>$inputs->offer_instruction],
		'is_default'=>["type"=>"checkbox",'checkedValue'=>$inputs->is_default],
		'attachment'=>["type"=>"file",'file'=>$attachments],
		'offer_attachment'=>["type"=>"file",'file'=>$offer_attachments],
		];

		return response()->json(array(
			"status" => "success",
			"inputs"=>$filteredArr,
			));
	}

	function destroy(Request $request){
		$instruction = Instruction::find($request->input('id'));

		$instruction->delete();

		AssignmentsInstruction::where('instruction_id', $request->input('id'))->delete();

		return response()->json(array(
			"status" => "success",
			"message"=>"Instruction Removed Successfully!",
			));   
	}/* destroy */

	public function apply(Request $request){
		$instruction_id = $request->input('instruction_id');
		$instruction = Instruction::find($instruction_id);

		$assignment_instruction = AssignmentsInstruction::where('instruction_id', $instruction_id)->delete();


		if($request->has('available_sites'))
		{
			foreach($request->input('available_sites') as $assignment_id){
				$data[] = ['instruction_id' => $instruction_id, 'assignment_id' => $assignment_id];
			}

			AssignmentsInstruction::insert($data);
		}
		return response()->json(array(
			"status" => "success",
			"message"=>"Instruction applied successfully!",
			));
	}

	public function getAssignments(Request $request){

		$round_id = $request->input('round_id');
		$instruction_id = $request->input('instruction_id');
		$round = Round::find($round_id);

		//$assignment = new Assignment;
		$instruction = Instruction::find($instruction_id);

		$assignments = $round->assignments;
		foreach($assignments as $assignment){
			$sites[$assignment->id] = $assignment->sites->site_name;
		}

		//$available_sites = $assignment->getAssignmentHasNoInstruction($round);
		//$available_sites = $sites;

		//List all site that has selected instrucion.
		$selected_sites = $instruction->getAssignmentHasInstruction($instruction);

		//Remove site from listing that has selected instrucion.
		$available_sites = $result=array_diff($sites,$selected_sites);;
		return response()->json(array(
			"status" => "success",
			"available_sites" => $available_sites,
			"selected_sites" => $selected_sites,
			));
	}

	public function getdata($round_id){

		$instructions = Instruction::where(['round_id' => $round_id])->get(['id','instruction_name','is_default']);

		return Datatables::of($instructions)

		->addColumn('assignment', function ($instructions) {
			$count =  format_code($instructions->assignments->count(),2);
			return format_code($count,2)." Assignment(s)";
		})

		->addColumn('apply', function ($instructions) {
			if(!$instructions->is_default){
				return '<a href="#"  class="apply_to_assignment_link" data-toggle="modal" data-target="#apply_to_assignment_modal" data-id="'.$instructions->id.'">Apply to Assignment</a>';
			}
			return;
		})

		->addColumn('action', function ($instructions) {
			return '<button class="btn btn-box-tool" type="button" name="remove_instruction" data-id="'.$instructions->id.'" value="delete" ><span class="fa fa-trash"></span></button>';
		})

		->editColumn('instruction_name', function ($instructions) {
			return '<a href="#" onclick="SetInstructionEdit(this,event)" data-id='.$instructions->id.'>'.$instructions->instruction_name.'</a>';
		})
		->removeColumn('id')
		->removeColumn('is_default')
		->make();

	}

	public function deleteAttachment(Request $request,$instruction){
		$instruction = Instruction::find($instruction);
		$deleteImage_key = $request->key;
		$type = $request->type;
		$attachments = unserialize($instruction->$type);
		$path = AppHelper::INSTRUCTION_IMG;
		$filename = $attachments[$deleteImage_key];

		if(file_exists($path.$filename))
		{
			File::delete($path.$filename);
			unset($attachments[$key]);
		} 

		// foreach($attachments as $key => $value)
		// {
		// 	if ($key == $deleteImage_key){

		// 		$path = AppHelper::INSTRUCTION_IMG;
		// 		$filename = $value;

		// 		if(file_exists($path.$filename))
		// 		{
		// 			File::delete($path.$filename);
		// 			//unlink($path.$filename);
		// 			unset($attachments[$key]);
		// 		} 
		// 	}
		// }
		$attachments = serialize($attachments);
		$instruction->update([$type => $attachments]);
		return response()->json(array(
			"status" => "success",
			"message"=>"Attachment Removed",
			));
	}
}
