<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{

	protected $primaryKey = 'id';

	protected $table = 'site_settings';

    protected $fillable = ['setting_key', 'setting_value'];
}
