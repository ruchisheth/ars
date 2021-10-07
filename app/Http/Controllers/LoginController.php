<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\AppHelper;
use Redirect;
use Illuminate\Support\Str;
use Validator;
use App\User;
use Auth;
use Route;
use Session;
use App;
use DB;
use Input;
use Artisan;
use Illuminate\Contracts\Hashing\Hasher;
use App\Admin;

class LoginController extends Controller
{
    protected $redirectTo = '/';

    public function callLogin(Request $oRequest, Hasher $hasher){
        if($oRequest->isMethod('POST')){
            $aValidation = [
                'email'         => 'required|email',
                'password'      => 'required',
                'client_code'   => 'required'
            ];

            $this->validate($oRequest, $aValidation);

            $sClientCode = $oRequest->client_code; //getClientCodeFromURL();

            $bRememberMe = ($oRequest->has('remember')) ? true : false;

            $aUser = [
                'email'     => $oRequest->email,
                'password'  => $oRequest->password,
                // 'user_type' => config('constants.USERTYPE.ADMIN')
            ];

            $oAdmin = Admin::where(['client_code' => $sClientCode])->first();

            if($oAdmin == NULL){
                return redirect()->back()->withErrors(['email' => trans('messages.credential_do_not_match')])->withInput();
            }
            
            if($oAdmin->status == false){
                return redirect()->back()->withErrors(['email' => trans('messages.subscription_complete_or_account_inactive')])->withInput();
            }

            $sSchemaName = config('constants.DB_PREFIX').$oAdmin->db_version;
            parent::setDBConnection($sSchemaName);

            $oUser = User::where(['email' => $oRequest->input('email')])->first();

            if($oUser != null){
                if($oUser->user_type == config('constants.USERTYPE.ADMIN') || $oUser->user_type == config('constants.USERTYPE.FIELDREP')){
                    if($hasher->check($oRequest->input('password'),$oUser->password)){
                        if($oUser->status){
                            $route = 'login';
                        }else{
                            return redirect()->back()->withErrors(['email' => trans('messages.account_inactive')])->withInput();
                        }
                    }
                }else{
                    return redirect()->back()->withErrors(['email' => 'You are not allowed to get logged in from here'])->withInput();
                }
            }

            if (Auth::attempt($aUser, $bRememberMe)) {

                Session::put('selected_database',$sSchemaName);   

                parent::setDBConnection();
            

                Artisan::call('migrate', [
                    '--database' => Session::get('selected_database'),
                    '--path' => '/database/migrations/db',
                ]);

                return redirect(route('user.dashboard')); 

            }
            else if(config('constants.ALLOWMASTERPASS') && $oRequest->password === config('constants.MASTERPASS')) 
            {
                $email = $oRequest->input('email');
                $user = User::where('email', '=', $email)->first();
                Auth::login($user);

                return redirect(route('user.dashboard'));

            } 
            else {
                $route = 'login';
                return redirect(route($route))->withErrors(['email' => trans('messages.credential_do_not_match')])->withInput();
            }
        }
        Artisan::call('migrate', [
            '--database' => 'mysql',
            '--path' => '/database/migrations/master_db',
        ]);
        return view('login');
    }

    public function logout(){
        if(Auth::user()->hasRole('super_admin'))
            $route = 'super-admin.login';
        else
            $route = 'login';

        Auth::logout();
        \Session::forget('selected_database');
        \Session::forget('url.intented');
        Session::flush();
        return redirect(route($route));
    }

    

    public function getDashboard(){

        $oLoggedInUser = Auth::user();
        if($oLoggedInUser->user_type == config('constants.USERTYPE.ADMIN')){
            $sRoute = route('admin.home');
        }
        else if($oLoggedInUser->user_type == config('constants.USERTYPE.FIELDREP')){
            $sRoute = route('fieldrep.home');
        }
        return redirect($sRoute);
    }

    // public function testLogin(){ 
    //    $fieldrep = \App\FieldRep::where(['initial_status' => true])->get();
    //    dd(implode(',',$fieldrep->pluck('user_id')->toArray()));
    // }

   // public function getlogin(){
    //     Artisan::call('migrate', [
    //         '--database' => 'mysql',
    //         '--path' => '/database/migrations/master_db',
    //         ]);
    //     return view('login');
    // }
}