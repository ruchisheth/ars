<?php

namespace App;

use App\RolesUser;
use Auth;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ARS
{
	// protected $permissionsLoaded = FALSE;

	protected $permissions = [];

	protected $user = NULL;

	public function canOrNot($aPermission, $nIdRole = NULL)
	{
		if(!is_array($aPermission)){
			$aPermission = [$aPermission];
		}
		if($nIdRole == NULL){

			if($this->isUserTypeAdmin()){
				return TRUE;
			}

			$nIdRole = $this->getAuthUserSubRole();

		}else{
			session()->forget('user_permissions');
		} 

		if (!$this->can($aPermission, $nIdRole)) {
			return false;
		}

		return true;
	}

	public function canOrFail($aPermission, $nIdRole = NULL)
	{
		if(!is_array($aPermission)){
			$aPermission = [$aPermission];
		}
		
		if($nIdRole == NULL){
			if($this->isUserTypeAdmin()){
				return TRUE;
			}
			$nIdRole = $this->getAuthUserSubRole();
			
		}else{
			session()->forget('user_permissions');
		}
		
		if (!$this->can($aPermission, $nIdRole)) {
			throw new UnauthorizedHttpException(null);
		}
	}

	public function can($aPermission, $nIdRole)
	{
		return $this->roleHasPermission($aPermission, $nIdRole);
	}

	public function setRolePermissions($nIdRole = NULL)
	{
		if($nIdRole == NULL){
			$nIdRole = $this->getAuthUserSubRole();
		}else{
			$this->permissions = RolesUser::find($nIdRole)->permissions()->pluck('permission', 'id')->toArray();
			// \Session::put('user_permissions', $this->permissions);
		}
	}

	public function getRolePermissions($nIdRole)
	{
		return RolesUser::find($nIdRole)->permissions()->pluck('permission', 'id')->toArray();
		// if(!(\Session::has('user_permissions'))){
		// 	$this->setRolePermissions($nIdRole);
		// }
		
		// return \Session::get('user_permissions');
	}

	public function roleHasPermission($permission, $nIdRole){
		// http_response_code(500);
		return !empty(array_intersect($this->getRolePermissions($nIdRole), $permission));
		// return in_array($permission, $this->getRolePermissions($nIdRole));
	}

	public function getAuthUserSubRole(){
		return Auth::user()->sub_role;
	}

	public function isUserTypeAdmin(){
		$oUser = Auth::user();
		
		if($oUser->user_type == config('constants.USERTYPE.ADMIN') && $oUser->sub_role == NULL){
			return TRUE;
		}
		return FALSE;
	}
}



