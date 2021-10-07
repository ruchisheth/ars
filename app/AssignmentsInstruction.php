<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Assignment;

class AssignmentsInstruction extends Model
{
    protected $fillable = 
    [
    'assignment_id',
    'instruction_id',
    ];

    public static function applyDefault($instruction_id, $assignment_id){
    	self::create(['instruction_id' => $instruction_id, 'assignment_id' => $assignment_id]);
    }
}
