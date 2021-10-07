<?php

namespace App\Http\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\XSSescapeRequest;

use DB;
use File;
use Datatables;
use Exception;
use App\Http\AppHelper;

// use ARS;
use App\User;
use App\AppData;
use App\Client;
use App\ContactType;
use App\ClientChain;
use App\Chain;
use App\Contact;
use App\Emailer;

class ClientController extends Controller
{
    public function callCreateClient(Request $oRequest)
    {
        // ARS::canOrFail('add_client');

        $nClientId = DB::table('INFORMATION_SCHEMA.TABLES')->where(['TABLE_SCHEMA' => session()->get('selected_database'), 'TABLE_NAME' => 'clients'])->first()->AUTO_INCREMENT;

        $aViewData = [
            'client_id' => $nClientId
        ];
        
        return view('AdminView::clients.create_client', $aViewData);
    }
    
    public function callSaveClient(Request $oRequest)
    {
        $this->validate($oRequest, [
            'client_name'   => 'required',
            'email'         => 'sometimes|required|email|unique:users,email,null,id',
        ]);

        $sDestinationPath = public_path(config('constants.CLIENTLOGOFOLDER'));

        if($oRequest->input('id') == '')
        {
            // ARS::canOrFail('add_client');
            $aInputs = $oRequest->all();
            
            DB::beginTransaction();

            $oUser = User::create([
                'email'         =>  $oRequest->email,
                'password'      =>  bcrypt(config('constants.DEFAULTPASSWORD')),
                'user_type'     =>  config('constants.USERTYPE.CLIENT'),
                'role'          =>  4,
                'status'        =>  TRUE
            ]);

            $this->UploadFile($oRequest, $aInputs, $sDestinationPath, 'client_logo', 'client_logo_name');

            $oClient = $oUser->client()->create([
                'client_name'   =>  $oRequest->client_name,
                'client_abbrev' =>  $oRequest->client_abbrev,
                'client_logo'   =>  $aInputs['client_logo'],
                'client_logo_name' =>  $aInputs['client_logo_name'],
                'notes'         =>  $oRequest->notes,
            ]);
            
            DB::commit();

            $aEmailData = [
                'email'         =>  $oRequest->email,
                'client_name'   =>  $oRequest->client_name,
                'password'      =>  config('constants.CLIENTPASSWORD'),
            ];

            Emailer::SendEmail('admin.new_client',$aEmailData);
      
            return redirect()->route('edit.client',[$oClient->id])->with('success', 'Client added successfully!');
        }
        else{
            // ARS::canOrFail('edit_client');
            $oClient = Client::where(['id' => $oRequest->id ])->first();

            $inputs = $oRequest->all();

            $this->UploadFile($oRequest, $inputs, $sDestinationPath, 'client_logo', 'client_logo_name');

            $oClient->update($inputs);

            return redirect('clients')->with('success', 'Client saved successfully!');   
        } 
    }
    
    public function callShowEditClientForm(Request $oRequest, $nIdClient){

        // ARS::canOrFail('edit_client');
    
        $oClient = Client::findorFail($nIdClient);
    
        $oClient->updated = AppHelper::getLocalTimeZone($oClient->updated_at,AppHelper::TIMESTAMP_FORMAT,$oRequest)->format(AppHelper::DATE_DISPLAY_FORMAT); 
    
        $oClient->created = AppHelper::getLocalTimeZone($oClient->created_at,AppHelper::TIMESTAMP_FORMAT,$oRequest)->format(AppHelper::DATE_DISPLAY_FORMAT);
    
        $oApp = new AppData;
    
        $nEntityType = $oApp->entity_types['client'];
    
        $aContactType = ['' => 'Select Contact'] + $oApp->contact_types['client']; //// get contact types of clients
    
        if($oClient->client_logo != '' || $oClient->client_logo != null){
          $oClient->client_logo = asset('public'.config('constants.CLIENTLOGOFOLDER').'/'.$oClient->client_logo);
        }else{
            $oClient->client_logo = null;
        }
    
        $nContactCount = DB::table('contacts')->where('entity_type', '=', 1)->where('reference_id', '=', $nIdClient)->count();
    
        $aStates = ['' => 'Select State'] + DB::table('_list')->where('list_name','=','state')->lists('item_name','item_name','list_order');
    
        $aViewData = [
          'client'        =>  $oClient,
          'entity_type'   =>  $nEntityType,
          'contact_types' =>  $aContactType,
          'contact_count' =>  $nContactCount,
          'states'        =>  $aStates,
        ];
    
        return view('AdminView::clients.create_client', $aViewData);
    }
    
    public function callDeleteClient(Request $oRequest){

        // ARS::canOrFail('delete_client');
    
        try{
          $nIdClient = $oRequest->id;
    
          $oClient = Client::find($nIdClient);
          
          $this->callDeleteClientLogo($nIdClient);
          
          $oUser = User::find($oClient->id_user)->update(['status' => false]);
    
          Contact::where(['entity_type' => 1, 'reference_id' => $nIdClient])->delete();
    
          $oClient->delete();
    
          return response()->json([
            'success' => true,
            'message' => trans('messages.client_delete_success'),
          ]);
        }
        catch(Exception $oException){
          if($oException instanceof \PDOException )
          {
            $nErrorCode = $oException->getCode();
            if($nErrorCode == 23000){
              return response()->json([ 
                'status'  => false,
                'message' => trans('messages.client_has_chain_error_msg'),
              ], 422);
            }
          }
        }
    }
    
    public function callShowClientList(Request $oRequest){ 

        // ARS::canOrFail('view_client');
    
        if($oRequest->isMethod('GET')){
          $bIsDataAvailable = parent::isDataAvailable('client','create.client');
    
          if($bIsDataAvailable === true)
          {
            $aClients = ['' => 'Select Client'] + Client::lists('client_name','id')->all();
    
            $aViewData = [
              'client_list' => $aClients
            ];
    
            return view('AdminView::clients.clients', $aViewData);
          }
    
          return $bIsDataAvailable; 
        }
    
        $oClients = Client::from('clients as c')
        ->leftJoin('users as u', 'u.id', '=', 'c.id_user')
        ->leftJoin('chains as ch', 'ch.client_id', '=', 'c.id')
        ->leftjoin('contacts as co', function ($join) {
          $join->on('co.reference_id', '=', 'c.id')
          ->where('co.entity_type', '=', '1')
          ->where('co.contact_type', '=', 'primary');
        })        
        ->groupBy('ch.client_id')
        ->select([
          'c.id',
          'c.client_name',
          'u.email',
          'c.client_logo',
          'co.city',
          'co.state',
          'co.zipcode',            
          'c.status'])
        ->groupBy('c.id');
    
        $oDataTable =  Datatables::of($oClients)
        ->editColumn('id', function ($oClients) {
            $nClientCode = format_code($oClients->id);
            //if(ARS::canOrNot('edit_client')){
                return '<a href='.url("/clients-edit/").'/'.$oClients->id.'>'. $nClientCode.'</a>';
            //}
            return $nClientCode;
        })
        ->addColumn('action', function ($oClients) {
            //if(ARS::canOrNot('delete_client')){
                return '<button class="btn btn-box-tool" type="submit" name="remove_client" data-id="'.$oClients->id.'" value="delete" title="delete"><span class="fa fa-trash"></span></button>';
            //}
        })
        ->addColumn('chains', function ($oClients) {
            $oChains = $oClients->chains()->get();
            $sHtml = "";
    
            if($oChains){
                if($oClients->status == TRUE){                    
                    foreach($oChains as $oChain){
                        // if(ARS::canOrNot('edit_chain')){
                            $sHtml .= '<a href='.route('edit.chain', ['id' => $oChain->id]).'><span class="text text-success"><i class="fa fa-chain"></i></span> '.$oChain->chain_name.'</a><br>';
                        // }else{
                            //$sHtml .= '<span class="text text-success"><i class="fa fa-chain"></i></span> '.$oChain->chain_name.'<br>';
                        // }
                    }
                    //if(ARS::canOrNot('add_chain')){
                        $sHtml .= '<a href='.url("/chains-edit/").'/client/'.$oClients->id.' class="text text-gray"><i class="fa fa-plus"></i> '.trans('messages.new').'</a><br>';
                    //}
                    return $sHtml;
                }
                else{
                    return;
                }
            }else{
                if($oClients->status == true){
                    $sHtml .= '<a href='.url("/chains-edit/").'/client/'.$oClients->id.' class="text text-gray"><i class="fa fa-plus"></i> '.trans('messages.new').'</a><br>';
                    return $sHtml;
                }
                else{
                    return '';
                }
            }
        })
        ->editColumn('client_logo', function ($oClients) {
            $sClientLogo = getImage(config('constants.CLIENTLOGOFOLDER').'/'.$oClients->client_logo);
            return $sClientLogo;
        })
        ->editColumn('location', function ($oClients) {
          return format_location($oClients->city, $oClients->state, $oClients->zipcode);
        })
        ->editColumn('status', function ($oClients) {
          if($oClients->status == TRUE){
            return '<span class="label label-success">'.trans('messages.active').'</span>';
          }else{
            return '<span class="label label-danger">'.trans('messages.inactive').'</span>';
          }
        });
    
        if ($oRequest->status != '') {
          $bStatus = $oRequest->status;
          $oDataTable->where('c.status', 'like', "$bStatus%"); 
        }
    
        $sSearchStr = $oRequest->get('search')['value'];
    
        if (preg_match("/^".$sSearchStr."/i", 'Active', $match)) :
          $oDataTable->filterColumn('c.status', 'where', '=', "1");
        endif;
    
        if (preg_match("/^".$sSearchStr."/i", 'Inactive', $match)) :
          $oDataTable->filterColumn('c.status', 'where', '=', "0");
        endif;
    
        $oDataTable->filterColumn('co.city', 'whereRaw', "CONCAT(co.city,',',co.state,' ',co.zipcode) like ? ", ["%$sSearchStr%"]);
    
        return $oDataTable->make(true);
    }
    
    public function callDeleteClientLogo($nIdClient)
    {
        $oClient = Client::find($nIdClient);
    
        $sFilePath = public_path(config('constants.CLIENTLOGOFOLDER'));
        
        $sFileName = $oClient->client_logo;
        
        if(is_file($sFilePath.'/'.$sFileName) && file_exists($sFilePath.'/'.$sFileName))
        {
          File::delete($sFilePath.'/'.$sFileName);
          $oClient->update(['client_logo' => '']);
    
          return response()->json([
            'success' => true,
            'message' => trans('client_logo_delete_success'), //"Logo removed successfully"
          ]);  
        }
    }

    public function UploadFile($oRequest, &$inputs, $sDestinationPath, $file, $file_token)
    {
        $oClient = NULL;
        $oClient = Client::find($oRequest->id);
    
        if(Input::hasFile($file) || $oRequest->$file_token != "")
        {
          if($oClient != NULL && $oClient->$file != "")
          {
            $aExistingFile = $oClient->$file;
            if(!is_array($aExistingFile)){
              $aExistingFiles[] = $aExistingFile;
            }        
            foreach($aExistingFiles as $aExistingFile)
            {
              if(file_exists($sDestinationPath.'/'.$aExistingFile))
              {
                File::delete($sDestinationPath.'/'.$aExistingFile);
              }
            }
            $inputs[$file] = $inputs[$file_token] = "";
          }
    
          if(Input::hasFile($file))
          {
            \File::makeDirectory($sDestinationPath,0777,true,true);
    
            $oFileToUpload = $oRequest->file($file);
            if(!is_array($oFileToUpload)){
              $aFilesToUpload[] = $oFileToUpload;
            }else{
              $aFilesToUpload = $oFileToUpload;
            }
    
            $sNewFileSrc = "";
            $sNewFileName = "";
    
            foreach($aFilesToUpload as $oFilesToUpload)
            {
              if($oFilesToUpload->isValid())
              {
                $name       = $sNewFileName = $oFilesToUpload->getClientOriginalName();
                $extension  = $oFilesToUpload->getClientOriginalExtension();
                $file_name  = md5(uniqid().time()).'_'.$name;
    
                $img = \Image::make($oFilesToUpload->getRealPath());
                $img->resize(600, 600, function ($constraint) {
                  $constraint->aspectRatio();
                  $constraint->upsize();
                });
    
                $img->save($sDestinationPath.'/'.$file_name,'70');
                $sNewFileSrc = $file_name;
              }
            }                
            $inputs[$file]  = $sNewFileSrc;
            $inputs[$file_token] = $sNewFileName;
    
          }elseif($oRequest->$file_token != "")
          {
            $inputs[$file] = $inputs[$file_token] = "";
          }
        }else{
          if($oClient != NULL){
            $inputs[$file] = $oClient->$file;
            $inputs[$file_token] = $oClient->$file_token;
          }else{
            $inputs[$file] ="";
            $inputs[$file_token] = "";
          }
        }
    }
}
