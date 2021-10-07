<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
	protected $connection = 'mysql';

	protected $primaryKey = 'id_admin';

	protected $table = 'admins';

	protected $fillable = ['email', 'name', 'logo', 'client_code', 'db_version', 'is_invited', 'status'];
  	
  	// protected $table = 'clients';
 	// protected $fillable = ['user_id', 'name','logo', 'is_invited'];
 	
 	public function admin_subscriptions()
	{
		return $this->hasMany(AdminSubScription::class, 'id_admin');
	}
}
