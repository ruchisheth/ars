<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Round;

use App\FieldRepsCriteria;

class FieldrepCriteriaController extends Controller
{
	public function setCriteria(Request $request){
		$round_id = $request->input('round_id');
		$column = preg_replace('/\[.*\]/', '', $request->input('name')); 
		$values = $request->input('values');
		if(count($values) > 0){
			$values = trim(implode(',', $request->input('values')));
			if($values == ""){
				$values = null;
			}
		}
		$criteria = FieldRepsCriteria::where(['round_id'=>$round_id])->first();
		if($criteria == null){
			$criteria = new FieldRepsCriteria;
			$criteria->round_id = $round_id;
			$criteria->save();
		}
		$criteria->update([$column=>$values]);
		return response()->json(array(
			"status" => "success",
		));
	}
}
