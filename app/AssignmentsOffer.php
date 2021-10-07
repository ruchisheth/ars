<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Assignment;

class AssignmentsOffer extends Model
{
   protected $fillable = 
    	[
    		'assignment_id',
    		'fieldrep_id',
    		'is_accepted',
            'reject_reason',
    		'other_reason',
    	];

    public function assignments()
    {
     	return $this->belongsTo(Assignment::class,'assignment_id');
    }
}
