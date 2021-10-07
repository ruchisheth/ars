<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    

    protected $fillable = ['fieldrep_id','rating_category', 'rating','rater', 'effective_date'];

}
