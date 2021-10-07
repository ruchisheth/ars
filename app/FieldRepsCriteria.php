<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldRepsCriteria extends Model
{
	protected $table = 'fieldreps_criteria';

    protected $fillable = [
    	'round_id',
    	'has_camera', 
    	'has_internet', 
    	'exp_match_project_type',
        'gender',
    	'distance',
    	'allowable_days',
    	];
}
