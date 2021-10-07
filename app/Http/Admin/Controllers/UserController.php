<?php

namespace App\Http\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Client;
use App\Role;
use App\User;
use App\Profile;
use App\RolesUser;
use Response;
use Session;
use Auth;
use DB;
use Datatables;

class UserController extends Controller
{
    public function callShowClientList(Request $oRequest)
    {

        if($oRequest->isMethod('POST')){
            $oUsers = DB::table('users as u')
            ->leftJoin('profile as p', 'u.id', '=', 'p.user_id')
            ->leftJoin('roles as r', 'u.sub_role', '=', 'r.id_role')
            ->where(['u.user_type' => config('constants.USERTYPE.ADMIN')])
            ->whereNotNull('u.sub_role')
            ->select(['u.id', 'p.name', 'u.email', 'u.status', 'r.name as role_name']);

            $oDatatable =  Datatables::of($oUsers)
            ->addColumn('action', function ($oUsers) {
                $sContent = '';
                $sContent .= '<a href="javascript:void(0)" data-toggle="modal" data-target="#permissions_modal" class="btn btn-box-tool show-tooltip" title="Permissions" id="btn-permissions"><i class="fa fa-check-square-o"></i></a>';
                return $sContent;
            })
            ->editColumn('status', function ($oUsers) {
                if($oUsers->status){
                    return '<span class="label label-success">Active</span>';
                }else{
                    return '<span class="label label-danger">Inactive</span>';
                }
            });

            if ($oRequest->get('status') != ''  ) {
                $status = $oRequest->get('status');
                $oDatatable->where('u.status', 'like', "$status%"); 
            }

            $keyword = $oRequest->get('search')['value'];

            if (preg_match("/^".$keyword."/i", 'Active', $match)) :
                $oDatatable->filterColumn('u.status', 'where', '=', "1");
            endif;

            if (preg_match("/^".$keyword."/i", 'Inactive', $match)) :
                $oDatatable->filterColumn('u.status', 'where', '=', "0");
            endif;

            return $oDatatable->make(true);
        }

        $oRoles = RolesUser::orderBy('role_order')->get();
        $aRoles = $oRoles->pluck('name', 'id')->prepend('Select Role', '');

        $aViewData = [
            'roles' =>  $oRoles,
            'roles_user' =>  $aRoles,
        ];
        return view('AdminView::users.users_list', $aViewData);
    }

    public function callCreateUser(Request $oRequest)
    {

        $this->validate($oRequest, [
            'name' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'role_id' => 'required',
        ],[
            'name.required'   => 'Name field is required',
            'email.required'  => 'Email field is required',
            'role_id.required'  => 'The Role field is required',
        ]);

        $sPassword = bcrypt(config('constants.DEFAULTPASSWORD'));
        
        DB::beginTransaction();
        
        $oUser = User::create([
            'email' => $oRequest->email,
            'password' => $sPassword,
            'user_type' => config('constants.USERTYPE.ADMIN'),
            'role' => 2,
            'sub_role'  => $oRequest->role_id,
            'client_code' => Auth::user()->client_code,
            'status'    => $oRequest->status,
            'is_first' => TRUE,
            'db_version' => 1,
        ]);

        $oProfile = Profile::create([
            'user_id'   =>  $oUser->id,
            'email'     =>  $oRequest->email,
            'name'      =>  $oRequest->name,
        ]);

        DB::commit();

        return response()->json([
            'success'   => true,
            'message'   => trans('messages.user_create_success'),
            'data'      => []
        ]);
    }

    public function callUpdateUser(Request $oRequest, $nIdUser)
    {
    }
}
