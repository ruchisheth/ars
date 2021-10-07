<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\_list;

use DB;


class Project extends Model
{
    
    protected $fillable = ['project_name', 'chain_id', 'project_type', 'can_schedule', 'primary_contact', 'billing_contact', 'status'];

    //protected $appends = ['client'];

    public static function getProjectTypes(){
        $project_types = DB::table('_list')->where('list_name','=','project_types')->orderBy('list_order')->lists('item_name','id');    
        return $project_types;
    }
    
    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function chains()
    {
        return $this->belongsTo(Chain::class,'chain_id','id');
    }

    public function getClientAttribute()
    {
        return $this->chains->clients;
    }

    public function assignments()
    {
        //return $this->hasManyThrough('App\Assignment', 'App\Round');
        return $this->hasManyThrough(
            'App\Assignment', 'App\Round',
            'project_id', 'round_id', 'id'
        );
    }
}

