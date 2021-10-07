<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use App;
use Config;
use Session;
use App\Http\AppHelper;
use App\User;
use App\Setting;
use App\AdminClient;
use App\FieldRep;
use DB;
use Artisan;
use Datatables;
use App\Emailer;
use Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $connection = 'mysql';

    public function index()
    {

        return view('super_admin.clients');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $timezone = AppHelper::getTimeZone();
        $data = [ 
        'timezone'      =>  $timezone
        ];
        return view('super_admin.create_client', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            "client_code"   =>  'required|unique:users',            
            "name"          =>  'required',
            "email"         =>  'required|email|unique:mysql.users,email,null,id,client_code,'.Auth::user()->client_code,
            "password"      =>  'required',
            "timezone"      =>  'required',
            ],[
            'name.required' => 'The Client Name field is required.',
            ]);
        $inputs = $request->all();
        $inputs['client_code']  = Str::upper($inputs['client_code']) ;
        $inputs['password']     = bcrypt($request->get('password'));
        $inputs['password2']    = $request->get('password');
        $inputs['role']         = 2;

        $users = DB::table('master_settings')->where('item_name', '=', 'db_version')->first();

        $db_version = $users->item_value;
        $schemaName = config('constants.DB_PREFIX').$db_version;
        
        // Create a New Database
        //DB::connection()->statement('CREATE DATABASE '.$schemaName);

        $config = App::make('config');
        $connections = $config->get('database.connections');
        $defaultConnection = $connections[$config->get('database.default')];
        $newConnection = $defaultConnection;
        $newConnection['database'] = $schemaName;
        App::make('config')->set('database.connections.'.$schemaName, $newConnection);

        DB::beginTransaction();
        Artisan::call('migrate', [
           '--database' => $schemaName,
           '--path' => '/database/migrations/db',
           ]);
        Config::set('database.default',$schemaName);

        Artisan::call( 'db:seed', [
            '--class' => 'DatabaseSeeder',
            '--force' => true ]
            );

        $setting = new Setting;
        $setting->timezone = $inputs['timezone'];


        $user = new User;
        $user->role = $inputs['role'];
        $user->client_code = $client_code = $inputs['client_code'];
        $user->db_version = $db_version;
        $user->email = $inputs['email'];
        $user->password = $inputs['password'];
        $user->status = true;
        $user->save();

        $client = new AdminClient;
        $client->user_id = $setting->user_id = $user->id;
        $client->name = $inputs['name'];


        $user_details = $user;
        $user_details->client_code = $client_code;
        

        $fileName = 'null';
        if(Input::hasFile('logo'))
        {
            $destinationPath = AppHelper::USER_IMAGE;
            $file = $request->file('logo');
            chmod($destinationPath,0777);
            if($file->isValid())
            {
                $ImagePath = $file->getRealPath();
                $name =  $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                $encrypted_name = md5(uniqid().time()).".".$extension;
                $img = \Image::make($ImagePath);

                $img->resize(160, 160, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save($destinationPath.$encrypted_name,'70');
                //$file->move($destinationPath,$encrypted_name);
                $image_data = array(
                    "name"=>$name,        
                    "encrypted_name"=>$encrypted_name);
                $inputs['logo'] = $image_data['encrypted_name'];
                $client->logo = $inputs['logo'];
                $setting->logo = $inputs['logo'];
            }
        }
        $client->save();
        $setting->save();
        Config::set('database.default','mysql');

        DB::table('master_settings')->where('item_name', '=', 'db_version')
        ->update(['item_value' => ++$db_version]);

        DB::commit();

        if($client && $user && $setting){
            $data = array(
                'user'=>$user_details,
                'user_password'=>$inputs['password2']
                );
            //Emailer::SendEmail('superadmin.new_client',$data);
        }

        return redirect('show-clients')->with('success', 'Client added successfully!');
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
     * Display the all resource.
     *
     * @param  request
     * @return \Illuminate\Http\Response
     */
    public function getdata(Request $request){      

        // $clients = DB::table('users as u')
        // ->leftJoin('clients as c', 'c.user_id', '=', 'u.id')
        // ->where('role',2);

        $clients = DB::table('clients as c')
        ->leftJoin('users as u', 'c.user_id', '=', 'u.id')
        ->where('u.role','=',2)
        ->select(["c.id","user_id","name","logo","role","client_code","db_version","email","status", "c.is_invited"]);


        $datatables =  Datatables::of($clients)

        ->addColumn('logo', function ($clients) {
            $logo = AppHelper::getClientLogoImage($clients->logo);
            return $logo;
        })
        ->editColumn('id', function ($clients) {
            return '<span class="">'.format_code($clients->id).'</span>';
        })
        ->addColumn('database', function ($clients) {
            //return config('constants.DB_PREFIX').strtolower($clients->client_code);
            return config('constants.DB_PREFIX').strtolower($clients->db_version);
        })
        ->addColumn('invite', function ($clients) {
            $class = "";
            $icon = "fa-envelope-o";
            //$class = "btn-danger";
            $btn_text = "Invite User";
            if($clients->is_invited){
                $class = "text-success";
                $icon = "fa-envelope";
                $btn_text = "Invite Again";
            }

            // $html = '<button class="btn btn-sm '.$class.'" type="butoon" name="send_invite" data-id="'.$clients->id.'" value="invite" title="Invite to SYI">'.$btn_text.'</button>';

            $html = '<button class="btn btn-box-tool" type="butoon" name="send_invite" data-id="'.$clients->id.'" value="invite" title="Invite to SYI">
            <span class="'.$class.'">
                <i class="fa '.$icon.' fa-lg"></i>
            </span></button>';
            return $html;
        });

        //$datatables =  Datatables::of($clients);
        return $datatables->make(true);

    }

    public function sendInvitation(Request $request, $invitation_type){
        $user_id = $request->get('user_id');
        switch ($invitation_type) {
            case 'payer':
            $user_data = AdminClient::find($user_id);
            $res = Emailer::SendEmail('invite_payer',$user_data);
            if($res != false){
                $user_data->update(['is_invited' => true]);
                return response()->json(array(
                    "status" => "success",
                    "message"=>"Your Invitation has been sent.",
                    ));
            }else{
                return response()->json(array(
                    "status" => "error",
                    "message"=>"Invitation has not been sent.",
                    ),422);
            }
            break;
            case 'payee':
            $user_data = FieldRep::find($user_id);
            $data['invitation_link'] = Setting::where(['user_id'    =>  Auth::id()])->get(['invitaton_link'])->first()->invitaton_link;
            if($data['invitation_link'] == ""){
                return response()->json([ 'message' => "Seems like you haven't set Invitaton link" ], 422);
            }
            $res = Emailer::SendEmail('invite_payee',$user_data);
            if($res != false){
                $user_data->update(['is_invited' => true]);
                return response()->json(array(
                    "status" => "success",
                    "message"=>"Your Invitation has been sent.",
                    ));
            }else{
                return response()->json(array(
                    "status" => "error",
                    "message"=>"Invitation has not been sent.",
                    ),422);
            }
            break;
            
            default:
                # code...
            break;
        }
    }



}
