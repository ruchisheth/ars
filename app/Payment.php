<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Assignment;

class Payment extends Model
{
	protected $table = 'assignments_payments';

    protected $fillable = [
    	'assignment_id',
    	'payment_type',
    	'qty',
        'rep_payment_type',
    	'pay_rate',
    	'pay_type',
    ];

    public function assignments()
    {
        return $this->belongsTo(Assignment::class,'assignment_id');        
    }
}
