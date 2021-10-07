<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class surveys_template extends Model
{
    //
    protected $fillable = [
    	'template_name',
    	'template',
    	'questions_data',
    	'question_tags',
    ];
}
