<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class _List extends Model
{
    protected $table = '_list';

    protected $fillable = ['id','list_name','item_name','list_order'];
}
