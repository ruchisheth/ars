<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\DataTables\SureveyDataTable;

use App\Client;
use App\Role;
use App\User;
use Response;
use Session;
use Auth;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(SureveyDataTable $dataTable)
    // {
    //     return $dataTable->   ('users');
    // }
    public function index()
    {
       
           $clients = ['' => 'Select Client'] + DB::table('clients')->where('status','=','1')->lists('client_name','id');
           return view('admin.users.users',compact('client_list'));
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_types = ['' => 'Select User Type'] + DB::table('_list')->where('list_name','=','user_type')->orderBy('list_order')->lists('item_name','item_id');
        $data = [
        'user_type'=>$user_types,
        ];
        return view('admin.users.create_user',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'user_type'     =>  'required',
            'role'          =>  'required',
            'user_name'     =>  'required',
            'user_email'    =>  'required|email|unique:mysql.users,email,NULL,id,client_code,'.Auth::user()->client_code,
            'password'      =>  'required|confirmed|min:6|max:15',
            ]);

        $inputs = $request->all();

        $inputs['client_code'] = Auth::user()->client_code;
        $inputs['db_version'] = Auth::user()->db_version;
        $inputs['password'] = bcrypt($request->get('password'));

        //Save in Clients DB
        
        // Save in Master DB
        $user = new User;
        $user->role = $inputs['role'];
        $user->client_code = $inputs['client_code'];
        $user->db_version = $inputs['db_version'];
        $user->email = $inputs['user_email'];
        $user->password = $inputs['password'];
        $user->save();

        $users = new User;
        $users->connection = null;
        $users->user_id = $user->id;
        $users->user_name  = $inputs['user_name'];
        $users->user_type = $inputs['user_type'];
        $users->role = $inputs['role'];
        $users->email = $inputs['user_email'];
        $users->password = $inputs['password'];
        $users->save();
        return redirect('users')->with('success', 'User added successfully!');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function getData(Request $request)
    {
        //
    }

    /**
     * Display List of User levels of related User Type.
     *
     * @param 
     */
    public function getUserLevel(){
        $option = Input::get('option');
        $user_level = Role::where('type','=',$option);                
        return Response::make($user_level->get(['id','role']));
    }
}
