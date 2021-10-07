<?php

namespace App\Http\Web\Controllers;

use App\Http\Admin\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

use App\Client;
use App\Libraries\Document;


class ResourceController extends Controller
{
	protected $aMSOfficeExtentions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

	public function callGetResourceList(Request $oRequest){
		$oClients = Client::all();

		$aViewData = [
			'oClients' => $oClients,
		];

		return \View::make('WebView::fieldrep.documents.document_list', $aViewData);
	}

	public function callGetDocumentList(Request $oRequest, $nIdClient = NULL,  $nIdDocument = NULL){

		$nIdParent = $nIdDocument;
		
		$oClients = NULL;

		if($nIdClient == NULL){
			$oClients = Client::all();
		}

		if(isset($nIdParent)){
			$oParentFolder = Document::find($nIdParent);
			$oDocuments = $oParentFolder->children;
		}else{
			$oDocuments = Document::where(['id_client' => $nIdClient, 'id_parent' => NULL])->get();
		}
		
		$aViewData = compact('oDocuments', 'nIdClient', 'nIdParent', 'oClients', 'nIdClient');

		if($oRequest->ajax()){
			$sHtml = \View::make('WebView::fieldrep.documents._more_document_list_ajax', $aViewData)->render();
			return response()->json([
				'success' => true,
				'message' => '',
				'data' => [
					'sHTML' => $sHtml,
					'nIdClient' => $nIdClient,
				]
			]);
		}

		return \View::make('WebView::fieldrep.documents.document_list', $aViewData);

	}

	public function callCreateFolder(Request $oRequest)
	{
		if($oRequest->id_parent != '' && $oRequest->id_parent != 'undefined')
			$nIdParent = $oRequest->id_parent;
		else
			$nIdParent = NULL;

		$nIdClient = $oRequest->id_client;

		// $oDocument= Document::find($oRequest->id_parent);

		$sFileName = '';
		
		if($oRequest->doc_type == config('constants.DOCUMENTTYPEFOLDER'))
		{
			$aValidationRequiredFor = [
				'folder_name' => 'required'
			];
		}
		if($oRequest->doc_type == config('constants.DOCUMENTTYPEFILE')){
			$aValidationRequiredFor = [
				'file' => 'required|max:40000'
			];
			// if ($oRequest->hasFile('file'))
			// {
			// 	$oRequest->offsetSet('extention', mb_strtolower($oRequest->file('file')->getClientOriginalExtension()));
			// 	$aValidationRequiredFor['extention'] = 'required|in:bmp,jpg,jpeg,png,svg,doc,docx,pages,rtf,txt,wp,numbers,xls,xlsx,csv,key,ppt,pptx,pps,mdb,acc,accdb,pdf,zip,rar';
			// 	$aValidationRequiredFor['extention'] = 'required|in:bmp,jpg,jpeg,png,svg,doc,docx,pages,rtf,txt,wp,numbers,xls,xlsx,csv,key,ppt,pptx,pps,mdb,acc,accdb,pdf,zip,rar';
			// }
		}

		$this->validate($oRequest, $aValidationRequiredFor);
		if($oRequest->doc_type == config('constants.DOCUMENTTYPEFILE'))
		{
			$aUploadedFiles = $oRequest->file('file');
			foreach ($aUploadedFiles as $oUploadedFile) {

				if($oUploadedFile != ''){
					$sOriginalName = $oUploadedFile->getClientOriginalName();
					$oRequest->folder_name = $sOriginalName;
                	$sExtension = $oUploadedFile->getClientOriginalExtension(); // getting image extension
                	$sFileName = str_random(8).'_'.time().'.'.$sExtension; // renameing image


                	$oDocument = Document::create([
                		'id_client' 	=> $oRequest->id_client,
                		'document_name' => $oRequest->folder_name,
                		'file_name' 	=> $sFileName,
                		'id_parent' 	=> $nIdParent,
                		'document_type' => $oRequest->doc_type,
                	]);

                }
            }

        }else if($oRequest->doc_type == config('constants.DOCUMENTTYPEFOLDER')){

        	$oDocument = Document::create([
        		'id_client' 	=> $oRequest->id_client,
        		'document_name' => $oRequest->folder_name,
        		'file_name' 	=> $sFileName,
        		'id_parent' 	=> $nIdParent,
        		'document_type' => $oRequest->doc_type,
        	]);
        	
        }

        if(isset($nIdParent)){
        	$oParentFolder = Document::find($nIdParent);
        	$oDocuments = $oParentFolder->children;
        	// $oDocuments = $oParentFolder->getImmediateDescendants();
        }else{
        	$oDocuments = Document::where(['id_client' => $nIdClient, 'id_parent' => NULL])->get();
        }

        $aViewData = compact('oDocuments', 'nIdClient', 'nIdParent', 'nIdClient');

        $sHtml = \View::make('AdminView::resources.document_list_ajax', $aViewData)->render();

        return response()->json([
        	'success'	=> true,
        	'message'	=> '',
        	'data'		=> [
        		'sHTML' => $sHtml,
        		'nIdClient' => $nIdClient,
        	]
        ]);
    }

    public function callDocumentDelete(Request $oRequest){
    	http_response_code(500);
    	$oDocument = Document::find($oRequest->id_document)->delete();

    	return response()->json([
    		'success'	=> true,
    		'message'	=> '',
    		'data'		=> []
    	]);
    	
    }

    public function callFilePreview(Request $oRequest, $nIdDocument){
    	$oDocument = Document::find($nIdDocument);
    	$sFileName = $oDocument->file_name;
    	$oLoggedinUser = Auth::user();
    	
    	if(!empty($sFileName))
    	{
    		$sFileUrl = asset('public'.config('constants.DOCUMENTFOLDER').'/'.$oLoggedinUser->client_code.'/'.$sFileName );
    		$aFileType = explode('.', $sFileName);
    		if(in_array(last($aFileType), $this->aMSOfficeExtentions))
    		{
    			$sDisplayUrl = "https://view.officeapps.live.com/op/view.aspx?src=".urlencode($sFileUrl);
    		}
    	}

    	$aViewData = compact('sDisplayUrl', 'oDocument');

    	return \View::make('AdminView::resources.preview_file', $aViewData);
    }


}
