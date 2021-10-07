<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolesUser extends Model
{
	protected $table = 'roles';

	protected $primaryKey = 'id_role';
	
	protected $fillable = ['name', 'slug', 'role_order'];

	public function permissions()
	{
		return $this->belongsToMany('App\Permission');
	}

}
