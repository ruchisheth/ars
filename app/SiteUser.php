<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteUser extends Model
{

		protected $table = 'users';

    protected $fillable = [
        'user_id', 'user_name', 'user_type', 'role', 'email', 'password','status'
    ];

    
}
