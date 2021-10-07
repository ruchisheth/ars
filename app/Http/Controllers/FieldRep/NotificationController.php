<?php

namespace App\Http\Controllers\FieldRep;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\AppHelper;

use Auth;

use App\Round;


class NotificationController extends Controller
{
	

	// public function __construct(){
		//parent::__construct();
		// $rep = FieldRep::where(['user_id' => Auth::id()])->first();
		// $this->rep_id = $rep->id;	
	// }

	public function callShowNotifications(){
		http_response_code(500);
		$oLoggedInUser  = Auth::user();
		$oUserDetail  = $oLoggedInUser->UserDetails;

		$oRounds = Round::getUserRoundsEndInThreeDays($oLoggedInUser->id);

		$aViewData = [
			'oRounds' => $oRounds
		];
		// foreach($oRounds as $oRound){
		// 	$nDaysLeft = NULL;
		// 	if($oRound->assignment_end != NULL){
		// 		$dEndDate = Carbon::parse($oRound->asssignment_end);
		// 	}else{
				
		// 		$dEndDate = Carbon::parse($oRound->round_end);
		// 	}
		
		// }
			// $dEndDate->diffInDays(Carbon::now());
		$sHtml = \View::make('WebView::fieldrep.notifications', $aViewData)->render();

		return response()->json([
			'success' => TRUE,
			'message' => '',
			'data' =>	[
				'sHtml'	=>	$sHtml
			] 
		]);
	}

}
