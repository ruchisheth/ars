<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Contracts\Hashing\Hasher;
use Auth;
use DB;
// use App\Exceptions\SurveyNotAvailableException;

use App\Client;
use App\FieldRep;
use App\Admin;
use App\User;
use App\Assignment;
use App\AssignmentsOffer;
use App\Site;
use App\surveys;
use App\RoundsAcknowledge;
use App\Profile;
use App\FieldRep_Org;

// use App\Exceptions\SurveyNotAvailableException;

class FieldRepController extends Controller
{
  public function __construct(){

    parent::__construct();
    $this->middleware('auth', [
      'only' => [
        'callShowAssignmentList',
      ]
    ]);
  }

  public function callShowFieldRepDashboard(Request $oRequest){

            // dd(Auth::user()->UserDetails);

    $oAcknowledgements = $oBulletin = null;

    $oFieldrep = FieldRep::find(Auth::user()->UserDetails->id);

    if(!session()->has('is_acknowledged')){
                  // $acknos = RoundsAcknowledge::where(['fieldrep_id' => Auth::user()->UserDetails->id, 'is_acknowledged' => false])->get();
      $oAcknowledgements = RoundsAcknowledge::where(['fieldrep_id' => Auth::user()->UserDetails->id, 'is_acknowledged' => false])->get();


      if($oAcknowledgements->isEmpty()){
        $oBulletin = null;
      }else{
        $oBulletin = $oAcknowledgements->filter(function ($oAcknowledgements) {
          if($oAcknowledgements->rounds->is_bulletin == true)
            return $oAcknowledgements;
        });
        if($oBulletin->isEmpty()){
          $oBulletin = null;                      
        }
      }
    }

    session()->put('is_acknowledged', true);

            $oAssignments = $oFieldrep->assignments;//->whereIn('status', [1,3]);
            $oOffers = $oFieldrep->offers->where('is_accepted', NULL);          

            $oOffers = $oOffers->filter(function ($oOffers) {
              return ($oOffers->assignments->rounds->status == 1 && $oOffers->assignments->rounds->projects->status == 1 && $oOffers->assignments->status == 0);
            });

            /* List only those assignments whose Project and Round is Active and are not reported*/
            $oScheduledAssignments = $oAssignments->filter(function ($oAssignments) {
              return ($oAssignments->rounds->status == 1 && $oAssignments->rounds->projects->status == 1 && (($oAssignments->is_scheduled && !$oAssignments->is_partial ) && (!$oAssignments->is_reported)));
            });

            $oParialAssignments = $oAssignments->filter(function ($oAssignments) {
              return ($oAssignments->rounds->status == 1 && $oAssignments->rounds->projects->status == 1 && (($oAssignments->is_partial ) && (!$oAssignments->is_reported)));
            });

            $nScheduledAassignments = $oScheduledAssignments->count();
            $nPartialAassignments = $oParialAssignments->count();
            $nOffers = $oOffers->groupBy('assignment_id')->count();
            $aViewData = [
              'nScheduledAassignments'      => $nScheduledAassignments,
              'nPartialAassignments'        => $nPartialAassignments,
              'nOffers'                     => $nOffers,
              'oBulletin'                   => $oBulletin,
            ];

            return \View::make('WebView::fieldrep.dashboard', $aViewData);
          }

          public function callShowAssignmentList(Request $oRequest, $sAssignmentStatus = NULL)
          {
            $oLoggedInUser = Auth::user();
            $id_rep = $oLoggedInUser->UserDetails->id;
            $sClientCode = $oLoggedInUser->client_code;

            if($sAssignmentStatus == NULL)
            {
              $sAssignmentStatus = config('constants.ASSIGNMENTSTATUS.SCHEDULED');     
            }

            $oAssignments      = Assignment::getFieldRepAssignmentByStatus($sAssignmentStatus, $id_rep);
            
            $aViewData = [
              'oAssignments' => $oAssignments,
              'sAssignmentStatus' => $sAssignmentStatus
            ];

            if(!$oRequest->ajax()){
              $aAssignmentsCount = Assignment::getFieldRepsAssignmentCountByStatus($id_rep);
              $aViewData['aAssignmentsCount'] = $aAssignmentsCount;
            }

            if($oRequest->ajax()){
              $sAssignmentList = \View::make('WebView::fieldrep.assignments._more_assignments_list', $aViewData)->render();
              return response()->json([
                'status' => 'success', 
                'data' => [
                  'sAssignmentList' => $sAssignmentList,
                ],
              ]);
            }

            // $aViewData['nScheduledAssignmentCount'] = $oScheduledAssignments->total();
            // $aViewData['nCompletedAssignmentCount'] = $oCompletedAssignments->total();

            return \View::make('WebView::fieldrep.assignments.assignment_list', $aViewData);
          }

          public function callShowOfferList(Request $oRequest, $sOfferStatus = NULL)
          {
            $oLoggedInUser = Auth::user();
            $nIdFieldRep = $oLoggedInUser->UserDetails->id;
            $sClientCode = $oLoggedInUser->client_code;

            if($sOfferStatus == NULL)
            {
              $sOfferStatus = config('constants.OFFERSTATUS.PENDING');     
            }

            $oAssignmentOffers = AssignmentsOffer::getFieldRepAssignmentOfferByStatus($nIdFieldRep, $sOfferStatus);
            
            
            $aViewData = [
              'oAssignmentOffers' => $oAssignmentOffers,
              'sOfferStatus' => $sOfferStatus
            ];

            if(!$oRequest->ajax()){
              $aAssignmentOffersCount = AssignmentsOffer::getFieldRepAssignmentOfferCountByStatus($nIdFieldRep);
              $aViewData['aAssignmentOffersCount'] = $aAssignmentOffersCount;
            }

            if($oRequest->ajax()){
              $sOfferList = \View::make('WebView::fieldrep.offers._more_offers_list', $aViewData)->render();
              return response()->json([
                'status' => 'success', 
                'data' => [
                  'sOfferList' => $sOfferList,
                ],
              ]);
            }

            return \View::make('WebView::fieldrep.offers.offer_list', $aViewData);
          }

          public function GetSurvey(Request $requests, $id, $client_code)
          {

          }

          public function callShowSurvey(Request $oRequest, $nIdSurvey)
          {
            $oSurvey = surveys::findorFail($nIdSurvey);
            $oAssignment = Assignment::getAssignmentById($oSurvey->assignment_id);
            // if(!$oAssignment->is_reported && !$oAssignment->is_partial){
            //       throw new SurveyNotAvailableException();
            // }

            $oSurveyDetails = (object)$oSurvey->getSurveyDetail($oSurvey);

            $aSurveyQuestions = unserialize($oSurvey->keypairs);

            $oViewData = [
              'oSurvey'               => $oSurvey,
              'oAssignment'           => $oAssignment,
              'oSurveyDetails'        => $oSurveyDetails,
              'aSurveyQuestions'      => $aSurveyQuestions,
            ];
            
            return \View::make('WebView::client.surveys.show_survey', $oViewData);
            
          }

          public function callAcceptOffer(Request $oRequest){
            $aIdOffer = $oRequest->aIdOffer;
            foreach($aIdOffer as $nIdOffer ){
              $oAssignmentOffer = AssignmentsOffer::find($nIdOffer);                 
              AssignmentsOffer::where('assignment_id', $oAssignmentOffer->assignment_id)
              ->where('fieldrep_id', $oAssignmentOffer->fieldrep_id)
              ->where('is_accepted', null)
              ->update(['is_accepted' => true]);
              $oAssignment = $oAssignmentOffer->assignments;
              app('App\Http\Controllers\Admin\AssignmentController')->scheduelFieldrep($oAssignment, Auth::user()->UserDetails->id);
            }
            return response()->json(array(
              "status" => "success",
              "message"=>"Offer Accepted",
            ));
          }

          public function callRejectOffer(Request $oRequest){
            $aIdOffer = $oRequest->aIdOffer;
            $sReasonToReject = $oRequest->sReasonToReject;
            $sOtherReason = '';
            if($sReasonToReject == 5 ){
              $sOtherReason = $oRequest->sOtherReason;
            }
            foreach($aIdOffer as $nIdOffer){
              $oAssignmentOffer = AssignmentsOffer::find($nIdOffer);                 

              AssignmentsOffer::where('assignment_id', $oAssignmentOffer->assignment_id)
              ->where('fieldrep_id', $oAssignmentOffer->fieldrep_id)
              ->where('is_accepted', null)
              ->update(['is_accepted' => false, 'reject_reason' => $sReasonToReject, 'other_reason' => $sOtherReason]);
            }
            return response()->json([
              'status' => 'success',
              'message' => 'Offer Rejected',
            ]);
          }

          public function callAccountSetting(Request $oRequest){

            $oFieldRep = FieldRep::where(['user_id' => Auth::id()])->first();
            $oProfile = Profile::where(['user_id' => Auth::id()])->first();
            $oFieldRepOrg = FieldRep_Org::find($oFieldRep->organization_name);
            $aProjectTypes = DB::table('_list')->where('list_name','=','project_types')->orderBy('list_order')->lists('item_name','id'); 

            $aViewData = compact('oFieldRep', 'oProfile', 'oFieldRepOrg', 'aProjectTypes');
            return \View::make('WebView::fieldrep.profile.account_setting', $aViewData);
          }

          public function callShowFieldRepProjectType(Request $oRequest){
            if($oRequest->isMethod('POST')){
              $have_done = $interested_in = '';

              $oFieldRep = FieldRep::where(['user_id' =>Auth::id()])->first();
              if($oRequest->has('have_done')){
                $sHaveDone = implode(',',array_keys($oRequest->have_done));
              }
              if($oRequest->has('interested_in')){
                $sInterestedIn = implode(',',array_keys($oRequest->interested_in));
              }

              $oFieldRep->update(['have_done' => $sHaveDone, 'interested_in' => $sInterestedIn]);

              return response()->json([
                'status' => 'success',
                'message' => 'success',
              ]);
            }
          }
        }