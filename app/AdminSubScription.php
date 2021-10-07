<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\AdminSubScription;

class AdminSubScription extends Model
{
	protected $connection = 'mysql';

	protected $primaryKey = 'id_admin_subscription';

	protected $table = 'admin_subscriptions';

	protected $fillable = ['id_admin', 'subscription_start_on', 'subscription_end_on', 'status'];
}
