<?php
namespace App\Http\Controllers\FieldRep;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\Client;
use App\Libraries\Document;


class DocumentController extends Controller
{
	protected $aMSOfficeExtentions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

	public function callGetDocumentList(Request $oRequest, $nIdClient = NULL,  $nIdDocument = NULL){

	$nIdParent = $nIdDocument;

    $oClients = $oDocuments =  NULL;
    $aBreadCrumbs = [];

    $oAvailableClientsIds = Client::from('clients as c')
    ->leftjoin('chains as ch', 'ch.client_id', '=', 'c.id')
    ->leftjoin('sites as s', 's.chain_id', '=', 'ch.id')
    ->leftjoin('assignments as a', 's.id', '=', 'a.site_id')
    ->where(['s.fieldrep_id' => Auth::id()])
    ->orWhere(function($query){
      $query->where(['a.fieldrep_id' => Auth::id(), 'a.is_approved' => false]);
    })
    ->groupBy('c.id')
    ->pluck('c.id')
    ->toArray();

    if($nIdClient == NULL){
      $oClients = Client::from('clients as c')
      ->leftjoin('chains as ch', 'ch.client_id', '=', 'c.id')
      ->leftjoin('sites as s', 's.chain_id', '=', 'ch.id')
      ->leftjoin('assignments as a', 's.id', '=', 'a.site_id')
      ->where(['s.fieldrep_id' => Auth::id()])
      ->orWhere(function($query){
       $query->where(['a.fieldrep_id' => Auth::id(), 'a.is_approved' => false]);
     })
      ->groupBy('c.id')
      ->get(['c.id', 'c.client_name']);
    }else{
      $oClient = Client::where(['id' => $nIdClient])->whereIn('id', $oAvailableClientsIds)->first();
      if($oClient == null){
        return redirect(route('fieldrep.document-list'));
      }
    }

    if(isset($nIdParent)){
      $oParentFolder = Document::where(['id_document' => $nIdParent])->whereIn('id_client', $oAvailableClientsIds)->first();
      if($oParentFolder == NULL){
        return redirect(route('fieldrep.document-list', ['nIdClient' => $nIdClient]));
      }
      $oDocuments = $oParentFolder->children;
    }else{
      $oDocuments = Document::where(['id_client' => $nIdClient, 'id_parent' => NULL])->whereIn('id_client', $oAvailableClientsIds)->get();
    }

    $aViewData = compact('oDocuments', 'nIdClient', 'nIdParent', 'oClients', 'nIdClient');

    if($oRequest->ajax()){
      $sHtml = \View::make('fieldrep.documents._more_document_list_ajax', $aViewData)->render();
      return response()->json([
        'success' => true,
        'message' => '',
        'data' => [
          'sHTML' => $sHtml,
          'nIdClient' => $nIdClient,
        ]
      ]);
    }
    return \View::make('fieldrep.documents.document_list', $aViewData);

	}

	public function callFilePreview(Request $oRequest, $nIdDocument, $sDisplayFileName)
	{
		$oDocument = Document::findOrFail($nIdDocument);
		$sFileName = $oDocument->file_name;
		$oLoggedinUser = Auth::user();
		if(!empty($sFileName) && file_exists(public_path(config('constants.DOCUMENTFOLDER').'/'.strtoupper($oLoggedinUser->client_code).'/'.$sFileName)))
		{

			$sFileUrl = asset('public'.config('constants.DOCUMENTFOLDER').'/'.$oLoggedinUser->client_code.'/'.$sFileName );
			$aFileType = explode('.', $sFileName);
			if(in_array(last($aFileType), $this->aMSOfficeExtentions))
			{
				$sDisplayUrl = "https://view.officeapps.live.com/op/view.aspx?src=".urlencode($sFileUrl);
			}else if(mb_strtolower(last($aFileType)) == "pdf"){
				$oFile = file_get_contents(public_path(config('constants.DOCUMENTFOLDER').'/'.strtoupper($oLoggedinUser->client_code).'/'.$sFileName));
				$oResponse = \Response::make($oFile, 200);
				$oResponse->header('Content-Type', "application/pdf");
				return $oResponse;
			}else if(in_array (mb_strtolower(last($aFileType)), array("png", "jpg", "jpeg"))){
				$oFile = file_get_contents(public_path(config('constants.DOCUMENTFOLDER').'/'.strtoupper($oLoggedinUser->client_code).'/'.$sFileName));
				$oResponse = \Response::make($oFile, 200);
				$oResponse->header('Content-Type', "image/".mb_strtolower(last($aFileType)));
				return $oResponse;
			}
		}
		$aViewData = compact('sDisplayUrl', 'oDocument');
		return \View::make('WebView::fieldrep.documents.preview_file', $aViewData);
	}

	public function callDownloadFile(Request $oRequest, $nIdDocument, $sDisplayFileName)
	{	
		$oDocument = Document::findOrFail($nIdDocument);
		$sFileName = $oDocument->file_name;
		$oLoggedinUser = Auth::user();

		if(!empty($sFileName) && file_exists(public_path(config('constants.DOCUMENTFOLDER').'/'.strtoupper($oLoggedinUser->client_code).'/'.$sFileName)))
		{
			$oFile = file_get_contents(public_path(config('constants.DOCUMENTFOLDER').'/'.strtoupper($oLoggedinUser->client_code).'/'.$sFileName));

			$oResponse = \Response::make($oFile, 200);
			$oResponse->header('Content-Type', 'application/octet-stream');
			return $oResponse;
		}
		else
		{
			abort(404);
		}
	}
}
