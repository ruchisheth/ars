<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoundsAcknowledge extends Model
{
    protected $fillable = ['round_id', 'fieldrep_id', 'is_acknowledged'];

    public function rounds()
    {
        return $this->belongsTo(Round::class,'round_id');
    }
}
