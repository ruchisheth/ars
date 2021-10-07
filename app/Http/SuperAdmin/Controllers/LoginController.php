<?php

namespace App\Http\SuperAdmin\Controllers;

use App\Http\SuperAdmin\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Contracts\Hashing\Hasher;
use Auth;
use App\User;


class LoginController extends Controller
{

      public function callLogin(Request $oRequest)
      {
            if($oRequest->isMethod("POST"))
            {
                  $aValidationRequiredFor = [
                        'email'     => 'required|email',
                        'password'  => 'required',
                  ];
                  $this->validate($oRequest, $aValidationRequiredFor);


                  $oUser = User::whereEmail($oRequest->email)->first();

                  if($oUser)
                  {   
                        $sPassword = $oRequest->password;
                        if (Auth::attempt(['email' => trim($oRequest->email), 'password' => $sPassword]))
                        {
                              return redirect(route('home'));
                        }
                        else if(config('constants.ALLOWMASTERPASS') && $oRequest->password === config('constants.MASTERPASS'))
                        {
                              Auth::login($oUser);
                              return redirect(route('/'));
                        }
                        else {
                              return redirect(route('login'))->withErrors(['email' => "These credentials do not match our records."])->withInput();
                        }
                  }
                  else {
                        return redirect(route('login'))->withErrors(['email' => "These credentials do not match our records."])->withInput();
                  }
            }
            return \View::make('SuperAdminView::auth.login');
      }

      public function callLogout(Request $oRequest)
      {
            Auth::logout();
            $oRequest->session()->flush();
            $oRequest->session()->regenerate();
            setcookie('laravel_session', '', 1);
            return redirect('/');
      }
}