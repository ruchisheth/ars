<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\User;
use App\Http\AppHelper;
use Config;
use App;
use App\surveys;
use App\Chain;
use App\Site;
use App\Feedback;
use Session;
use App\Exceptions;
use App\Exceptions\FeedbackAlreadySubmitted;
use DB;
use Mail;
use App\Emailer;
use App\Contact;

class FeedBackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $code, $client_code)
    {

        // $service_code = '';
        // $query_fields = $request->query();
        // $service_code = $query_fields['ServiceCode'];
        // $client_code = base64_encode($client_code);
        // dd($client_code);

        $chain_code = base64_decode($client_code);

        $admin = User::where(['client_code' => $code, 'role' => 2])->first();
        //dd('test');
        if($admin == null){
            abort('404');
        }

        $db_version = $admin->db_version;
        $schemaName = config('constants.DB_PREFIX').$db_version;
        //Session::forget('selected_database');
        Session::put('selected_database',$schemaName);
        parent::setDBConnection();
        
        $chain = Chain::findorFail($chain_code);
        $client_name = $chain->clients->client_name;
        
        // $sites = Site::where(['chain_id' => $chain->id])->select(DB::raw("CONCAT(site_code,' - ',site_name) AS site_name"),'id', 'site_code')->pluck('site_name', 'id');
         // $sites = Site::where(['chain_id' => $chain->id])->select(DB::raw("CONCAT(site_code,' - ',site_name) AS site_name"),'id', 'site_code')->orderBy(DB::raw('lpad(trim(site_code), 20, 0)'), 'asc')->get();//->pluck('site_name', 'id');
        $sites = Site::where(['chain_id' => $chain->id])->select(DB::raw("CONCAT(site_code,' - ',site_name) AS site_name"),'id', 'site_code')->orderBy(DB::raw('CAST(site_code AS UNSIGNED)'), 'asc')->orderBy('site_code', 'asc')->get();//->pluck('site_name', 'id');
        
        $sites = $sites->pluck('site_name', 'id');
        

        if($sites->isEmpty()){
            abort(404);
        }

        $sites->prepend('Select Site', '');
        

        $data = [
        'sites'          =>  $sites,
        'client_name'    =>  $client_name,
        'client_code'    =>  $client_code,
        'code'    =>  $code,
        // 'service_code' => $service_code,
        ];

        
        
        return view('feedback.feedback',$data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notifyCreate(Request $request, $code, $client_code)
    {
        $service_code = '';
        $query_fields = $request->query();
        $service_code = $query_fields['ServiceCode'];

        $admin = User::where(['client_code' => $client_code, 'role' => 2])->first();
        if($admin == null){
            abort('404');
        }

        $db_version = $admin->db_version;
        $schemaName = config('constants.DB_PREFIX').$db_version;
        //Session::forget('selected_database');
        Session::put('selected_database',$schemaName);

        parent::setDBConnection();

        // $config = App::make('config');


         //$connections = $config->get('database.connections');
        // $defaultConnection = $connections[$config->get('database.default')];
        // $newConnection = $defaultConnection;
        // $newConnection['database'] = $schemaName;
        // App::make('config')->set('database.connections.'.$schemaName, $newConnection);
        // Config::set('database.default',$schemaName);

        $survey = surveys::where(['service_code' => $service_code])->first();
        if($survey == null){
            abort('404');
        }

        $feedback = Feedback::where(['service_code' => $service_code])->first();
        if($feedback != null){
            throw new FeedbackAlreadySubmitted("Seems like you've already submitted your feedback.",1);
        }

        $data = [
        'service_code' => $service_code,
        ];
        
        return view('feedback.feedback',$data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $code, $client_code)
    {
        $chain_code = base64_decode($client_code);
        $this->validate($request, [
            'site'              =>  'required',
            "name"              =>  'required',
            "feedback_message"  =>  'required',
            ],[
            'name.required'             =>  'Please enter your name.',
            'site.required'             =>  'Please select store for which you are providing feedback',
            'feedback_message.required' =>  'Please enter your feedback.',
            ]);

        $chain = Chain::findorFail($chain_code);
        $client_name = $chain->clients->client_name;
        $site = Site::where(['id' => $request->site, 'chain_id' => $chain->id])->first();
        
        $site_address = $site->site_name.'('.$site->site_code.') '.format_location($site->city,$site->state,$site->zipcode);

        $admin = User::where(['client_code' => $code, 'role' => 2])->first();
        if($admin == null){
            abort('404');
        }

        $contacts = $chain->contacts()->where(['entity_type' => 2, 'contact_type' => 'Feedback'])->get(['email']);
        if($contacts->isEmpty()){
            $send_to = $admin->email;
        }else{
            $contacts = $contacts->toArray();   
            $send_to = array_map('current', $contacts);
        }
        

        $data = [
            'name'          =>  $request->name,
            'phone_number'  =>  $request->phone_number,
            'chain_name'    =>  $chain->chain_name,
            'client_name'   =>  $client_name,
            'site'          =>  $site_address,
            'site_name'     =>  $site->site_name,
            'feedback'      =>  $request->feedback_message,
            'send_to'       =>  $send_to,
        ];
        
        Emailer::SendEmail('feedback',$data);
        

        return view('feedback.thank_you');

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
    public function edit()
    {
        return view('feedback.thank_you');
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
}
