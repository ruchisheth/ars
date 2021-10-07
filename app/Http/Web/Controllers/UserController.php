<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Contracts\Hashing\Hasher;
use Auth;

use App\Admin;
use App\User;
use App\Client;
use App\Site;
use App\Assignment;

class UserController extends Controller
{
	function __construct(){
            parent::__construct();
      }

      public function callLogin(Request $oRequest,  Hasher $hasher)
      {

            if($oRequest->isMethod('POST')){

                  $aValidationRequiredFor = [
                        'email' => 'required|email',
                        'password' => 'required',
                        'client_code' => 'required',
                  ];

                  $this->validate($oRequest, $aValidationRequiredFor);

                  /* Change DB Connection */
                  $oAdmin = Admin::where(['client_code' => $oRequest->client_code])->first();
                  if($oAdmin == NULL){
                        return redirect()->back()->withErrors(['email' => "These credentials do not match our records."])->withInput();
                  }
                  $sSchemaName = config('constants.DB_PREFIX').$oAdmin->db_version;
                  parent::setDBConnection($sSchemaName);

                  $oUser = User::whereEmail($oRequest->email)->first();

                  if($oUser){

                        if($hasher->check($oRequest->password, $oUser->password)){
                              if($oUser->status == FALSE){
                                    return redirect()->back()->withErrors(['email' => "Sorry! Your account is inactive."])->withInput();
                              }
                        }
                  }

                  $aCredential = [
                        'email' => $oRequest->email,
                        'password' => $oRequest->password
                  ];

                  $bRemember = ($oRequest->has('remember')) ? TRUE : FALSE;


                  if (Auth::attempt($aCredential, $bRemember)) {
                        $oAuthUser = Auth::user();
                        if($oAuthUser->user_type == config('constants.USERTYPE.CLIENT')){
                              return redirect(route('client.dashboard'));
                        }
                        if($oAuthUser->user_type == config('constants.USERTYPE.FIELDREP')){
                              return redirect(route('fieldrep.dashboard'));
                        }
                  }
            }
            return \View::make('WebView::auth.login');
      }
      public function callLogout(Request $oRequest)
      {
            Auth::logout();
            $oRequest->session()->flush();
            $oRequest->session()->regenerate();
            // setcookie('laravel_session', '', 1);
            // setcookie('AWSELB', '', 1);
            return redirect('/');
      }

     


      public function callGetUserTimezone(Request $oRequest){

            $sTimezone = $oRequest->timezone;
            $oRequest->session()->put('timezone', $sTimezone);
            $oRequest->session()->put('local_tz', $sTimezone);
      }
}