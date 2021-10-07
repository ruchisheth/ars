<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Auth;

class User extends Authenticatable
{
    // protected $connection = 'mysql';
    
    protected $table = 'users';
   
    protected $fillable = ['email', 'password', 'user_type', 'role', 'sub_role', 'client_code', 'db_version', 'is_first', 'status'];

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    // public function __construct(array $attributes = array())
    // {
    //     parent::__construct($attributes);

    //     if(isset ($_SERVER['HTTP_HOST']) && 
    //         (
    //             $_SERVER['HTTP_HOST'] == preg_replace('#^http(s)?://#', '', env('SUPER_ADMIN_URL')) || 
    //             $_SERVER['HTTP_HOST'] == preg_replace('#^http(s)?://#', '', 'http://wts.alpharepservice.com')) 
    //         ) 
    //     {
    //         $this->connection = null;
	// 	}else{
	// 		$this->connection = 'mysql';
	// 	}
    // }

    public function hasrole($role = null){
        $hasrole = false;

        // $role = (array)$role;
        // $user_role = $this->roles()->get(['slug'])->first()->toArray()['slug'];
        // if(in_array($user_role, $role)){
        //     $hasrole = true;
        // }
        $roles = $this->roles()->get();
        foreach ($roles as $res_role){
            if ($res_role->slug == $role){
                $hasrole = true;
            }
        }
        return $hasrole;
    }

    public function roles()
    {
        return $this->hasOne(Role::class,'id','role');
    }
    
//  public function roles_user(){
// 		return $this->hasOne(UsersRole::class,'id','role');
// 	}

    public function client()
    {
        return $this->hasOne('App\Client', 'id_user');
    }
    
    public function UserDetails(){
        $oLoggedInUser = Auth::user();
        if($oLoggedInUser->user_type == config('constants.USERTYPE.SUPERADMIN')){
			return  $this->hasOne(Profile::class,'id_user');
		}elseif($oLoggedInUser->user_type == config('constants.USERTYPE.ADMIN')){
			return  $this->hasOne(Profile::class,'user_id');
		}elseif($oLoggedInUser->user_type == config('constants.USERTYPE.FIELDREP') || $oLoggedInUser->role == 3){
			return $this->hasOne(FieldRep::class,'user_id');
		}elseif($oLoggedInUser->user_type == config('constants.USERTYPE.CLIENT')){
			return $this->hasOne(Client::class,'id_user');
		}
    }

    public function ClientDetails(){        
        if($this->hasrole('admin')){
            return  $this->hasOne(User::class,'id');
        }
        if($this->hasrole('super_admin')){

            return  $this->hasOne(User::class,'id');
        }
    }
    
    // public function UserDetails(){

    //     if($this->hasrole('super_admin')){
    //         // return  $this->hasOne(AdminClient::class,'user_id');
    //         return  $this->hasOne(Profile::class,'id_user');
    //         //return  $this->hasOne(SiteUser::class,'user_id');
    //     }elseif($this->hasrole('admin')){
    //         return  $this->hasOne(Profile::class,'user_id');
    //         return  $this->hasOne(AdminClient::class,'user_id');
    //         //return  $this->hasOne(SiteUser::class,'user_id');
    //     }
    //     elseif($this->hasrole('fieldrep')){
    //         return $this->hasOne(FieldRep::class,'user_id');
    //     }
    // }
    
}
