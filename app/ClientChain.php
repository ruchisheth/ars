<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Chain;

class ClientChain extends Model
{
	public $timestamps = false;

    protected $fillable = ['client_id','chain_id'];

    public function chains()
     {
     	//return $this->hasMany(Chain::class,'reference_id');
     	return $this->hasMany(Chain::class,'id')->select(array('id'));
     }
}
