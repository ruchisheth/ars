<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\AppHelper;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use Session;
use Config;
use Datatables;
use Validator;
use Exception;
use Illuminate\Support\Facades\Mail;

use App,
App\Site,
App\Client,
App\Chain,
App\FieldRep,
App\FieldRep_Org,
App\Project,
App\Round,
App\FieldRepsCriteria,
App\AppData,
App\Setting,
App\Assignment, 
App\AssignmentsOffer,
App\Profile,
App\User,
App\AdminClient,
App\surveys,
App\Emailer,
App\Contact;

class FieldRepController extends Controller
{
  public function index()
  {    
    $res = parent::isDataAvailable('FieldRep','fieldrep.create');
    if($res === true){
      $states         = ['' => 'Select State'] + DB::table('_list')->where('list_name','=','state')->orderBy('item_name')->lists('item_name','item_name');
      $fieldrep_list  = ['' => 'Select FieldRep'] + FieldRep::select(DB::raw("concat(first_name,' ',last_name) as full_name, id"))->lists('full_name', 'id')->all();
      return view('admin.fieldreps.fieldreps',compact('fieldrep_list','states'));
    }
    return $res;
  }

  public function create()
  {       

    $fieldrep_id = FieldRep::max('id');
    $fieldrep_id++;

    $organizations                = ['' => 'Select FieldRep Organization'] + FieldRep_Org::lists('fieldrep_org_name','id')->all();

    $highest_edu                  = ['' => 'Select Education'] + DB::table('_list')->where('list_name','=','rep_highest_edu_level')->orderBy('list_order')->lists('item_name','id','list_order');

    $internet_browser             = ['' => 'Select Browser'] + DB::table('_list')->where('list_name','=','rep_internet_browser')->orderBy('list_order')->lists('item_name','id','list_order');

    $distance_willing_to_travel   = ['' => 'Select Distance'] + DB::table('_list')->where('list_name','=','rep_distance_willing_to_travel')->orderBy('list_order')->lists('item_name','id','list_order');

    $project_types = Project::getProjectTypes();

    $data = [
    'fieldrep_id'                 =>  $fieldrep_id,
    'highest_edu'                 =>  $highest_edu,
    'internet_browser'            =>  $internet_browser,
    'distance_willing_to_travel'  =>  $distance_willing_to_travel,
    'organizations'               =>  $organizations,
    'project_types'               =>  $project_types,
    ];

    return view('admin.fieldreps.create_fieldrep',  $data);
  }

  public function store(Request $oRequest){    
    $rules = [
      'first_name'    =>  'required',
      'last_name'     =>  'required',
      'fieldrep_code' =>  'required|unique:fieldreps',
    ];

    $message = [
      'fieldrep_code' => 'Fieldrep Code must contain atleast one alphabate and one digit.'
    ];

    $oLoggedInUser = Auth::user();
    //$inputs = $oRequest->all();
    $aFieldRep = $oRequest->all();

    if($oRequest->has('approved_for_work')){
      if($aFieldRep['approved_for_work'] == false && $aFieldRep['initial_status'] == true){
        $aFieldRep['initial_status'] = false;
      }
    }

    if($oRequest->organization_name == ""){
      $aFieldRep['organization_name'] = null;
    }

    // AutoNumber FieldeRep Code if leaved blank
    if($oRequest->get('fieldrep_code') == ''){
      if($oRequest->id == ''){
        $fieldrep_codes = FieldRep::select(DB::raw('max(cast(fieldrep_code as signed)) as fieldrep_code'))->first();
      }else{
        $fieldrep_codes = FieldRep::where('id','!=', $oRequest->input('id'))->select(DB::raw('max(cast(fieldrep_code as signed)) as fieldrep_code'))->first();       
      }
      $nFieldrepCode = $fieldrep_codes->fieldrep_code == 0 ? 1 : $fieldrep_codes->fieldrep_code + 1;
    }else{
      $nFieldrepCode = $oRequest->fieldrep_code;
    }

    $aFieldRep['fieldrep_code'] = $nFieldrepCode;

    if($oRequest->id == ''){
      $rules['email']    =  'required|email|unique:mysql.users,email,null,id,client_code,'.Auth::user()->client_code;
      $rules['password'] =  'required|confirmed';
      
      $this->validate($oRequest, $rules, $message);

      // Create a User account for FieldRep
      $oUser               = new User;        
      $oUser->email        = $aFieldRep['email']; 
      $oUser->password     = bcrypt($aFieldRep['password']); // bcrypt($oRequest->input('password'));
      $oUser->role         = 3;
      $oUser->user_type    = config('constants.USERTYPE.FIELDREP');
      $oUser->client_code  = $oLoggedInUser->client_code;
      $oUser->db_version   = $oLoggedInUser->db_version;

      if($aFieldRep['initial_status'] == true){
        $oUser->status = true;
      }
      $oUser->save();
      
      $oFieldRep = new FieldRep($aFieldRep);
      $oFieldRep->user_id = $oUser->id;
      $oFieldRep->save();


      if($oFieldRep && $oUser){
        $aUserDetails['client_name'] = Auth::user()->UserDetails->name;
        $aUserDetails['password'] = $oRequest->input('password');
        $data = [
            'user' =>  $oUser,  
            'details' =>  $aUserDetails
        ];
        $this->sendWelcomeEmail($data);
      }

      $type = $oRequest->input('type');
      if($type == 'fieldrep'){
        return redirect()->route('fieldrep.home')->with('success', 'Profile updated successfully!');          
      }else{
        return redirect()->route('show.fieldreps.get')->with('success', trans('messages.fieldrep_create_success'));
      } 
    }
    else{     
      $oFieldRep = FieldRep::where(['id'=>$oRequest->input('id')])->first();
      $nIdUser = $oFieldRep->user_id;      
      $rules['email'] = 'required|email|unique:mysql.users,email,'.$nIdUser.',id,client_code,'.Auth::user()->client_code;
      $rules['fieldrep_code'] = 'unique:fieldreps,fieldrep_code,'.$oFieldRep->id;

      $this->validate($oRequest,$rules);

      $oUser = User::where(['id'=> $oFieldRep->user_id])->first();
      $user_inputs['email'] = $aFieldRep['email'];
      
      /*
      * Check if submited data has initial statas field
      * if FieldRep Edit his/her profile initial status won't be there
      */
      if($oRequest->has('initial_status')){
        $user_inputs['status'] = $aFieldRep['initial_status'] == true ? true : false;
      }

      $oUser->update($user_inputs);

      $oFieldRep->update($aFieldRep);
      
      $profile = Profile::firstOrCreate(['user_id' => $nIdUser]);
      $profile->update([
        'email'       => $aFieldRep['email'],
        'name'        => $aFieldRep['first_name'].' '.$aFieldRep['last_name'],
        'profile_pic' => $aFieldRep['profile_pic']
      ]);

      if($oRequest->input('type') == 'fieldrep'){
        return redirect()->back()->with('success', 'Profile updated successfully!');
      }else{
        return redirect()->back()->with('success', 'FieldRep saved successfully!');
      }
    }
  } 

  public function store_otherdetails(Request $request){
    if($request->input('id') == '')
    {
      $fieldrep = $request->all();
      FieldRep::create($fieldrep);
      return back();
    }
    else
    {
      $fieldrep = FieldRep::where(['id'=>$request->input('id')])->first();

      $fieldrep_a = $request->except('_token');
      $fieldrep->update($fieldrep_a);

      $update_data['can_print']       = 
      $update_data['can_print']       = $request->has('can_print') ? true : false;
      $update_data['has_camera']      = $request->has('has_camera') ? true : false;
      $update_data['has_computer']    = $request->has('has_computer') ? true : false;
      $update_data['has_smartphone']  = $request->has('has_smartphone') ? true : false;
      $update_data['has_internet']    = $request->has('has_internet') ? true : false;

      $fieldrep->update($update_data);

      $type = $request->input('type');
      if($type == 'fieldrep'){
        return redirect()->back()->with('success', 'Profile updated successfully!');
      }else{
        return redirect()->back()->with('success', 'Details saved successfully!');
      }
    }
  } 

  public function store_interestedin(Request $request)
  {
    $have_done = $interested_in = '';

    $fieldrep = FieldRep::where(['id'=>$request->input('id')])->first();
    if($request->has('have_done')){
      $have_done = implode(',',array_keys($request->input('have_done')));
    }
    if($request->has('interested_in')){
      $interested_in = implode(',',array_keys($request->input('interested_in')));
    }

    $fieldrep->update(['have_done' => $have_done, 'interested_in' => $interested_in]);

    $type = $request->input('type');
    if($type == 'fieldrep'){
      return redirect()->back()->with('success', 'Profile updated successfully!');
    //return redirect()->route('fieldrep.home')->with('success', 'Profile updated successfully!');
    }elseif($type == 'admin'){
      return redirect()->back()->with('success', 'Details saved successfully!');
    //return redirect()->route('show.fieldreps.get')->with('success', 'Details saved successfully!');
    }
  } 

  public function store_availability(Request $request){

    $fieldrep = FieldRep::where(['id'=>$request->input('id')])->first();

    $fieldrep_a = $request->except('_token');

    $days = array('availability_monday','availability_tuesday','availability_wednesday','availability_thursday','availability_friday','availability_saturday','availability_sunday');

    foreach($days as $day){
      if(!isset($fieldrep_a[$day]['a'])){
        $fieldrep_a[$day]['a'] = 0;
      }
      if(!isset($fieldrep_a[$day]['b'])){
        $fieldrep_a[$day]['b'] = 0;
      }

      if(!isset($fieldrep_a[$day]['c'])){
        $fieldrep_a[$day]['c'] = 0;
      }
      ksort($fieldrep_a[$day]);

      $fieldrep_a[$day] = implode(',',$fieldrep_a[$day]);
    }  
    $fieldrep->update($fieldrep_a);
    $type = $request->input('type');
    if($type == 'fieldrep'){
      return redirect()->back()->with('success', 'Profile updated successfully!');          
    }else{
      return redirect()->back()->with('success', 'Details saved successfully!');  //reps is named route
    }
  } 

  public function edit(Request $request,$id){

    $clients = ['' => 'Select Client'] + Client::lists('client_name','id')->all();

    $chains = Chain::lists('chain_name','id')->all();

    $rep_activity = ['' => 'Select Activity'] + DB::table('_list')->where('list_name','=','rep_activity')->orderBy('list_order')->lists('item_name','id','list_order');

    $organizations = ['' => 'Select FieldRep Organization'] +FieldRep_Org::lists('fieldrep_org_name','id')->toArray();

    $highest_edu = ['' => 'Select Education'] + DB::table('_list')->where('list_name','=','rep_highest_edu_level')->orderBy('list_order')->lists('item_name','id','list_order');

    $internet_browser = ['' => 'Select Browser'] + DB::table('_list')->where('list_name','=','rep_internet_browser')->orderBy('list_order')->lists('item_name','id','list_order');

    $distance_willing_to_travel = ['' => 'Select Distance'] + DB::table('_list')->where('list_name','=','rep_distance_willing_to_travel')->orderBy('list_order')->lists('item_name','id','list_order');

    $project_types = Project::getProjectTypes();

    $states = ['' => 'Select State'] + DB::table('_list')->where('list_name','=','state')->lists('item_name','item_name','list_order');

    $user = User::find(Auth::user()->id);
    $admin = User::where('role',2)->where('db_version',$user->db_version)->first();
    $admin_client = ['' => 'Select User'] + AdminClient::where('user_id',$admin->id)->lists('name','id')->all();

    $fieldrep = FieldRep::findorFail($id);
    $fieldrep->updated = date_formats(AppHelper::getLocalTimeZone($fieldrep->updated_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT); 
    $fieldrep->created = date_formats(AppHelper::getLocalTimeZone($fieldrep->created_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT);

    $user = User::where(['id'=>$fieldrep->user_id])->first();        
    $project = new Project;

    $fieldrep->work_exp = explode(',',$fieldrep->work_exp);

    $days = array('availability_monday','availability_tuesday','availability_wednesday','availability_thursday','availability_friday','availability_saturday','availability_sunday');

    foreach ($days as $day) {

      $key = array('a','b','c');

      $fieldrep->$day = explode(',',$fieldrep->$day);

      $fieldrep->$day = array_combine($key, $fieldrep->$day);              
    }

    $app = new AppData;

    $entity_type = $app->entity_types['rep'];

    $contact_types = ['' => 'Select Contact'] + $app->contact_types['rep']; //// get contact types of fieldreps

    $data = [
    'fieldrep'                    => $fieldrep,
    'clients'                     => $clients,  
    'entity_type'                 =>  $entity_type,
    'contact_types'               =>  $contact_types,
    'highest_edu'                 =>  $highest_edu,
    'internet_browser'            =>  $internet_browser,
    'distance_willing_to_travel'  =>  $distance_willing_to_travel,
    'chains'                      =>  $chains,
    'rep_activity'                =>  $rep_activity,
    'project_types'               =>  $project_types,
    'user'                        =>  $user,
    'organizations'               =>  $organizations,
    'admin_client'                =>  $admin_client,
    'states'                      =>  $states
    ];

    return view('admin.fieldreps.create_fieldrep',$data);
  }

  public function getdata(Request $request){

    $criterias = [];
    $prefered_reps = [];
    $ban_reps = [];
    $round_id = "";
    $activity = null;
    $site = null;
    $assignment_id = null;
    $distance = '';
    if($request->get('set_criteria') == true){
      $assignment_id = $request->get('assignment_id');
      if($assignment_id != null)
      {
        $assignment = Assignment::find($assignment_id);
        $round_id = $assignment->rounds->id;
        $criterias = FieldRepsCriteria::where(['round_id'=>$round_id])->first();
        $site = Site::find($assignment->site_id);
        $activity = $assignment->rounds->projects->project_type;
        $lat1 = $site->lat;
        $long1 = $site->long;
        // $ban_reps = $site->getBanFieldRep($activity);
        // $prefered_reps = $site->getPreferedFieldRep($activity);

      // Distance in KM
      // $distance = 'floor(111.1111 * DEGREES(ACOS(COS(RADIANS('.$lat1.')) * COS(RADIANS(co.lat)) * COS(RADIANS('.$long1.' - co.long)) + SIN(RADIANS('.$lat1.')) * SIN(RADIANS(co.lat))))) AS `distance`';
        $distance = '0 `distance`';
        if($lat1 != '' && $long1 != ''){
          $distance = 'floor(3959 * ACOS(COS(RADIANS('.$lat1.')) * COS(RADIANS(co.lat)) * COS(RADIANS('.$long1.' - co.long)) + SIN(RADIANS('.$lat1.')) * SIN(RADIANS(co.lat)))) `distance`';
        } // in Miles
      }
    }

    $columns = [
    'f.id','f.fieldrep_code','f.first_name','f.last_name',
    'co.address1','co.address2','co.city','co.state','co.zipcode','u.email','co.lat','co.long',
    'f.approved_for_work','f.classification',
    'f.initial_status', 'f.has_camera','f.has_internet','f.have_done',
    'f.availability_sunday','f.availability_monday',
    'f.availability_tuesday','f.availability_wednesday',
    'f.availability_thursday','f.availability_friday',
    'f.availability_saturday','is_invited','is_pending'];

    if($distance != ''){
      array_unshift ($columns, DB::raw($distance));
    }

    $fieldreps = DB::table('fieldreps as f')
    ->leftjoin('users as u','u.id','=','f.user_id')
    ->leftjoin('contacts as co', function ($join) {
      $join->on('co.reference_id', '=', 'f.id')
      ->where('co.entity_type', '=', '4')
      ->where('co.contact_type', '=', 'primary');
    })
    ->when($request->get('is_pending') != "" || $request->get('is_pending') != NULL, function ($query) use ($request) {
      return $query->where('f.is_pending', '=', $request->get('is_pending'));
    })
    ->when($request->get('assignment_id') != "" || $request->get('assignment_id') != NULL, function ($query) use ($request) {
      return $query->where('f.approved_for_work', '=', true);
    })
    ->select($columns)
    ->groupBy('f.id');

    if($request->order[0]['column'] == 1){
      $fieldreps->orderBy(DB::raw('lpad(trim(fieldrep_code), 10, 0)'), $request->order[0]['dir']);
    }

    $datatables = Datatables::of($fieldreps)
    ->addColumn('action', function ($fieldreps) use($criterias,$round_id) {
      $invite_btn = '<span class="text-default"><i class="fa">-</i></span>';

      $delete_btn = '<button class="btn btn-box-tool" type="button" name="remove_fieldrep" data-id="'.$fieldreps->id.'" value="delete" ><span class="fa fa-trash"></span></button>';

      if($fieldreps->classification == 1){
        $class  = "";
        $icon   = "fa-envelope-o";
        if($fieldreps->is_invited){
          $class  = "text-success";
          $icon   = "fa-envelope";
        }
        $invite_btn = '<button class="btn btn-box-tool" type="button" name="send_invite" data-id="'.$fieldreps->id.'" value="invite" title="Invite to SYI">
        <span class="'.$class.'"><i class="fa '.$icon.' fa-lg"></i></span>
      </button>';
    }
    return $invite_btn." ".$delete_btn;

  })
    ->addColumn('schedule', function ($fieldreps) {
      return '<a href="#" data-target="#assignment_schedule_modal" class="schedule" data-assignment-id="" data-fieldrep-id="'.$fieldreps->id.'" data-toggle="modal">schedule</a> | <a href="#" data-target="#assignment_offer_modal" class="offer" data-assignment-id="" data-fieldrep-id="'.$fieldreps->id.'" data-toggle="modal">offer</a>';
    })
    ->editColumn('distance', function ($fieldreps) use($site){
      return ($site != null) ? $fieldreps->distance : '';
      // if($site != null)
      // {
      //   return $fieldreps->distance;
      // }
      // return '';
    })
    ->editColumn('status', function ($fieldreps) {
      if($fieldreps->is_pending){
        return '<span class="label label-default">pending</span>';
      }
      if($fieldreps->initial_status === 0){
        return '<span class="label label-danger">Inactive</span>';
      }else if($fieldreps->initial_status == 1){
        return '<span class="label label-success">Active</span>';
      }
      else if($fieldreps->initial_status == '2'){
        return '<span class="label label-info">Hold</span>';
      }
      else if($fieldreps->initial_status == '3'){
        return '<span class="label label-warning">Terminated</span>';
      }
    })
    ->editColumn('full_name', function ($fieldreps) use($site, $activity) {

      $className = "";            
      if($site != null)
      {
        if($site->isPrefered($fieldreps->id, $activity)){

          $className .= "text-bold text-success";
        }
        
        if($site->isBan($fieldreps->id, $activity)){
          $className .= "text-bold text-danger";
        }
      }
      $html = '';
      $html .= '<span class="'.$className.'">';
      $html .= fullname($fieldreps->first_name,$fieldreps->last_name);
      $html .= '</span">';
      return $html;
    })
    ->editColumn('approved_for_work', function ($fieldreps) {
      if($fieldreps->is_pending){
        $html = '<button class="btn btn-xs btn-success app_respond_btns" id="approve" data-id="'.$fieldreps->id.'"><i class="fa fa-check"></i></button>';
        $html .= '<button class="btn btn-xs btn-danger app_respond_btns" id="reject" data-id="'.$fieldreps->id.'"><i class="fa fa-times"></i></button>';
        return $html;
      }else{
        if($fieldreps->approved_for_work == false && $fieldreps->approved_for_work !== NULL){
          return '<span class="text-danger">Rejected</span>';
        } elseif($fieldreps->approved_for_work == true){
          return '<span class="text-success">Approved</span>';
        }else{
          return '-';
        }
      }
    })
    ->editColumn('location', function ($fieldreps) {
      return format_location($fieldreps->city,$fieldreps->state,$fieldreps->zipcode);
    })
    ->editColumn('fieldrep_code', function ($fieldreps) use($round_id) {
      if($round_id == "")
        return '<a href='.url("/fieldreps-edit/").'/'.$fieldreps->id.'>'. $fieldreps->fieldrep_code.'</a>';
      else
        return format_code($fieldreps->fieldrep_code);
    });

    if ($request->get('status') != ''  ) {
      $status = $request->get('status');
      if($status == 'pending'){
        $datatables->where('f.is_pending', '=', true); 
      }elseif($status == 'approved'){
        $datatables->where('f.approved_for_work', '=', true);
      }elseif($status == 'rejected'){
        $datatables->where('f.approved_for_work', '=', false);
      }
      else{
        $datatables->where('f.initial_status', '=', $status)->where('f.is_pending', '=', false);
      }
    }

    if ($request->get('classification') != ''  ) {
      $classification = $request->get('classification');
      $datatables->where('f.classification', '=', $classification); 
    }

    if ($request->get('state') != ''  ) {
      $state = $request->get('state');
      $datatables->where('co.state', 'like', "$state%"); 
    }

        //fieldrep criteria for scheduling
    if($assignment_id != null){

      if($criteria = $criterias){

        if($criteria['has_camera'] == "1"){
          $datatables->where('f.has_camera', '=', "1");
        }

        if($criteria['has_internet'] == "1"){
          $datatables->where('f.has_internet', '=', "1");
        }

        if($criteria['exp_match_project_type'] == "1"){
          $round = Round::find($round_id);
          $project_type = $round->projects->project_type;
          $datatables->whereRaw("FIND_IN_SET('".$project_type."',f.have_done)"); 
        }

        if($criteria['distance'] != null){
          $datatables->having('distance', '<=', $criteria['distance']);
        }

        if($criteria['allowable_days'] != null){
          $days = explode(',', $criteria['allowable_days']);
          foreach($days as $day){
            $datatables->whereRaw("FIND_IN_SET('1',f.availability_".$day.")");
          }
        }
      }
    }

    //Built in keyword search
    $keyword = $request->get('search')['value'];

    if (preg_match("/^".$keyword."/i", 'Active', $match)) :
      $datatables->filterColumn('f.status', 'where', '=', "1");
    endif;

    if (preg_match("/^".$keyword."/i", 'Inactive', $match)) :
      $datatables->filterColumn('f.status', 'where', '=', "0");
    endif;

    $datatables->filterColumn('co.city', 'whereRaw', "CONCAT(co.city,',',co.state,' ',co.zipcode) like ? ", ["%$keyword%"]);

    $datatables->filterColumn('f.first_name', 'whereRaw', "CONCAT(f.first_name,' ',f.last_name) like ? ", ["%$keyword%"]);

    return $datatables->make(true);
  }

  function deleteFieldRep(Request $request){
    try{
      $fieldrep = FieldRep::find($request->input('id'));
      $user = User::find($fieldrep->user_id);
      $fieldrep->delete();
      $user->delete();
      Contact::where(['entity_type' => 4, 'reference_id' => $request->input('id')])->delete();
      return response()->json(array(
       "status" => "success",
       "message"=>"FieldRep removed successfully",
       ));
    }
    catch(Exception $e){
      if($e instanceof \PDOException )
      {
        $error_code = $e->getCode();
        if($error_code == 23000){
          $message = 'Fieldrep can not be deleted, it has ';
          $entity = [];
          if(Assignment::where(['fieldrep_id' => $request->input('id')])->count() > 0){
            $entity[] = 'Assignments';
          }
          if(AssignmentsOffer::where(['fieldrep_id' => $request->input('id')])->count() > 0){
            $entity[] = 'Offers';
          }
          if(surveys::where(['reference_id' => $request->input('id')])->count() > 0){
            $entity[] = 'Surveys';
          }
          $glue = (count($entity) > 2) ? ', ': ' and ';
          $message .= implode($glue, $entity).".";
          return response()->json([ 'message' => $message ], 422);  
        }
      }
    }
  }

  function recent_activity(Request $request,$fieldrep_id)
  {
        //Get Fieldrep Recent Activity
    $recent_activities = DB::table('surveys as ss')
    ->leftjoin('assignments as a','a.id','=','ss.assignment_id')
    ->leftjoin('rounds as r','r.id','=','a.round_id')
    ->leftjoin('projects as p','p.id','=','r.project_id')
    ->leftjoin('sites as s','s.id','=','a.site_id')
    ->leftjoin('fieldreps as f','f.id','=','a.fieldrep_id')
    ->where('f.id','=',$fieldrep_id)
    ->select([
      'ss.id as survey_id',
      'ss.status as status',
      'a.id as assignment_id',
      'a.site_id as a_site_id',
      'a.fieldrep_id as a_fieldrep_id',
      'a.round_id as a_round_id',
      's.street',
      's.city',
      's.state',
      'p.project_name',
      'ss.updated_at',
      ])
    ->groupBy('ss.id');
                            //->get();

    $datatables = Datatables::of($recent_activities)
    ->editColumn('date', function ($recent_activities) use($request) {
      $activities = date_formats(AppHelper::getLocalTimeZone($recent_activities->updated_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT);
      return $activities;
    })
    ->editColumn('location', function ($recent_activities) {
      return format_location($recent_activities->street,$recent_activities->city,$recent_activities->state);
    })

    ->editColumn('status', function ($recent_activities) {
      if($recent_activities->status == 0){
        return '<span class="label label-primary">Scheduled</span>';
      }elseif($recent_activities->status == 1){
        return '<span class="label label-default">Pending</span>';
      }
      else{
        return '<span class="label bg-purple">Reported</span>';
      }
    }) 

    ->removeColumn('survey_id')
    ->removeColumn('assignment_id')
    ->removeColumn('site_id')
    ->removeColumn('fieldrep_id')
    ->removeColumn('round_id')
    ->removeColumn('street')
    ->removeColumn('city')
    ->removeColumn('state');

    return $datatables->make(true);
        //Recent Activity Ends
  }

  public function getRegister($client_code){

    $admin = User::where(['client_code' => $client_code, 'role' => 2])->first();
    if($admin == null){
      abort('404');
    }

    $db_version = $admin->db_version;
    $schemaName = config('constants.DB_PREFIX').$db_version;
    Session::put('selected_database',$schemaName);

    parent::setDBConnection();

    $highest_edu = ['' => 'Select Education'] + DB::table('_list')->where('list_name','=','rep_highest_edu_level')->orderBy('list_order')->lists('item_name','id','list_order');

    $internet_browser = ['' => 'Select Browser'] + DB::table('_list')->where('list_name','=','rep_internet_browser')->orderBy('list_order')->lists('item_name','id','list_order');

    $states = ['' => 'Select State'] + DB::table('_list')->where('list_name','=','state')->lists('item_name','item_name','list_order');

    $distance_willing_to_travel = ['' => 'Select Distance'] + DB::table('_list')->where('list_name','=','rep_distance_willing_to_travel')->orderBy('list_order')->lists('item_name','id','list_order');

    $project_types = Project::getProjectTypes();

    $data = [
    'highest_edu' => $highest_edu,
    'internet_browser' => $internet_browser,
    'distance_willing_to_travel' => $distance_willing_to_travel, 
    'project_types' => $project_types,
    'states' => $states,
    'cc' =>  $client_code,
    ];
    return view('public.public_registration',$data);
  }

  public function validateData(Request $request, $client_code, $index){

    $client_code = strtoupper($client_code);
    $rules = [];
    $message = [];
    switch ($index) {
      case 1:
      $rules = [
      'first_name'    =>  'required',
      'last_name'     =>  'required',
    //   'email'         =>  'required|email|unique:mysql.users,email,null,id',
      'email'         =>  'required|email|unique:mysql.users,email,null,id,client_code,'.$client_code,
      ];
      break;
      case 2:
      $rules = [
      'address1'    =>  'required',
      'city'        =>  'required',
      'state'       =>  'required',
      'zipcode'     =>  'required|numeric',
      'phone_number'=>  'required',
      ];
      break;
      default:
      $rules = [];
      $message = [];
      break;
    };

    $this->validate($request, $rules, $message);

    return response()->json([
      'success' =>  'success',
      ]);
  }

  public function registerFieldRep(Request $request, $client_code){
      http_response_code(500);
      
    $f = FieldRep::select(DB::raw('max(lpad(fieldrep_code, 10, 0)) as fieldrep_code'))->where('fieldrep_code', 'like', '%'.@$client_code.'%')->first();
    $fc = substr($f->fieldrep_code, strpos($f->fieldrep_code, '_')+1)+1;

    $admin = User::where(['client_code' => $client_code, 'role' => 2])->first();
    $db_version = $admin->db_version;

    DB::beginTransaction();
    $user = new User;
    $user->email   =  $request->get('email');
    $user->password = '';
    $user->user_type = config('constants.USERTYPE.FIELDREP');
    $user->role = 3;
    $user->client_code = $client_code;
    $user->db_version = $db_version;
    $user->status = false;
    $user->save(); 

    $fieldrep = new FieldRep;
    $fieldrep->user_id = $user->id;
    $fieldrep->fieldrep_code = $client_code.'_'.$fc;
    $fieldrep->first_name = $request->get('first_name');
    $fieldrep->last_name = $request->get('last_name');

    $fieldrep->approved_for_work = false;
    $fieldrep->classification = 1; // IC
    $fieldrep->highest_edu = $request->get('highest_edu');
    $fieldrep->internet_browser = $request->get('internet_browser');
    $fieldrep->distance_willing_to_travel = $request->get('distance_willing_to_travel');
    $fieldrep->is_employed = $request->get('is_employed') != null ? $request->get('is_employed') : false;
    $fieldrep->occupation = $request->get('occupation');
    $fieldrep->as_merchandiser = $request->get('as_merchandiser') != null ? $request->get('as_merchandiser') : false;
    $fieldrep->merchandiser_exp = $request->has('merchandiser_exp') ? $request->get('merchandiser_exp') : '';
    $fieldrep->can_print = $request->has('can_print')  ? $request->has('can_print') : false;
    $fieldrep->has_camera = $request->has('has_camera')  ? $request->has('has_camera') : false;
    $fieldrep->has_computer = $request->has('has_computer')  ? $request->has('has_computer') : false;
    $fieldrep->has_smartphone = $request->has('has_smartphone')  ? $request->has('has_smartphone') : false;
    $fieldrep->has_internet = $request->has('has_internet')  ? $request->has('has_internet') : false;
    $fieldrep->experience = $request->get('experience');
    $fieldrep->cities = $request->get('cities');
    $fieldrep->is_pending = true;
     //$fieldrep->cities = $request->get('cities');
    if($request->has('have_done')){
      $fieldrep->have_done = implode(',',array_keys($request->get('have_done')));
    }
    if($request->has('interested_in')){
      $fieldrep->interested_in = implode(',',array_keys($request->get('interested_in')));
    }

    $fieldrep_a = $request->only(['availability_monday','availability_tuesday','availability_wednesday','availability_thursday','availability_friday','availability_saturday','availability_sunday']);

    $days = array('availability_monday','availability_tuesday','availability_wednesday','availability_thursday','availability_friday','availability_saturday','availability_sunday');

    foreach($days as $day){
      if(!isset($fieldrep_a[$day]['a'])){
        $fieldrep_a[$day]['a'] = 0;
      }
      if(!isset($fieldrep_a[$day]['b'])){
        $fieldrep_a[$day]['b'] = 0;
      }

      if(!isset($fieldrep_a[$day]['c'])){
        $fieldrep_a[$day]['c'] = 0;
      }
      ksort($fieldrep_a[$day]);

      $fieldrep_a[$day] = implode(',',$fieldrep_a[$day]);
      $fieldrep->$day = $fieldrep_a[$day]; 
    }
    $fieldrep->save();

    $contact = new Contact;
    $contact->entity_type = 4;
    $contact->reference_id = $fieldrep->id;
    $contact->contact_type = 'Primary';
    $contact->first_name = $request->get('first_name');
    $contact->last_name = $request->get('last_name');
    $contact->email = $request->get('email');
    $contact->address1 = $request->get('address1');
    $contact->address2 = $request->get('address2');
    $contact->city = $request->get('city');
    $contact->state = $request->get('state');
    $contact->zipcode = $request->get('zipcode');
    $contact->phone_number = $request->get('phone_number');
    $contact->save();


    DB::commit();
    if($request->get('s_address1') != '' && $request->get('s_city') != '' && $request->get('s_state') != '' && $request->get('s_zipcode') != ''){
      $contact = new Contact;
      $contact->entity_type = 4;
      $contact->reference_id = $fieldrep->id;
      $contact->contact_type = 'Shipping';
      $contact->first_name = $request->get('first_name');
      $contact->last_name = $request->get('last_name');
      $contact->email = $request->get('email');
      $contact->address1 = $request->get('s_address1');
      $contact->address2 = $request->get('s_address2');
      $contact->city = $request->get('s_city');
      $contact->state = $request->get('s_state');
      $contact->zipcode = $request->get('s_zipcode');
      $contact->phone_number = $request->get('s_phone_number');
      $contact->save();
    }

    $this->sendThankyouEmail($request);
    $this->sendNotificationEmail($request, $client_code);

    return response()->json([
      'success' => 'success',
      'message' => 'Thank you for your interest.  We will review your application.',
      ]);
  }

  public function sendWelcomeEmail($data){
    Emailer::SendEmail('admin.new_fieldrep',$data);
  }

  public function sendThankyouEmail(Request $request)
  {

    Mail::send('emails.thankyou_for_register', [], function ($m) use($request){
      $m->from('support@alpharepservice.com', 'ARS');

      $m->to($request->get('email'), $request->get('first_name'))->subject('ARS. Thank You');
    });
  }

  public function sendNotificationEmail(Request $request, $client_code)
  {

    $user = User::where(['client_code' => $client_code, 'role' => 2])->first();


    Mail::send('emails.new_application', ['user' => $user], function ($m) use ($user) {
      $m->from('support@alpharepservice.com', 'ARS');

      $m->to($user->email)->subject('New Application');
    });
  }

  public function respondToApplication(Request $request){

    $is_approved = $status = $initial_status = boolval($request->is_approved);

    $fieldrep = Fieldrep::find($request->id);
    $fieldrep->update(['is_pending' =>  false, 'approved_for_work' => $is_approved, 'initial_status'  =>  $initial_status]);
    if($is_approved){
      $user = User::find($fieldrep->user_id);
      $password = str_random(12);

      $user->update(['password' =>  bcrypt($password), 'status' => $status]);

      $user_details['client_name'] = Auth::user()->UserDetails->name;
      $user_details['password'] = $password;
      $email_data = ['user' =>  $user,  'details' =>  $user_details];
      $this->sendWelcomeEmail($email_data);
    }    
    return response()->json([
      'success' => 'success',
      'message' => 'Success',
      ]);
  }
}
