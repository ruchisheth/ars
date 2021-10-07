<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldRep_Pay extends Model
{
    protected $table = 'fieldrep_pays';   

    protected $fillable = ['fieldrep_id','project_type','client_id','item','rate','pay_type','notes'];


}
