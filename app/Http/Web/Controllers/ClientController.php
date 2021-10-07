<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Contracts\Hashing\Hasher;
use Auth;
use DB;
// use App\Exceptions\SurveyNotAvailableException;

use App\Admin;
use App\User;
use App\Client;
use App\Assignment;
use App\surveys;
use App\Site;

class ClientController extends Controller
{
      public function __construct(){

            parent::__construct();
            $this->middleware('auth', [
                  'only' => [
                        'callShowAssignmentList',
                        'callShowClientDashboard'
                  ]
            ]);
      }

      public function callShowClientDashboard(Request $oRequest){

            $oClient = Client::where('id_user', Auth::id())->first();

            $nScheduledAssignmentCount = Assignment::getClientAssignmentByStatus(config('constants.ASSIGNMENTSTATUS.SCHEDULED'), $oClient->id)->total();
            $nCompletedAssignmentCount = Assignment::getClientAssignmentByStatus(config('constants.ASSIGNMENTSTATUS.COMPLETED'), $oClient->id)->total();

            $nOpenSiteCount = Site::whereIn('chain_id', $oClient->chains()->get(['id'])->toArray())->where('status', TRUE)->count();
            $nCloseSiteCount = Site::whereIn('chain_id', $oClient->chains()->get(['id'])->toArray())->where('status', FALSE)->count();

            $aViewData = [
                  'nScheduledAssignmentCount'   => $nScheduledAssignmentCount,
                  'nCompletedAssignmentCount'   => $nCompletedAssignmentCount,
                  'nOpenSiteCount'              => $nOpenSiteCount,
                  'nCloseSiteCount'             => $nCloseSiteCount,
            ];

            return \View::make('WebView::client.dashboard', $aViewData);
      }

      public function callShowAssignmentList(Request $oRequest, $sAssignmentStatus = 'scheduled')
      {

            if($sAssignmentStatus != config('constants.ASSIGNMENTSTATUS.COMPLETED') && $sAssignmentStatus != config('constants.ASSIGNMENTSTATUS.SCHEDULED'))
            {
                  $sAssignmentStatus = config('constants.ASSIGNMENTSTATUS.SCHEDULED');     
            }

            $oClient = Client::where('id_user', Auth::id())->first();

            if($oRequest->ajax()){
                  $oAssignments = Assignment::getClientAssignmentByStatus($sAssignmentStatus, $oClient->id);
            }else{
                  $nScheduledAssignments = Assignment::getClientAssignmentByStatus(config('constants.ASSIGNMENTSTATUS.SCHEDULED'), $oClient->id);
                  $nCompletedAssignments = Assignment::getClientAssignmentByStatus(config('constants.ASSIGNMENTSTATUS.COMPLETED'), $oClient->id);
                  $oAssignments = ($sAssignmentStatus == config('constants.ASSIGNMENTSTATUS.SCHEDULED')) ? $nScheduledAssignments : $nCompletedAssignments;
                  
            }
            $oViewData = [
                  'oAssignments' => $oAssignments,
                  'sAssignmentStatus' => $sAssignmentStatus
            ];

            if($oRequest->ajax()){
                  $sAssignmentList = \View::make('WebView::client.assignments._more_assignments_list', $oViewData)->render();
                  return response()->json([
                        'status' => 'success', 
                        'data' => [
                              'sAssignmentList' => $sAssignmentList,
                        ],
                  ]);
            }

            $oViewData['nScheduledAssignmentCount'] = $nScheduledAssignments->total();
            $oViewData['nCompletedAssignmentCount'] = $nCompletedAssignments->total();

            return \View::make('WebView::client.assignments.assignment_list', $oViewData);
      }

      public function callShowSurveyOld(Request $oRequest, $nIdSurvey)
      {
            $oSurvey = surveys::findOrFail($nIdSurvey);
            $oAssignment = Assignment::getAssignmentById($oSurvey->assignment_id);
            $aSurveyQuestions = unserialize($oSurvey->keypairs);
            $aSurveyDetail = $oSurvey->getSurveyDetail($oSurvey);
            // dd($aSurveyQuestions);

            $oViewData = [
                  'oSurvey'               => $oSurvey,
                  'oAssignment'           => $oAssignment,
                  'aSurveyQuestions'      => $aSurveyQuestions,
                  'aSurveyDetail'         => $aSurveyDetail,
            ];
            
            return \View::make('WebView::client.surveys.show_survey', $oViewData);
            
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
}