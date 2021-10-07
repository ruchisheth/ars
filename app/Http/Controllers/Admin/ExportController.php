<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\AppHelper;
use Response;
use View;

use App\Admin;
use App\Client;
use App\Chain;
use App\Project;
use App\surveys;
use App\Assignment;
use App\Round;
use App\Site;
use App\FieldRep;
use App\surveys_template;
use App\SiteSetting;

use DB;
use Auth;
use Excel;
use Datatables;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Carbon\Carbon;

class ExportController extends Controller
{
	public function exportView(Request $oRequest, $sEntity = NULL){

		if($sEntity == NULL){
			return view('admin.export.export');
		}else{
			$aClientList = ['' => 'Select Client'] + Client::lists('client_name','id')->all();
			$sView = View::make('admin.export.export_'.$sEntity,['client_list' => $aClientList]);
			if($oRequest->ajax()) {
				$aSections = $sView->renderSections(); // returns an associative array of 'content', 'head' and 'footer'
	      return $aSections['content']; // this will only return whats in the content section
	    }
	    return $sView;
	  }
	}

	public function exportSurveyData(Request $oRequest){
		
		$this->validate($oRequest, [
      "client_id"   =>  "required",
      "project_id"   =>  "required",
      "round_id"   =>  "required",
    ],
    [
      "client_id.required" => "The Client Id field is required.",
      "project_id.required" => "The Project field is required.",
      "round_id.required" => "The Round Id field is required.",
    ]);

		$aQuestion  = [];
    $aExportQuestion  = [];
    $aQuestionTag  = [];
    
    $bIsReportedSurveyOnly    = $oRequest->has('reported_survey');
    $bIsApprovedSurveyOnly    = $oRequest->has('approved_survey');
    $bIsUnexportedSurveyOnly  = $oRequest->has('unexported_survey');

    foreach($oRequest->questions as $nQuestionKey =>  $aQue){
      if(array_key_exists('export', $aQue)){
        $aQuestion[$nQuestionKey] = $aQue;
      }
    }

    $aExportQuestion = array_keys($aQuestion);

    $oRound = Round::find($oRequest->round_id);
    $oProject = Project::find($oRequest->project_id);
    $sFileName = format_code($oRound->id)."-".$oRound->round_name;
    $oAssignments = Assignment::where('round_id', $oRequest->get('round_id'));//->whereRaw("1 = 1");

    if($bIsReportedSurveyOnly){
      if($bIsApprovedSurveyOnly){
        $oAssignments->where(['is_reported' => TRUE])->orWhere([ 'is_approved' => TRUE]);
      }else{
        $oAssignments->where(['is_reported' => TRUE, 'is_approved' => FALSE]);
      }
    } else if($bIsApprovedSurveyOnly){
      if($bIsReportedSurveyOnly){
        $oAssignments->where(['is_reported' => TRUE])->orWhere([ 'is_approved' => TRUE]);
      }else{
        $oAssignments->where(['is_approved' => TRUE]);
      }
    }

    $oAssignments = $oAssignments->get(['id']);

    if($oAssignments->isEmpty()) {
      return redirect('/exports/survey')->withErrors('Your request has no result');
    }

    $nAssignmentIds = array_map('current',$oAssignments->toArray());

    $oSurveys = surveys::whereIn('assignment_id', $nAssignmentIds)->whereIn('status', ['2','4']);

    if($bIsUnexportedSurveyOnly){
      $oSurveys->where('is_exported', '=', false);
    }

    $oSurveys = $oSurveys->get();

    if($oSurveys->isEmpty()) {
      return redirect('/exports/survey')->withErrors('Your request has no result');
    }

    $aTable;
    $aColumnHeading = [
        'Survey No.', 'Project Name', 'Round Name', 'Assignment Code', 
        'Site Code','Chain Code', 'Template Code', 'Survey Status', 
        'Template name', 'Schedule Date', 'Deadline Date', 'Reported Date'
      ];
    $bIsSetColumn = false;

    foreach($oSurveys as $nIdKey => $oSurvey){
      $survey_exported[] =  $oSurvey->id;

      // Fetch site code of survey (assignment code);
      $nSiteCode = $oSurvey->assignments->sites->site_code;
      $nChainCode = $oSurvey->assignments->rounds->projects->chains->id;
      $oAssignment = Assignment::find($oSurvey->assignments->id);
      $oSurveyTemplate = surveys_template::find($oSurvey->template_id);

			$aTable[$nIdKey][] =   $nSiteCode; //Survey No.
			$aTable[$nIdKey][] =   $oProject->project_name; //Project Name
			$aTable[$nIdKey][] =   $oRound->round_name; //Round Name
			$aTable[$nIdKey][] =   $nSiteCode; // Assignment Code
			$aTable[$nIdKey][] =   $nSiteCode; // Site Code
			$aTable[$nIdKey][] =   $nChainCode; //Chain Code
      $aTable[$nIdKey][] =   $oSurvey->template_id; //Template Code
			$aTable[$nIdKey][] =   $oAssignment->callGetAssignmentStatus(); //Template Code
			$aTable[$nIdKey][] =   $oSurveyTemplate->template_name; //Template name
			$aTable[$nIdKey][] =   \Carbon::parse($oAssignment->getAssignmentScheduleDate())->format(AppHelper::DATE_EXPORT_FORMAT); // Schedule Date
      $aTable[$nIdKey][] =   \Carbon::parse($oAssignment->getAssignmentEndDate())->format(AppHelper::DATE_EXPORT_FORMAT); // Deadline Date
			$aTable[$nIdKey][] =   ($oAssignment->reported_at != NULL) ? \Carbon::parse($oAssignment->reported_at)->format(AppHelper::DATE_EXPORT_FORMAT) : 'NA'; // Deadline Date

			$aSurveyQuestions = unserialize($oSurvey->keypairs);

			if(!empty($aExportQuestion)){
				$aExportQuestions = array_flip($aExportQuestion);
				$aSurveyQuestions = array_intersect_key($aSurveyQuestions, $aExportQuestions);


				foreach ($aSurveyQuestions as $nQuestionNumber => $aSurveyQuestionDetail) {
					$sColumnHeading = $aSurveyQuestionDetail['que_no'];
					if($aSurveyQuestionDetail['type'] == 'file'){
						$sAnswer = explode(',',$aSurveyQuestionDetail['ans']);
						$sAnswer = implode($sAnswer,' ; ');
					}
					else if($aSurveyQuestionDetail['type'] == 'checkbox' || $aSurveyQuestionDetail['type'] == 'radio'){
						if($aSurveyQuestionDetail['ans'] == ""){
							$aSurveyQuestionDetail['ans'] = "0";
							$sAnswer = $aSurveyQuestionDetail['ans'];
						}
						$sAnswer = $aSurveyQuestionDetail['ans'];
					}else if($aSurveyQuestionDetail['type'] == 'date'){
						$date = $aSurveyQuestionDetail['ans'];
            if($date != '' || $date != NULL){
              $sAnswer = \Carbon::parse($date)->format(AppHelper::DATE_EXPORT_FORMAT);            
            }
          }
          else{
            $sAnswer = $aSurveyQuestionDetail['ans'];
          }

          if($aQuestion[$nQuestionNumber]['question_tag'] != ""){

            $sColumnHeading = $aQuestion[$nQuestionNumber]['question_tag'];
            $aQuestionTag[$nQuestionNumber] = $aQuestion[$nQuestionNumber]['question_tag'];
          }else{
            $sColumnHeading = "(Q.".$nQuestionNumber.') '.$aSurveyQuestionDetail['ques'];
          }

          if($bIsSetColumn == false){
            $aColumnHeading[] = $sColumnHeading;
          }
          $aTable[$nIdKey][] = $sAnswer; /*ans of question*/
        }
      }
      $bIsSetColumn = true;
    }

    $aTable = Collection::make($aTable);

    /*order data by site code (Survey NO column)*/
    $aTable = $aTable->sortBy(0);
    
    /*
    * Mark Surveys as Exported
    */
    surveys::whereIn('id', $survey_exported)->update(['is_exported' => true]);

    Excel::create($sFileName,function($excel) use ($aTable, $aColumnHeading){
      $excel->sheet('Sheet 1',function($sheet) use ($aTable, $aColumnHeading){
        $sheet->fromArray($aTable, null, 'A1', false)
        ->row(1, $aColumnHeading)
        ->freezeFirstRowAndColumn();
      });
    })->export('csv');

    return redirect('export/survey')->with('success', 'Survey Data Exported!');
  }
  
    public function exportAllUnExportedSurvey(Request $oRequest)
    {
        $oAdmins = Admin::where(['status' => TRUE, 'client_code' => 'KLP' ])->get();
    
        $dCurrentDate = Carbon::now('US/Eastern');
        $sDateFolderName = $dCurrentDate->format('Y-m-d');
        $nHourFolderName = $dCurrentDate->format('H');
        $bFtpLogin = false;

        foreach($oAdmins as $oAdmin)
        {
      
            $sExportedSurveyFolderPath = public_path(config('constants.EXPORTFOLDER')).'/'.strtoupper($oAdmin->client_code).'/'.$sDateFolderName.'/'.$nHourFolderName;
            \File::makeDirectory($sExportedSurveyFolderPath, 0775, true, true);
            chmod($sExportedSurveyFolderPath, 0777);

            $sSchemaName = config('constants.DB_PREFIX').$oAdmin->db_version;
            parent::setDBConnection($sSchemaName);
            
            $oAdminSettings = SiteSetting::whereIn('setting_key', ['ftp_host', 'ftp_username', 'ftp_password', 'ftp_port', 'ftp_directory'])->get();
            
            if($oAdminSettings)
            {
                $aAdminSettings = $oAdminSettings->pluck('setting_value', 'setting_key')->toArray();

                $aAdminSettings['ftp_directory'] = trim($aAdminSettings['ftp_directory'], '/').'/ars/'.$sDateFolderName.'/'.$nHourFolderName;

                $aFtpDirectoryPath = explode('/', $aAdminSettings['ftp_directory']);

                if($aAdminSettings['ftp_host'] !== '' && $aAdminSettings['ftp_username'] !== '' && $aAdminSettings['ftp_password'] !== '' )
                {
                    $oFtpServer = $aAdminSettings['ftp_host'];

                    $oFtpConnection = ftp_connect($oFtpServer) or die("Could not connect to $oFtpServer");

                    $bFtplogin = ftp_login($oFtpConnection, $aAdminSettings['ftp_username'], $aAdminSettings['ftp_password']);

                    foreach($aFtpDirectoryPath as $sFtpDirectory)
                    {
                        if (!@ftp_chdir($oFtpConnection, $sFtpDirectory)) 
                        {
                            ftp_mkdir($oFtpConnection, $sFtpDirectory);
                            ftp_chdir($oFtpConnection, $sFtpDirectory);
                        }            
                    }

                    ftp_pasv($oFtpConnection, true);
                }
            }
            
            $oUnExportedSurveys = Surveys::from('surveys as s')
            ->leftJoin('assignments as a', 's.assignment_id', '=', 'a.id')
            ->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
            ->where([
                's.is_exported' => false,
                'a.is_reported' => true,
            ])
            ->whereIn('s.status', ['2','4'])
            ->orderBy('round_id', 'asc')
            ->groupBy('round_id')
            ->get([
                's.id as survey_id',
                's.status as survey_status',
                'a.id as assignment_id',
                'a.is_reported as is_reported',
                'r.id as round_id',
            ]);
            
            if($oUnExportedSurveys->isEmpty()) {
                // \File::deleteDirectory($sExportedSurveyFolderPath);
                return;
            }
                
            $aRoundIds = $oUnExportedSurveys->pluck('round_id')->toArray();
    
            $aTable;
            
            foreach($aRoundIds as $nIdRound)
            {
                $aColumnHeading = [
                    'Survey No.', 'Project Name', 'Round Name', 'Assignment Code', 'Site Code','Chain Code', 'Template Code', 'Survey Status', 'Template name', 'Schedule Date', 'Deadline Date', 'Reported Date'
                ];
                $bIsSetColumn = false;
                $oSurveys = Surveys::from('surveys as s')
                    ->leftJoin('assignments as a', 's.assignment_id', '=', 'a.id')
                    ->leftJoin('rounds as r', 'a.round_id', '=', 'r.id')
                    ->where([
                        's.is_exported'  => false, 
                        'a.is_reported' => true,
                        'r.id' => $nIdRound
                    ])
                    ->whereIn('s.status', ['2','4'])
                    ->orderBy('round_id', 'asc')
                    ->get([
                        's.id as survey_id',
                        's.template_id as template_id',
                        's.keypairs as keypairs',
                        's.status as survey_status',
                        'a.id as assignment_id',
                        'a.is_reported as is_reported',
                        'r.id as round_id', 
                    ]);
                    
                $aSurveyExported = $aTable = [];
    
                foreach($oSurveys as $nIdKey => $oSurvey)
                {
                    $aSurveyExported[] =  $oSurvey->survey_id;
    
                    // Fetch site code of survey (assignment code);
                    $oAssignment = Assignment::find($oSurvey->assignment_id);
                    $oRound = Round::find($oSurvey->round_id);
                    $oProject = $oRound->projects;
                    $oSurveyTemplate = surveys_template::find($oSurvey->template_id);
                    
    
                    $sFileName = format_code($oRound->id)."-".preg_replace("/[^a-z0-9\_\-\.]/i", '_', $oRound->round_name);
            
                    $nSiteCode = $oAssignment->sites->site_code;
                    
                    $nChainCode = $oProject->chains->id;
            
                    $aTable[$nIdKey][] =   $oSurvey->survey_id; //Survey No.
                    $aTable[$nIdKey][] =   $oProject->project_name; //Project Name
                    $aTable[$nIdKey][] =   $oRound->round_name; //Round Name
                    $aTable[$nIdKey][] =   $nSiteCode; // Assignment Code
                    $aTable[$nIdKey][] =   $nSiteCode; // Site Code
                    $aTable[$nIdKey][] =   $nChainCode; //Chain Code
                    $aTable[$nIdKey][] =   $oSurvey->template_id; //Template Code
                    $aTable[$nIdKey][] =   $oAssignment->callGetAssignmentStatus(); //Template Code
                    $aTable[$nIdKey][] =   $oSurveyTemplate->template_name; //Template name
                    $aTable[$nIdKey][] =   \Carbon::parse($oAssignment->getAssignmentScheduleDate())->format(AppHelper::DATE_EXPORT_FORMAT); // Schedule Date
                    $aTable[$nIdKey][] =   \Carbon::parse($oAssignment->getAssignmentEndDate())->format(AppHelper::DATE_EXPORT_FORMAT); // Deadline Date
                    $aTable[$nIdKey][] =   ($oAssignment->reported_at != NULL) ? \Carbon::parse($oAssignment->reported_at)->format(AppHelper::DATE_EXPORT_FORMAT) : 'NA'; // Deadline Date
            
                    $aSurveyQuestions = unserialize($oSurvey->keypairs);
                    $aSurveyQuestionTags = NULL;
                    if($oSurveyTemplate->question_tags != NULL){
                        $aSurveyQuestionTags =  unserialize($oSurveyTemplate->question_tags);    
                    }
    
                    foreach ($aSurveyQuestions as $nQuestionNumber => $aSurveyQuestionDetail) 
                    {
                        $sColumnHeading = $aSurveyQuestionDetail['que_no'];
                        if($aSurveyQuestionDetail['type'] == 'file'){
                            $sAnswer = explode(',',$aSurveyQuestionDetail['ans']);
                            $sAnswer = implode($sAnswer,' ; ');
                        }
                        else if($aSurveyQuestionDetail['type'] == 'checkbox' || $aSurveyQuestionDetail['type'] == 'radio')
                        {
                            if($aSurveyQuestionDetail['ans'] == "")
                            {
                                $aSurveyQuestionDetail['ans'] = "0";
                                $sAnswer = $aSurveyQuestionDetail['ans'];
                            }
                            $sAnswer = $aSurveyQuestionDetail['ans'];
                        }
                        else if($aSurveyQuestionDetail['type'] == 'date')
                        {
                            $date = $aSurveyQuestionDetail['ans'];
                            if($date != '' || $date != NULL)
                            {
                                $sAnswer = \Carbon::parse($date)->format(AppHelper::DATE_EXPORT_FORMAT);            
                            }
                        }
                        else
                        {
                            $sAnswer = $aSurveyQuestionDetail['ans'];
                        }
                        if ($aSurveyQuestionTags != NULL && array_key_exists($nQuestionNumber, $aSurveyQuestionTags)){
                            $sColumnHeading = $aSurveyQuestionTags[$nQuestionNumber];
                        }else{
                            $sColumnHeading = "(Q.".$nQuestionNumber.') '.$aSurveyQuestionDetail['ques'];
                        }
                        //$sColumnHeading = "(Q.".$nQuestionNumber.') '.$sSurveyQuestion;
                        //$sColumnHeading = "(Q.".$nQuestionNumber.') '.$aSurveyQuestionDetail['ques'];
    
                        if($bIsSetColumn == false){
                            $aColumnHeading[] = $sColumnHeading;
                        }
                        $aTable[$nIdKey][] = $sAnswer; /*ans of question*/
                    }
                    $bIsSetColumn = true;
                }
                $aTable = Collection::make($aTable);
    
                /*order data by site code (Survey NO column)*/
                $aTable = $aTable->sortBy(0);
                    
                //Mark Surveys as Exported
                surveys::whereIn('id', $aSurveyExported)->update(['is_exported' => true, 'exported_at' => Carbon::now()]);
            
                Excel::create($sFileName,function($excel) use ($aTable, $aColumnHeading){
                    $excel->sheet('Sheet 1',function($sheet) use ($aTable, $aColumnHeading)
                    {
                        $sheet->fromArray($aTable, null, 'A1', false)
                        ->row(1, $aColumnHeading)
                        ->freezeFirstRowAndColumn();
                    });
                })->store('csv', $sExportedSurveyFolderPath);
                if($bFtplogin)
                {
                    ftp_put($oFtpConnection, $sFileName.'.csv', $sExportedSurveyFolderPath.'/'.$sFileName.'.csv', FTP_BINARY);
                }
                
            }
            if($bFtplogin)
                {
                    ftp_close($oFtpConnection);
                }
            
            // $oZipArchive = new ZipArchive;
            // $res = $oZipArchive->open($sExportedSurveyFolderPath.'.zip', ZipArchive::CREATE);
            // if (!$res)
            //     die('failed, code:' . $res);

            // $oFiles = new RecursiveIteratorIterator(
            //     new RecursiveDirectoryIterator($sExportedSurveyFolderPath),
            //     RecursiveIteratorIterator::LEAVES_ONLY
            // );

            // foreach ($oFiles as $sName => $oFile)
            // {
            //     // Skip directories (they would be added automatically)
            //     if (!$oFile->isDir())
            //     {
            //         // Get real and relative path for current file
            //         $sFilePath = $oFile->getRealPath();
            //         $sRelativePath = substr($sFilePath, strlen($sExportedSurveyFolderPath) + 1);

            //         // Add current file to archive
            //         $oZipArchive->addFile($sFilePath, $sRelativePath);
            //     }
            // }
            
            // $oZipArchive->close();

            // \File::deleteDirectory($sExportedSurveyFolderPath);
        }
    }

    public function exportFieldrepData(Request $oRequest){

    $sFileName = 'FieldrepData';
    $nClassification = ($oRequest->classification != '') ? $oRequest->classification : NULL;
    $nInitialStatus = ($oRequest->initial_status != '') ? $oRequest->initial_status : NULL;

    $oFiledreps = FieldRep::from('fieldreps as f')
    ->when(isset($nClassification), function ($query) use ($nClassification) {
      return $query->where('f.classification', '=', $nClassification);
    })
    ->when(isset($nInitialStatus), function ($query) use ($nInitialStatus) {
      return $query->where('f.initial_status', '=', $nInitialStatus);
    })
    ->orderBy('fieldrep_code')
    ->get();

    if($oFiledreps->isEmpty()) {
      return redirect('/exports/fieldreps')->withInput()->withErrors('Your request has no result');
    }


    $aTable = [];
    $aColumnHeading = ['FieldRep Code', 'Name', 'Classification', 'Status'];

      foreach($oFiledreps as $nKey => $oFiledrep){

        $sClassification = $sInitialStatus = NULL;
        if($oFiledrep->classification == 1){
          $sClassification = 'Indipendent Contractor';
        }else if($oFiledrep->classification == 2){
          $sClassification = 'Employee';
        }

        if($oFiledrep->status === 0){
          $sInitialStatus = 'Inactive';
        }else if($oFiledrep->status === 1){
          $sInitialStatus = 'Active';
        }

      $aTable[$nKey][] =   $oFiledrep->fieldrep_code; //FieldRep Code.
      $aTable[$nKey][] =   $oFiledrep->first_name.' '.$oFiledrep->last_name; //Full Naem
      $aTable[$nKey][] =   $sClassification; //Indipendent Contractor / Employee
      $aTable[$nKey][] =   $sInitialStatus; // Active / Inactive / Terminated / Hold
    }

    $aTable = Collection::make($aTable);

    /*order data by FieldRep Code */
    $aTable = $aTable->sortBy(0);
    

    Excel::create($sFileName,function($excel) use ($aTable, $aColumnHeading){
      $excel->sheet('Sheet 1',function($sheet) use ($aTable, $aColumnHeading){
        $sheet->fromArray($aTable, null, 'A1', false)
        ->row(1, $aColumnHeading)
        ->freezeFirstRowAndColumn();
      });
    })->export('csv');

    return redirect('export/fieldreps')->with('success', 'FieldRep Data Exported!');
  }

    public function getClientProject(Request $oRequest){
    $nIdClient = $oRequest->get('depdrop_parents')[0];
    if($nIdClient != "")
    {
      $oClient = Client::find($nIdClient);
      $oPojects = $oClient->projects();
      $oPojects = $oPojects->get(['project_name','projects.id']);
      if(!$oPojects->isEmpty()){
        $aProjects = [];
        foreach ($oPojects as $nIdKey => $oPoject) {
          $aProjects[] = ['id' => $oPoject->id, 'name' => $oPoject->project_name];
        }
        return json_encode([
          'output'    => $aProjects, 
          'selected'  => ''
        ]);
      }
    }
    return json_encode([
      'output'  => '', 
      'selected'=>''
    ]);
  }

    public function getProjectRound(Request $oRequest){
    $nIdProject = $oRequest->get('depdrop_parents')[0];
    $aParameter = $oRequest->get('depdrop_all_params');
    if($nIdProject != ""){
      $oProject = Project::find($nIdProject);
      $oRounds = $oProject->rounds()->orderBy('round_name')->get();
      if(!$oRounds->isEmpty()){
        $out_rounds = [];
        foreach ($oRounds as $nKey => $oRound) {
          $nUnexportedSurveyCount = 0;
          if($oRequest->has('depdrop_all_params.unexported_count')){
            $nUnexportedSurveyCount = $oRound->surveys()->where(['is_exported' => false, 'status' => 2])->count();
          }
          
          $sRoundName = $oRound->round_name;
          
          if($nUnexportedSurveyCount > 0){
            $sRoundName .= ' (UnExported Survey - '.$nUnexportedSurveyCount.')';
          }
          $aRounds[] = ['id' => $oRound->id, 'name' => $sRoundName];
        }
        return json_encode([
          'output'  => $aRounds, 
          'selected'=> ''
        ]);
      }
  	}
  	else if(array_has($aParameter, 'all_round')){
  		$oRounds = Round::orderBy('id','desc')->get();
  		$aRounds = [];
  		foreach ($oRounds as $nKey => $oRound) {
  			$aRounds[] = ['id' => $oRound->id, 'name' => $oRound->round_name];
  		}
  		return json_encode([
        'output'  => $aRounds,
        'selected'=> ''
      ]);
  	}
  	return json_encode([
      'output'  => '',
      'selected'=> ''
    ]);
  }

    public function getProjectSiteCode(Request $oRequest){
  	$option = $oRequest->get('depdrop_parents')[0];
  	$params = $oRequest->get('depdrop_all_params');

  	if($option != ""){
  		$project = Project::find($option);
  		$sites = $project->chains->sites()->distinct()->orderBy(DB::raw('lpad(trim(site_code), 10, 0)'), 'asc')->get(['site_code']);
  		if(!$sites->isEmpty()){
  			$out_site_codes = [];
  			foreach ($sites as $key => $site) {
  				$out_site_codes[] = ['id' => $site->site_code, 'name' => $site->site_code];
  			}
  			return json_encode(['output'=> $out_site_codes, 'selected'=>'']);
  		}
  	}//if over
  	else if(array_has($params, 'all_sitecodes')){
  		$sites = Site::select(['site_code'])->orderBy(DB::raw('lpad(trim(site_code), 10, 0)'), 'acs')->distinct()->get();
  		$out_site_codes = [];
  		foreach ($sites as $key => $site) {
  			$out_site_codes[] = ['id' => $site->site_code, 'name' => $site->site_code];
  		}
  		return json_encode(['output'=> $out_site_codes, 'selected'=>'']);
  	}
  	return json_encode(['output'=>'', 'selected'=>'']);
  }

    public function getRoundQuestion(Request $oRequest){
    $nIdRound = $oRequest->round_id;
    if($nIdRound != ""){
      $oRound = Round::find($nIdRound);
      $oSurveys = $oRound->surveys;
      // $oSurveys = $oRound->surveys();
      
      if(!$oSurveys->isEmpty()){
        $aQuestions = [];
        // $oSurveys = $oSurveys->where('keypairs', '!=', '');
        $jsonQuestionData = $oSurveys->first()->keypairs;
        $aQuestionData = unserialize($jsonQuestionData);

        $oSurveyTemplate = surveys_template::findOrFail($oSurveys->first()->template_id);

        $aQuestionTags = NULL;
        if($oSurveyTemplate->question_tags != NULL){
          $aQuestionTags = unserialize($oSurveyTemplate->question_tags);
        }

        $aQuestionData = array_filter(array_merge(array(0), $aQuestionData));
        foreach($aQuestionData as $nQuestionNumber => $aQuestion){
          $aQuestions[$nQuestionNumber] = [
            'id'        => $nQuestionNumber, 
            'question'  => "Q.".$nQuestionNumber." ".$aQuestion['ques'],
            'tag'       => ''
          ];
          if($aQuestionTags != NULL && array_key_exists($nQuestionNumber, $aQuestionTags)){
            $aQuestions[$nQuestionNumber]['tag'] = $aQuestionTags[$nQuestionNumber];
          }
        }
        $aQuestions = collect($aQuestions);

        $oDatatable = Datatables::of($aQuestions)
        ->addColumn('bulk_select', function($aQuestions){
          return '<input type="checkbox" value="1" class="minimal entity_chkbox" name="questions['.$aQuestions['id'].'][export]" checked tabindex="0">';
        })
        ->editColumn('name', function($aQuestions){
          return '<input type="text" class="form-control" name="questions['.$aQuestions['id'].'][question_tag]" placeholder="'.$aQuestions['question'].'" value="'.$aQuestions['tag'].'">';
        });

        return $oDatatable->make(true);
      }
    }
  	//return json_encode(['output'=>'', 'selected'=>'']);
  }
  
    public function callShowAutoExportedSurvey(Request $oRequest, $sDateDir = NULL, $sHourDir = NULL){

    $oLoggedInUser = Auth::user();

    $sExportedSurveyFolderPath = public_path(config('constants.EXPORTFOLDER')).'/'.strtoupper($oLoggedInUser->client_code);

    if($sDateDir != NULL){
      $sExportedSurveyFolderPath .= '/'.$sDateDir;
    }

    if($sDateDir != NULL && $sHourDir != NULL){
      $sExportedSurveyFolderPath .= '/'.$sHourDir;
    }
    // dd($sExportedSurveyFolderPath);

    $aDirectories = scandir($sExportedSurveyFolderPath, true);

    // dd($aDirectories);
    $aViewData = [
      'aDirectories'    => $aDirectories,
      'sDateDir'        => $sDateDir,
      'sHourDir'        => $sHourDir,
      'sCurrentDirPath' => $sExportedSurveyFolderPath
    ];

    if($oRequest->ajax()){
      $sHtml = \View::make('AdminView::surveys._more_exported_survey_list_ajax', $aViewData)->render();
      return response()->json([
        'success' => true,
        'message' => '',
        'data' => [
          'sHTML' => $sHtml,
        ]
      ]);
    }

    return \View::make('AdminView::surveys.exported_survey_list', $aViewData);
  }
}