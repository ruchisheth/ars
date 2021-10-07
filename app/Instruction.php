<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
    protected $fillable = 
    	[
    		'round_id',
    		'instruction_name',
    		'is_default',
    		'instruction',
    		'attachment',
    		'offer_instruction',
    		'offer_attachment',
    	];

    public function assignments()
    {
        return $this->belongsToMany(Assignment::class,'assignments_instructions');
    }


    public function getAssignmentHasInstruction(Instruction $instruction){

        $assignments = $instruction->assignments;

        $sites = [];

        $assignments = $assignments->all();

        foreach($assignments as $assignment){

            $sites[$assignment->id] = $assignment->sites->site_name;
        }

        return $sites;
        
    }
}
