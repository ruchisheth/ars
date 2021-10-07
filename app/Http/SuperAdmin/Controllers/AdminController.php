<?php
namespace App\Http\SuperAdmin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\AppHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

use DB;
use Datatables;
use Auth;
use Artisan;
use Carbon;

use App\Site;
use App\User;
use App\Admin;
use App\Client;
use App\Setting;
use App\Emailer;
use App\Profile;
use App\FieldRep;
use App\Assignment;
use App\AdminSubScription;
//use App\AdminUser;

class AdminController extends Controller
{

    protected $connection = 'mysql'; 

    public function callShowAdmins(Request $oRequest)
    {
        if($oRequest->isMethod('POST')){
            $oAdmins = Admin::with('admin_subscriptions')->get();


            $oDatatables =  Datatables::of($oAdmins)
            ->addColumn('logo', function ($oAdmins) {
                $logo = getImage(config('constants.USERIMAGEFOLDER').'/'.$oAdmins->logo);
                return $logo;
            })
            ->editColumn('id_admin', function ($oAdmins) {
                return '<a href="'.route('superadmin.add_subscripition', ['nIdAdmin' => $oAdmins->id_admin]).'"><span class="">'.format_code($oAdmins->id_admin).'</span></a>';
            })
            ->editColumn('status', function ($oAdmins) {

                if($oAdmins->status){
                    return '<label class="label label-success">'.trans('messages.active').'</label>';
                } 
                return '<label class="label label-danger">'.trans('messages.inactive').'</label>';

                $oAdminSubScriptions = $oAdmins->admin_subscriptions;

                if($oAdminSubScriptions->isEmpty()){
                    return ' - ';
                } 

                $sSubScriptionStatus = NULL;

                $oIfSubScriptionExists = FALSE;
                if($oAdminSubScriptions){
                    foreach ($oAdminSubScriptions as $oAdminSubScription) {
                        if ($oIfSubScriptionExists) {
                            break;
                        } else {
                            if ($oAdminSubScription->subscription_start_on <= \Carbon::parse() && ($oAdminSubScription->subscription_end_on >= \Carbon::parse() || ($oAdminSubScription->subscription_end_on == NULL) ))
                            {
                                $oIfSubScriptionExists = TRUE;
                            }
                            else if (($oAdminSubScription->subscription_start_on < \Carbon::parse() && $oAdminSubScription->subscription_end_on < \Carbon::parse()))
                            {
                                $sSubScriptionStatus = 'completed';
                            }
                            else if($oAdminSubScription->subscription_start_on > \Carbon::parse() && $oAdminSubScription->subscription_end_on > \Carbon::parse()){
                                $sSubScriptionStatus = 'in-Queue';
                            }
                        } 
                    }

                    if($oIfSubScriptionExists){
                        return '<label class="label label-success">'.trans('messages.active').'</label>';
                    }else{
                        if($sSubScriptionStatus != NULL){
                            if($sSubScriptionStatus == 'completed'){
                                return '<label class="label label-danger">'.trans('messages.completed').'</label>';
                            }else{
                                return '<label class="label label-info">'.trans('messages.in-queue').'</label>';
                            }
                        }
                    }
                }
                return ' - ';
            })
            ->addColumn('subscription_start_on', function ($oAdmins) {
                $oIfSubScriptionExists = false;
                $oAdminSubScriptions = $oAdmins->admin_subscriptions()->orderBy(DB::raw('ISNULL(subscription_end_on)'), 'DESC')->get();

                if($oAdminSubScriptions){
                    foreach ($oAdminSubScriptions as $oAdminSubScription) {
                        if ($oIfSubScriptionExists) {
                            break;
                        } else {
                            if ($oAdminSubScription->subscription_start_on <= \Carbon::parse() && ($oAdminSubScription->subscription_end_on >= \Carbon::parse() || ($oAdminSubScription->subscription_end_on == NULL) ))
                            {
                                return $oAdminSubScription->subscription_start_on;
                                $oIfSubScriptionExists = TRUE;
                            }
                        } 
                    }
                    return $oAdminSubScription->first()->subscription_start_on;
                }

                return ' - ';
                
            })
            ->addColumn('subscription_end_on', function ($oAdmins) {
                $oIfSubScriptionExists = false;
                $oAdminSubScriptions = $oAdmins->admin_subscriptions()->orderBy(DB::raw('ISNULL(subscription_end_on)'), 'DESC')->get();

                if($oAdminSubScriptions){
                    foreach ($oAdminSubScriptions as $oAdminSubScription) {
                        if ($oIfSubScriptionExists) {
                            break;
                        } else {
                            if ($oAdminSubScription->subscription_start_on <= \Carbon::parse() && ($oAdminSubScription->subscription_end_on >= \Carbon::parse() || ($oAdminSubScription->subscription_end_on == NULL) ))
                            {
                                $oIfSubScriptionExists = TRUE;
                                return ($oAdminSubScription->subscription_end_on != NULL) ? $oAdminSubScription->subscription_end_on : ' - ';
                            }
                        } 
                    }
                    return $oAdminSubScription->first()->subscription_end_on;
                }

                return ' - ';
            })
            ->addColumn('id', function ($oAdmins) {
                return $oAdmins->id_admin;
            })
            ->addColumn('details_url', function($oAdmins) {
                return route('superadmin.admin_detail', ['nIdAdmin' => $oAdmins->id_admin]);
            })
            ->addColumn('expand', function($oAdmins) {
                return '<a href="javascript:void(0)"><i class="fa fa-database"></i></a>';
            })
            ->addColumn('database', function ($oAdmins) {
                return config('constants.DB_PREFIX').strtolower($oAdmins->db_version);
            })
            ->addColumn('invite', function ($oAdmins) {
                $sBtnClass = "";
                $sBtnIcon = "fa-envelope-o";
                $sBtnText = "Invite User";
                if($oAdmins->is_invited){
                    $sBtnClass = "text-success";
                    $sBtnIcon = "fa-envelope";
                    $sBtnText = "Invite Again";
                }

                $html = '<button class="btn btn-box-tool" type="butoon" name="send_invite" data-id="'.$oAdmins->id_admin.'" value="invite" title="Invite to SYI">
                <span class="'.$sBtnClass.'">
                <i class="fa '.$sBtnIcon.' fa-lg"></i>
                </span>
                </button>';

                return $html;
            })
            ->editColumn('action', function ($oAdmins) {

                if($oAdmins->status){
                    $sHtml = '<label class="switch">' 
                    . '<input type="checkbox" class="change_activation" data-id_admin="'.$oAdmins->id_admin.'" checked>'
                    .'<span class="slider round"></span>'
                    .'</label>';
                }
                else{
                        $sHtml = '<label class="switch">' 
                        . '<input type="checkbox" class="change_activation" data-id_admin="'.$oAdmins->id_admin.'">'
                        .'<span class="slider round"></span>'
                        .'</label>';
                }
                return $sHtml;
        });
            return $oDatatables->make(true);
        }
        return view('SuperAdminView::admin_list');
    }

    public function callGetAdminStatistics(Request $oRequest, $nIdAdmin){

        $oAdmin = Admin::find($nIdAdmin);
        $sSchemaName = config('constants.DB_PREFIX').$oAdmin->db_version;
        parent::setDBConnection($sSchemaName);

        // $aEntitiesCount['client'] = Client::count();
        $aEntitiesCount = new Collection;
        $aEntitiesCount->push([
            'client'                => Client::where(['status' => true])->count(),
            'site'                  => Site::where(['status' => true])->count(),
            'fieldrep'              => FieldRep::where(['status' => true])->count(),
            'pending_assignment'    => Assignment::getPendingCount(),
            'offered_assignment'    => Assignment::getOfferedCount(),
            'scheduled_assignment'    => Assignment::getScheduleCount(),
            'late_assignment'    => Assignment::getLateCount(),
            'reported_assignment'    => Assignment::getReportedCount(),
            'notapproved_assignment'    => Assignment::getPartialCount(),
            'completed_assignment'    => Assignment::getCompletedCount(),
        ]);

        $oDatatables =  Datatables::of($aEntitiesCount)->make(true);

        return $oDatatables;
    }

    public function callAddSubScription(Request $oRequest, $nIdAdmin){

        $oAdmin = Admin::find($nIdAdmin);

        if($oRequest->isMethod('POST')){

            $oAdminSubScriptions = AdminSubScription::where(['id_admin' => $nIdAdmin ])->get();

            $this->validate($oRequest, [
                'start_date'        =>  'required',
                'end_date'          =>  'required',
            ]);

            $oIfSubScriptionExists = FALSE;

            $dStartDate = Carbon::parse($oRequest->start_date);
            $dStartDate = $dStartDate->toDateTimeString();

            $dEndDate = Carbon::parse($oRequest->end_date);
            $dEndDate = $dEndDate->hour(23)->minute(59)->second(59)->toDateTimeString();

            foreach ($oAdminSubScriptions as $oAdminSubScription) {
                if ($oIfSubScriptionExists) {
                    break;
                } else if (($dStartDate >= $oAdminSubScription->subscription_start_on && $dStartDate <= $oAdminSubScription->subscription_end_on)
                    || ($dEndDate >= $oAdminSubScription->subscription_start_on && $dEndDate <= $oAdminSubScription->subscription_end_on)  || ($oAdminSubScription->subscription_end_on == NULL)) {
                    $oIfSubScriptionExists = TRUE;
                }

            }

            if($oIfSubScriptionExists){
                $errors['start_date'] = 'Contract already exists between mention date';
                return redirect()->back()->withErrors($errors)->withInput();
            }

            AdminSubScription::create([
                'id_admin'              => $nIdAdmin,
                'subscription_start_on' => $dStartDate,
                'subscription_end_on'   => $dEndDate,
            ]);

            return redirect(route('show_admins'))->with('success', 'Subscription added successfully!');   

            // return redirect(route('show_admins'))
        }

        $aViewData = compact('oAdmin', 'oAdminSubScription');

        return view('SuperAdminView::add_admin_subscription', $aViewData);
    }

    public function callListAdminSubScription(Request $oRequest, $nIdAdmin){
        $oAdminSubScriptions = AdminSubScription::where(['id_admin' => $nIdAdmin])
        ->orderBy(DB::raw('ISNULL(subscription_end_on)'), 'DESC')
        ->get();

        $oDatatables =  Datatables::of($oAdminSubScriptions)
        ->editColumn('subscription_end_on', function ($oAdminSubScriptions) {
            if($oAdminSubScriptions->subscription_end_on == NULL){
                return ' - ';
            }
            return $oAdminSubScriptions->subscription_end_on;

        })
        ->editColumn('status', function ($oAdminSubScriptions) {
            if ($oAdminSubScriptions->subscription_start_on <= \Carbon::parse() && ($oAdminSubScriptions->subscription_end_on >= \Carbon::parse() || ($oAdminSubScriptions->subscription_end_on == NULL) ))
            {
                return '<label class="label label-success">'.trans('messages.active').'</label>';
            }
            else if (($oAdminSubScriptions->subscription_start_on < \Carbon::parse() && $oAdminSubScriptions->subscription_end_on < \Carbon::parse()))
            {
                return '<label class="label label-danger">'.trans('messages.completed').'</label>';
            }
            else if($oAdminSubScriptions->subscription_start_on > \Carbon::parse() && $oAdminSubScriptions->subscription_end_on > \Carbon::parse()){
                return '<label class="label label-info">'.trans('messages.in-queue').'</label>';
            }
        });

        return $oDatatables->make(true);
    }

    public function callCreateAdmin(Request $oRequest)
    {
        $oConfig = app()->make('config');
        \Session::forget('selected_database');
        parent::setDBConnection();
        
        if($oRequest->isMethod('POST'))
        {
            $this->validate($oRequest, [
                'client_code'   =>  'required|unique:admins|not_in:admin,web',                        
                'name'          =>  'required',
                'email'         =>  'required|email|unique:admins,email,null,id',//client_code,'.Auth::user()->client_code,
                'password'      =>  'required',
                'timezone'      =>  'required',
                'start_date'    =>  'required|date',
                'end_date'      =>  'date',
            ],[
                'name.required' => 'The Admin Name field is required.',
            ]);

            // /* Chang password to hash string */
            $sPassword = bcrypt($oRequest->password);
            $sClientCode = strtoupper($oRequest->client_code);

            $sImageName = NULL;

            /* Get the emapty database name and set schema name*/
            $oMasterSetting = DB::table('master_settings')->where('item_name', '=', 'db_version')->first();
            $nDBViersion = $oMasterSetting->item_value;
            $sSchemaName = config('constants.DB_PREFIX').$nDBViersion;

            /*Set defautl connection to new DB */
            parent::setDBConnection($sSchemaName);

            // DB::beginTransaction();

            Artisan::call('migrate', [
                '--database' => $sSchemaName,
                '--path' => '/database/migrations/db',
            ]);

            Artisan::call( 'db:seed', [
                '--class' => 'DatabaseSeeder',
                '--force' => true 
            ]);

            $oUser = User::create([
                'email' => $oRequest->email,
                'password' => $sPassword,
                'user_type' => config('constants.USERTYPE.ADMIN'),
                'role' => 2,
                'sub_role' => NULL,
                'client_code' => $sClientCode,
                'status' => TRUE,
                'is_first' => TRUE,
                'db_version' => $nDBViersion,
            ]);
            
            $dStartDate = Carbon::parse($oRequest->start_date);
            $dStartDate = $dStartDate->toDateTimeString();

            $dEndDate = NULL;
            if($oRequest->end_date != ''){
                $dEndDate = Carbon::parse($oRequest->end_date);
                $dEndDate = $dEndDate->hour(23)->minute(59)->second(59)->toDateTimeString();
            }

            $fileName = 'null';
            if(Input::hasFile('logo'))
            {
                $destinationPath = AppHelper::USER_IMAGE;
                $file = $oRequest->file('logo');
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
                    $image_data = array(
                        "name"=>$name,        
                        "encrypted_name"=>$encrypted_name);

                    $sImageName = $image_data['encrypted_name'];
                    // $oAdmin->logo = $oSetting->logo = $profile_data['profile_pic'] = $sImageName;
                }
            }

            $oSetting = Setting::create([
                'user_id'   =>  $oUser->id,
                'timezones' => $oRequest->timezone,
                'logo'      => $sImageName,
            ]);

            $oProfile = Profile::create([
                'user_id'   =>  $oUser->id,
                'name'      =>  $oRequest->name,
                'profile_pic'      =>  $sImageName
            ]);

            $bAdminStatus = FALSE;
            if ($dStartDate <= \Carbon::parse() && ($dEndDate >= \Carbon::parse() || $dEndDate == NULL))
            {
                $bAdminStatus = TRUE;
            }

            $oAdmin = Admin::create([
                'email'         => $oRequest->email,
                'name'          => $oRequest->name,
                'client_code'   => $sClientCode,
                'db_version'    => $nDBViersion,
                'logo'          => $sImageName,
                'status'        => $bAdminStatus,
            ]);

            $oAdminSubScription =  AdminSubScription::create([
                'id_admin'              => $oAdmin->id_admin,
                'subscription_start_on' => $dStartDate,
                'subscription_end_on'   => $dEndDate,
            ]);

            $oConfig->set('database.default','mysql');

            // DB::table('master_settings')->where('item_name', '=', 'db_version')->update(['item_value' => ++$nDBViersion]);
            
            if(env('APP_ENV') == config('constants.PRODUCTIONENV')){   
                $oCpanel = app()->make('cpanel');
                $oCpanel->createSubdomain(strtolower($sClientCode), 'alpharepservice', '/', 'alpharepservice.com');
            }

            // DB::commit();

            if($oAdmin){
                $aData = array(
                    'user'          => $oUser,
                    'user_password' => $oRequest->password
                );
                Emailer::SendEmail('superadmin.new_admin', $aData);
            }
            \Session::forget('selected_database');
            
            return redirect(route('admin_list'))->with('success', trans('messages.admin_account_created_successfully'));
        }
        return view('SuperAdminView::create_admin');
    }

    public function callActiveAdminSubScription(Request $oRequest){
        $oAdmin = Admin::find($oRequest->id_admin);
        $oAdmin->update(['status' => true]);

        return response()->json([
            'success' => true,
            'message' => trans('messages.admin_activated_successfully'),
            'data' => [],
        ]);
    }

    public function callInActiveAdminSubScription(Request $oRequest){
        $oAdmin = Admin::find($oRequest->id_admin);
        $oAdmin->update(['status' => false]);

        return response()->json([
            'success' => true,
            'message' => trans('messages.admin_inactivate_successfully'),
            'data' => [],
        ]);
    }

    /**
     * Display the all resource.
     *
     * @param  request
     * @return \Illuminate\Http\Response
     */
    public function getdata(Request $request){

        $clients = DB::table('admins as c')
        ->select(["c.id","name","logo","client_code","db_version","status", "c.is_invited"])->orderBy('id', 'desc');

        $datatables =  Datatables::of($clients)

        ->addColumn('logo', function ($clients) {
            $logo = AppHelper::getProfilePic($clients->logo);
            return $logo;
        })
        ->addColumn('email', function ($clients) {
            if($clients->db_version > 0){

                $schemaName = config('constants.DB_PREFIX').$clients->db_version;
                $user = DB::table($schemaName.'.users as u')
                ->where(['role' => 2])
                ->first();

                return $user->email;
            }

            return;
        })
        ->editColumn('id', function ($clients) {
            return 'test';
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
            $user_data = Admin::find($user_id);
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
