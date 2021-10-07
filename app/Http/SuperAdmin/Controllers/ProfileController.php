<?php

namespace App\Http\SuperAdmin\Controllers;

use App\Http\SuperAdmin\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Http\AppHelper;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Hash;

use Html;
use Auth;

use App\AppData,
App\Profile,
App\Client,
App\Admin,
App\User,
Validator,
DB;

class ProfileController extends Controller
{
    use ResetsPasswords;

    public function callProfile(Request $oRequest)
    {
        $oUser = Auth::user();
        $oProfile = Profile::firstOrCreate(['id_user' => $oUser->id]);
        if($oRequest->isMethod('POST')){
            $this->validate($oRequest, [
                'email'         =>  'required|email|unique:mysql.users,email,'.Auth::id(),
                'name'         =>  'required',
            ]);

            $oInputs = $oRequest->all();

            $oUser->update(['email' => $oRequest->email]);

            $sDestinationPath = public_path(config('constants.USERIMAGEFOLDER'));
            
            $this->UploadFile($oRequest, $oInputs, $sDestinationPath, 'profile_pic', 'profile_pic_name');
            $oProfile->update([
                'name' => $oRequest->name,
                'profile_pic' => $oInputs['profile_pic']
            ]);

            return redirect()->back()->with('success', trans('messages.profile_update_success')); 
        }

        $aViewData = [
            'oUser'     =>  $oUser,
            'oProfile'  =>  $oProfile,
        ];
        
        return view('SuperAdminView::profile', $aViewData);    
    }

    public function callResetPassword(Request $oRequest)
    {
        $oResponse = $this->reset($oRequest);
        return $oResponse;
    }

    public function reset(Request $oRequest)
    {
        $this->validate($oRequest, $this->rules(), $this->errorMessage());

         /*
            * Attempt to reset the user's password. 
            * If successful update the password on an actual user model and persist it to the database. 
            * else parse the error and return the response.
        */
         if($oRequest->has('current_password')){
            $user = $this->retrieveByCredentials($this->getOldCredentials($oRequest));
            if($user == null){
                return response()->json([
                    'message' => 'Invalid Current Password',
                ],422);        
            }
        }
        if($oRequest->id == ""){
            $user = Auth::user();
        }else{
            $user = FieldRep::find($oRequest->id)->users;
        }
        // $response = $this->broker()->reset(
        //     $this->credentials($request), 
        //     function ($user, $password) {
        //         $this->resetPassword($user, $password);
        //     });
        $res = $this->resetPassword($user, $oRequest->password);
        //$res = $this->resetPassword(Auth::user(), $request->password);
        
        
        return response()->json([
            'success'   => true,
            'message'   => trans('messages.password_reset_success_message'),
            'data'      => []
        ]);
    }

    protected function credentials(Request $request)
    {
        //$request->email = Auth::user()->email;
        $credential = $request->only(
            'password', 'password_confirmation', 'token'
        );
        $credential['email'] = Auth::user()->email;

        return $credential;
    }

    protected function getOldCredentials(Request $request)
    {
        //$request->email = Auth::user()->email;
        $req = $request->only(
            'current_password'
        );
        $credential['email'] = Auth::user()->email;
        $credential['password'] = $req['current_password'];

        return $credential;
    }

    protected function rules()
    {
        return [
            'current_password' => 'sometimes|required',
            //'password' => 'required|confirmed|min:6',
            'password' => 'required|min:6',
            'password_confirmation' =>  'required|same:password',
        ];
    }

    protected function errorMessage()
    {
        return [
            'password_confirmation.same' => 'The Password confirmation does not match.',
        ];
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return NULL;
        }

        /*
            * Add each credential element to the query as a where clause.
            * Then execute the query and, 
            * If user found return it in a Eloquent User "model" that will be utilized by the Guard instances.
        */
        $user = User::where('email', $credentials['email'])->first();
        if (Hash::check($credentials['password'], $user->password)) {
            return $user;
        }

        return NULL;
    }

    public function UploadFile($request, &$oInputs, $destinationPath, $file, $file_token){

        $profile = NULL;
        $profile = Profile::where(['id_user' => Auth::id()])->first();

        if(Input::hasFile($file) || $request->$file_token != "")
        {
            if($profile != NULL && $profile->$file != "")
            {
                $file_to_uploads = $profile->$file; 
                if(!is_array($file_to_uploads)){
                    $files_to_upload[] = $file_to_uploads;
                }        
                foreach($files_to_upload as $file_to_upload)
                {
                    if(file_exists($destinationPath.$file_to_upload))
                    {
                        \File::delete($destinationPath.$file_to_upload);
                    }
                }
                $oInputs[$file] = $oInputs[$file_token] = "";
            }

            if(Input::hasFile($file))
            {

                \File::makeDirectory($destinationPath,0777,true,true);

                $n_files = $request->file($file);
                if(!is_array($n_files)){
                    $new_files[] = $n_files;
                }else{
                    $new_files = $n_files;
                }
                $new_files_src = ""; 
                $new_file_name = ""; 
                foreach($new_files as $new_file)
                {
                    if($new_file->isValid())
                    {
                        $name =  $new_file_name = $new_file->getClientOriginalName(); //comment for multiple files
                        $extension = $new_file->getClientOriginalExtension();
                        $file_name =  md5(uniqid().time()).'_'.$name;
                        $img = \Image::make($new_file->getRealPath());
                        $img->resize(600, 600, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                        $img->save($destinationPath.'/'.$file_name,'70'); 
                        $new_files_src = $file_name;   
                    }
                }                
                $oInputs[$file]  = $new_files_src;
                $oInputs[$file_token] = $new_file_name;
            }elseif($request->$file_token != ""){
                $oInputs[$file] = $oInputs[$file_token] = "";
            }
        }else{
            if($profile != NULL){
                $oInputs[$file] = $profile->$file;
                $oInputs[$file_token] = $profile->$file_token;
            }else{
                $oInputs[$file] ="";
                $oInputs[$file_token] = "";
            }
        }
    }

}
