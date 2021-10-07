<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $fillable = [
        'name', 'slug', 'is_default'
    ];

    protected $connection = 'mysql';
    protected $table = 'roles';


    public function users(){
        return $this->belongsTo('App\Models\User','role_id');
    }
}
