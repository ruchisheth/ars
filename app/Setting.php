<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
	protected $fillable = ['user_id', 'timezone', 'logo', 'invitaton_link', 'syi_api_key', 'theme_color'];

  /**
    * Get User Meta for User.
    *
    * @var string $meta_key (meta_key name)
  */
  // public function getSetting($setting_name = NULL)
  // {
  // 	if($setting_name == NULL){
  // 		return $this->usermeta->pluck('meta_value', 'meta_key');
  // 	}else{
  // 		$usermeta = $this->usermeta();
  // 		$usermeta = $usermeta->where('meta_key','=', $meta_key)->get(['meta_value'])->first();
  // 		if($usermeta != null){
  // 			$meta_value = $usermeta->meta_value;
  // 			return $meta_value;
  // 		}
  // 		return;
  // 	}
  // }
}
