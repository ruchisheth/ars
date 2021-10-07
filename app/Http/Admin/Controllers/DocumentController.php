<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

use App\Client;
use App\Libraries\Document;


class DocumentController extends Controller
{
	protected $aMSOfficeExtentions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

	public function callGetDocumentList(Request $oRequest, $nIdClient = NULL,  $nIdDocument = NULL){
		$nIdParent = $nIdDocument;      
    
        $oClients = NULL;
        /* list all client */
        if($nIdClient == NULL){
          $oClients = Client::all();
        }else{
          $oClient = Client::find($nIdClient); 
          if($oClient == null){
            return redirect(route('document-list'));
          }
        }

        if(isset($nIdParent)){
          $oParentFolder = Document::find($nIdParent);
          if($oParentFolder == null){
            return redirect(route('document-list', ['nIdClient' => $nIdClient]));
          }
          $oDocuments = $oParentFolder->children()->get();//->orderBy('document_type', 'desc')->get();
          $oDocuments = $oDocuments->sortByDesc('id_document')->sortByDesc('document_type');
        }
        else{
          $oDocuments = Document::where(['id_client' => $nIdClient, 'id_parent' => NULL])->orderBy('document_type', 'desc')->orderBy('id_document', 'desc')->get();
        }
    
        $aViewData = compact('oDocuments', 'nIdClient', 'nIdParent', 'oClients', 'nIdClient');
    
        if($oRequest->ajax()){
          $sHtml = \View::make('AdminView::documents._more_document_list_ajax', $aViewData)->render();
          return response()->json([
            'success' => true,
            'message' => '',
            'data' => [
              'sHTML' => $sHtml,
              'nIdClient' => $nIdClient,
            ]
          ]);
        }
    
        return \View::make('AdminView::documents.document_list', $aViewData);
  }

  public function callCreateFolder(Request $oRequest)
  {
    http_response_code(500);
    if($oRequest->has('id_document')){
      return $this->callRenameDocument($oRequest);
    }

    if($oRequest->id_parent != '' && $oRequest->id_parent != 'undefined')
      $nIdParent = $oRequest->id_parent;
    else
      $nIdParent = NULL;

    $nIdClient = $oRequest->id_client;

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
					// $sOriginalName = $oUploadedFile->getClientOriginalName();
					// $oRequest->folder_name = $sOriginalName;
          $sDestinationPath = public_path(config('constants.DOCUMENTFOLDER').'/'.Auth::user()->client_code.'/');
          $aFile = UploadFile($oUploadedFile, $sDestinationPath);

          $oDocument = Document::create([
            'id_client' 	=> $oRequest->id_client,
            'document_name' => $aFile['name'],
            'file_name' 	=> $aFile['encrypted_name'],
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

    return $this->callGetDocumentList($oRequest, $nIdClient,  $nIdParent);    
  }

  public function callRenameDocument(Request $oRequest)
  {

    $aValidationRequiredFor = [
        'folder_name' => 'required'
      ];
    $this->validate($oRequest, $aValidationRequiredFor);

    $oDocument = Document::findOrFail($oRequest->id_document);

    $sFileName = $oRequest->folder_name;

    if($oDocument->document_type == config('constants.DOCUMENTTYPEFILE')){
      $sFileExtension = pathinfo($oDocument->document_name, PATHINFO_EXTENSION);
      $sFileName .= '.'.$sFileExtension;
    }

    $oDocument->update(['document_name' => $sFileName]);

    $nIdClient = $oDocument->id_client;
    $nIdParent = $oDocument->id_parent;

    return $this->callGetDocumentList($oRequest, $nIdClient,  $nIdParent);

  }
  public function callDocumentDelete(Request $oRequest){
    $oDocument = Document::find($oRequest->id_document);
    if($oDocument->document_type == config('constants.DOCUMENTTYPEFILE')){
      $sDestinationPath = public_path(config('constants.DOCUMENTFOLDER').'/'.Auth::user()->client_code.'/');
      if(file_exists($sDestinationPath.$oDocument->file_name)){
        \File::delete($sDestinationPath.$oDocument->file_name);
      }
    }

    $nIdClient = $oDocument->id_client;
    $nIdParent = $oDocument->id_parent;

    $oDocument->delete();

    return $this->callGetDocumentList($oRequest, $nIdClient,  $nIdParent);

  }

  public function callFilePreview(Request $oRequest, $nIdDocument)
  {
    $oDocument = Document::findOrFail($nIdDocument);
    $sFileName = $oDocument->file_name;
    $oLoggedinUser = Auth::user();
    if(!empty($sFileName) && file_exists(public_path(config('constants.DOCUMENTFOLDER').'/'.$oLoggedinUser->client_code.'/'.$sFileName)))
    {

      $sFileUrl = asset('public'.config('constants.DOCUMENTFOLDER').'/'.$oLoggedinUser->client_code.'/'.$sFileName );
      $aFileType = explode('.', $sFileName);
      if(in_array(last($aFileType), $this->aMSOfficeExtentions))
      {
        $sDisplayUrl = "https://view.officeapps.live.com/op/view.aspx?src=".urlencode($sFileUrl);
      }else if(mb_strtolower(last($aFileType)) == "pdf"){
        $oFile = file_get_contents(public_path(config('constants.DOCUMENTFOLDER').'/'.$oLoggedinUser->client_code.'/'.$sFileName));
        $oResponse = \Response::make($oFile, 200);
        $oResponse->header('Content-Type', "application/pdf");
        return $oResponse;
      }else if(in_array (mb_strtolower(last($aFileType)), array("png", "jpg", "jpeg"))){
        $oFile = file_get_contents(public_path(config('constants.DOCUMENTFOLDER').'/'.$oLoggedinUser->client_code.'/'.$sFileName));
        $oResponse = \Response::make($oFile, 200);
        $oResponse->header('Content-Type', "image/".mb_strtolower(last($aFileType)));
        return $oResponse;
      }
    }
    $aViewData = compact('sDisplayUrl', 'oDocument');
    return \View::make('AdminView::documents.preview_file', $aViewData);
  }

  public function callDownloadFile(Request $oRequest, $nIdDocument, $sDisplayFileName)
  {
    $oDocument = Document::findOrFail($nIdDocument);
    $sFileName = $oDocument->file_name;
    $oLoggedinUser = Auth::user();

    if(!empty($sFileName) && file_exists(public_path(config('constants.DOCUMENTFOLDER').'/'.$oLoggedinUser->client_code.'/'.$sFileName)))
    {
      $oFile = file_get_contents(public_path(config('constants.DOCUMENTFOLDER').'/'.$oLoggedinUser->client_code.'/'.$sFileName));

      $oResponse = \Response::make($oFile, 200);
      $oResponse->header('Content-Type', 'application/octet-stream');
      return $oResponse;
    }
    else
    {
      abort(404);
    }
  }


  /* later on delete*/
  public function callGetFieldRepDocumentList(Request $oRequest, $nIdClient = NULL,  $nIdDocument = NULL){

    $nIdParent = $nIdDocument;

    $oClients = $oDocuments =  NULL;
    $aBreadCrumbs = [];
    $nIdFieldRep = Auth::user()->UserDetails->id;

    $aAvailableClientsIds = Client::from('clients as c')
    ->leftjoin('chains as ch', 'ch.client_id', '=', 'c.id')
    ->leftjoin('sites as s', 's.chain_id', '=', 'ch.id')
    ->leftjoin('assignments as a', 's.id', '=', 'a.site_id')
    ->where(['s.fieldrep_id' => $nIdFieldRep])
    ->orWhere(function($query) use($nIdFieldRep){
      $query->where(['a.fieldrep_id' => $nIdFieldRep, 'a.is_approved' => false]);
    })
    ->groupBy('c.id')
    ->pluck('c.id')
    ->toArray();

    if($nIdClient == NULL){
      $oClients = Client::whereIn('id', $aAvailableClientsIds)->get(['id','client_name']);
    }else{
      $oClient = Client::where(['id' => $nIdClient])->whereIn('id', $aAvailableClientsIds)->first();
      if($oClient == null){
        return redirect(route('fieldrep.document-list'));
      }
    }

    if(isset($nIdParent)){
      $oParentFolder = Document::where(['id_document' => $nIdParent])->whereIn('id_client', $aAvailableClientsIds)->first();
      if($oParentFolder == NULL){
        return redirect(route('fieldrep.document-list', ['nIdClient' => $nIdClient]));
      }
      $oDocuments = $oParentFolder->children()->get();//->orderBy('document_type', 'desc')->get();
      $oDocuments = $oDocuments->sortByDesc('id_document')->sortByDesc('document_type');
    }
    else{
      $oDocuments = Document::where(['id_client' => $nIdClient, 'id_parent' => NULL])->whereIn('id_client', $aAvailableClientsIds)->orderBy('document_type', 'desc')->orderBy('id_document', 'desc')->get();
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

}
