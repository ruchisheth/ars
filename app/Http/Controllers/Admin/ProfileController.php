<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\URL;

use App\Http\AppHelper;

use Html;

use Auth;	
use App\AppData,
App\Client,
App\AdminClient,
App\User,
Validator,
DB;

class ProfileController extends Controller
{
    public function getProfile(Request $request){
        $profile = [
        'id'    => Auth::user()->ClientDetails->id,
        'email' => Auth::user()->ClientDetails->email,
        'name'  => Auth::user()->UserDetails->name
        ];
        
        if(Auth::user()->hasRole('super_admin')){
            return view('super_admin.profile.profile',$profile);
        }
        return view('admin.profile.profile',$profile);
    }
    
    public function postProfile(Request $request){
    	$user_id = Auth::user()->UserDetails->user_id;
    	$this->validate($request, [
            'email'         =>  'required|email|unique:mysql.users,email,'.$user_id,
            'name'         =>  'required',
            //'password'      =>  'confirmed|min:6|max:15',            
            ]);
        $user = User::where(['id'=>$user_id])->first();
        $inputs['email']   =   $request->get('email');
        $user->update($inputs);

        $client = AdminClient::where(['user_id'=>$user_id])->first();
        $client->update(['name' => $request->get('name')]);
        
        // if($request->input('password') == ''){
     //        $inputs = $request->except(['_token','password','password_confirmation']);
     //    }else{
     //        $inputs = $request->except(['_token','password_confirmation']);
     //        $inputs['password'] = bcrypt($request->input('password'));
     //    }
        return redirect()->route('admin.home')->with('success', 'Profile updated successfully!'); 
    }
}
