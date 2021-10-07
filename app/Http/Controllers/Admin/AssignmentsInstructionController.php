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
use App\AssignmentsInstruction;
use Illuminate\Support\Facades\Input;

class AssignmentsInstructionController extends Controller
{
	public function store(Request $request)
	{
		$this->validate($request, [
			"instruction_name"   =>  "required",
			"instruction"   =>  "required_without:offer_instruction",
			//"offer_instruction"   =>  "required_without:instruction",
			]);
		

		if($request->input('instruction_id') == '' || $request->input('instruction_id') == '0')
		{
			$instruction = new AssignmentsInstruction($request->except(['_token','attachment','offer_attachment','offer_instruction_name']));
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

			return response()->json(array(
				"status" => "success",
				"message"=>"Instruction Added Successfully!",
				));
		}else{

			$instruction = AssignmentsInstruction::where(['id'=>$request->input('instruction_id')])->first();
			$instruction->update($request->except(['_token','attachment','offer_attachment','offer_instruction_name','is_default']));
			if (!$request->has('is_default')) {
				$instruction->update(['is_default'=>'0']);
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

			return response()->json(array(
				"status" => "success",
				"message"=>"Instruction Saved Successfully!",
				));
		}
	}


	public function edit($instruction_id){

		$inputs = AssignmentsInstruction::find($instruction_id);

		$noReading = ['image', 'pdf'];
		$noPreview = ['image'];

		$attachments['src'] = $offer_attachments['src'] = [];
		$attachments['config'] = $offer_attachments['config'] = [];
		//$url = AppHelper::APP_URL.'delete-attachment/'.$inputs->id;
		$url = 'delete-attachment/'.$inputs->id;

		if($inputs->attachment != '')
		{
			$assignmentAttachments = unserialize($inputs->attachment);;
			foreach($assignmentAttachments as $key => $assignmentAttachment){
				$filepath = AppHelper::APP_URL.AppHelper::INSTRUCTION_IMG.$assignmentAttachment;
				$extension = File::extension($filepath);
				$fileType = AppHelper::getFileType($extension);
				if(in_array($fileType, $noReading)){
					$attachments['src'][] = $filepath;
					//$attachments['config'][] = ['type' => $fileType, 'height' => '20%', 'width' => '100%', 'url'=> $url,'key' => $key,'showDelete' => true,'showZoom' => false, 'extra'=> ['type' => 'attachment']];
				}else{
					$attachments['src'][] = File::get(AppHelper::INSTRUCTION_IMG.$assignmentAttachment);
				}
				if(in_array($fileType, $noPreview)){
					$attachments['config'][] = ['type' => $fileType, 'height' => '20%', 'width' => '100%', 'url'=> $url,'key' => $key,'showDelete' => true,'showZoom' => true, 'extra'=> ['type' => 'attachment']];
				}else{
					$attachments['config'][] = ['type' => $fileType, 'height' => '20%', 'width' => '100%', 'url'=> $url,'key' => $key,'showDelete' => true,'showZoom' => true, 'extra'=> ['type' => 'attachment']];
				}
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
				if(in_array($fileType, $noPreview)){
					$offer_attachments['config'][] = ['type' => $fileType, 'height' => '20%', 'width' => '100%', 'url'=> $url,'key' => $key,'showDelete' => true,'showZoom' => false, 'extra'=> ['type' => 'attachment']];
				}else{
					$offer_attachments['config'][] = ['type' => $fileType, 'height' => '20%', 'width' => '100%', 'url'=> $url,'key' => $key,'showDelete' => true,'showZoom' => true, 'extra'=> ['type' => 'attachment']];
				}
				// $offer_attachments['src'][] = AppHelper::APP_URL.AppHelper::INSTRUCTION_IMG.$$offerAttachment;
				// $offer_attachments['config'][] = ['height' => '20%', 'width' => '100%', 'url'=> $url,'key' => $key,'showDelete' => true,'showZoom' => false,'extra'=> ['type' => 'offer_attachment']];
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
		$instruction = AssignmentsInstruction::find($request->input('id'));

		$instruction->delete();
		return response()->json(array(
			"status" => "success",
			"message"=>"Instruction Removed Successfully!",
			));   
	}/* destroy */

	public function apply(Request $request){
		$instruction_id = $request->input('instruction_id');
		$request->input('available_sites');
		
		$instruction = AssignmentsInstruction::find($instruction_id);
		$assignments = $instruction->assignments;		
		if($assignments != null){
			foreach($assignments as $assignment){
				$assignment->update(['instruction_id' => null]);
			}
		}

		if($request->has('available_sites'))
		{
			foreach($request->input('available_sites') as $assignment_id){
				$assignment = Assignment::find($assignment_id);
				$assignment->update(['instruction_id' => $instruction_id]);
			}
			return response()->json(array(
				"status" => "success",
				"message"=>"Instruction applied successfully!",
				));   
		}
		// 	else{
		// return response()->json(array(
		// 	"status" => "success",
		// 	"message" => "You must select an assignment!",
		// 	)); 
		//  }

		
	}

	public function getAssignments(Request $request){

		$round_id = $request->input('round_id');
		$instruction_id = $request->input('instruction_id');
		$round = Round::find($round_id);

		$assignment = new Assignment;
		$instruction = AssignmentsInstruction::find($instruction_id);
		$available_sites = $assignment->getAssignmentHasNoInstruction($round);
		//$selected_sites = $assignment->getAssignmentHasInstruction($round,$instruction_id);
		$selected_sites = $instruction->getAssignmentHasInstruction($instruction);
		return response()->json(array(
			"status" => "success",
			"available_sites" => $available_sites,
			"selected_sites" => $selected_sites,
			));
	}

	public function getdata($round_id){

		$instructions = AssignmentsInstruction::where(['round_id' => $round_id])->get(['id','instruction_name']);
		// $instructions = DB::table('instructions')->where('round_id', $round_id)
		// ->select(['id','instruction_name']);


		return Datatables::of($instructions)
		
		->addColumn('assignment', function ($instructions) {
			$count =  format_code($instructions->assignments->count(),2);
			return format_code($count,2)." Assignment(s)";
		})

		->addColumn('apply', function ($instructions) {
			return '<a href="#"  class="apply_to_assignment_link" data-toggle="modal" data-target="#apply_to_assignment_modal" data-id="'.$instructions->id.'">Apply to Assignment</a>';
		})

		->addColumn('action', function ($instructions) {
			return '<button class="btn btn-box-tool" type="button" name="remove_instruction" data-id="'.$instructions->id.'" value="delete" ><span class="fa fa-trash"></span></button>';
		})

		->editColumn('instruction_name', function ($instructions) {
			return '<a href="#" onclick="SetInstructionEdit(this,event)" data-id='.$instructions->id.'>'.$instructions->instruction_name.'</a>';
		})
		->removeColumn('id')
		->make();

	}

	public function deleteAttachment(Request $request,$instruction){

		$instruction = AssignmentsInstruction::find($instruction);
		$deleteImage_key = $request->key;
		$type = $request->type;
		$attachments = unserialize($instruction->$type);
		$path = AppHelper::INSTRUCTION_IMG;
		$filename = $attachments[$$deleteImage_key]['name'];
		if(file_exists($path.$filename))
		{
			File::delete($path.$filename);
			unset($attachments[$deleteImage_key]);
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
