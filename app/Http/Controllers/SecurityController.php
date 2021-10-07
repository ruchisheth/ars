<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Hash;
use Auth;
use App\User;
use App\FieldRep;

class SecurityController extends Controller
{
    use ResetsPasswords;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //protected $redirectTo = '/security/password';

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the Change Password form.
     *
     * @return \Illuminate\Http\Response
     */
    // public function showChangeForm()
    // {
    //     return view('payer.security_password');
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resetPwd(Request $request)
    {
     $res = $this->reset($request);
     return $res;
 }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->errorMessage());

         /*
            * Attempt to reset the user's password. 
            * If successful update the password on an actual user model and persist it to the database. 
            * else parse the error and return the response.
        */
        if($request->has('current_password')){
            $user = $this->retrieveByCredentials($this->getOldCredentials($request));
            if($user == null){
                return response()->json([
                    'message' => 'Invalid Current Password',
                    ],422);        
            }
        }
        if($request->id == ""){
            $user = Auth::user();
        }else{
            $user = FieldRep::find($request->id)->users;
        }
        // $response = $this->broker()->reset(
        //     $this->credentials($request), 
        //     function ($user, $password) {
        //         $this->resetPassword($user, $password);
        //     });
        $res = $this->resetPassword($user, $request->password);
        //$res = $this->resetPassword(Auth::user(), $request->password);
        
        
        return response()->json([
            'message' => 'Your Password is reset!',
            ]);           
        

        // return ($response == Password::PASSWORD_RESET ||  Password::INVALID_TOKEN ) 
        // ? $this->sendResetResponse($response) 
        // : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => str_random(60),
        ])->save();

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
}
