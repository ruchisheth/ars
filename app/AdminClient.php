<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminClient extends Model
{
	protected $connection = 'mysql';
    protected $table = 'clients';

    protected $fillable = [
        'user_id', 'name','logo', 'is_invited'
    ];
}
