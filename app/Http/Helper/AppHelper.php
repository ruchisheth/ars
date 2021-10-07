<?php

namespace App\Http;

use Html;
use DB;
use File;
use Auth;
use App\Setting;
use App\User;


class AppHelper{
	
	const APP_URL 						=  	'http://www.alpharepservice.com/';

	const SYI_URL 						=  	'http://www.submityourinvoice.com/';

	const LOGO 								=		'public/assets/dist/img/logo.png';

	const LOGO_WHITE					=		'public/assets/dist/img/logo-white.png';

	const ASSETS 							= 	'public/assets/';

	const CLIENT_LOGO 				= 	'public/assets/images/logos/';

	const USER_IMAGE					= 	'public/assets/images/profile/';

	const SURVEY_UPLOAD 			= 	'public/assets/images/survey/';

	const MAIN_LOGO 					= 	'images/logos/main/';

	const INSTRUCTION_IMG 		= 	'public/assets/images/instruction_img/';

	const IMAGE 							= 	'public/assets/dist/img/';

	const ADMIN_JS 						= 	'public/assets/dist/js/';

	const ADMIN_CSS						=		'public/assets/dist/css/';

	const AVATAR_IMG 					= 	'user-thumbnail.png';

	const DATE_EXPORT_FORMAT 	=		'm/d/Y';

	const DATE_DISPLAY_FORMAT = 	'd M Y';

	const DATE_SAVE_FORMAT 		= 	'Y-m-d';

	const TIME_DISPLAY_FORMAT = 	'h:i A';

	const TIME_SAVE_FORMAT 		= 	'H:i:s';

	const TIMESTAMP_FORMAT 		= 	'Y-m-d H:i:s';

	const DISTANCE_UNIT 			= 	'MILES';


	public static function getClientLogoImage($logo){
		if($logo && File::exists(AppHelper::CLIENT_LOGO.$logo)){
			return Html::image(asset(AppHelper::CLIENT_LOGO.$logo),"",["height"=>"25","width"=>"45","class"=>"client_1"]);
		}
		else{
			return '<i class="fa fa-user text text-gray"></i>';
		}
	}

	//Convert Assoc array to Index
	public static function array_values_recursive( $array ) {
		$array = array_values( $array );
		for ( $i = 0, $n = count( $array ); $i < $n; $i++ ) {
			$element = $array[$i];
			if ( is_array( $element ) ) {
				$array[$i] = self::array_values_recursive( $element );
			}
		}
		return $array;
	}

	public static function combine_recursive($keys, $array ) {
		$array = array_values( $array );
		$result = [];
		$key = reset($keys);
		for ( $i = 0, $n = count( $array ); $i < $n; $i++ ) {
			$element = $array[$i];
			if ( is_array( $element ) ) {
				$result[$i + 2]  = self::combine_recursive($keys, $element );
			}else{
				$result[$keys[key($keys)]] = $element;
			}
			$key = next($keys);
		}
		return $result;
	}

	public static function convertTimeToUTC($date, $format, $timezone){
		if(strlen($date)){
			$new_date = \Carbon::createFromFormat($format, $date, $timezone);
			$new_date->setTimezone('UTC');
		}
		else{
			$new_date = null;
		}
		return $new_date;
	}

	public static function getFileType($extension){
		$fileType = '';
		$extension = strtolower($extension);
		switch($extension){
			case 'png':
			case 'jpg':
			case 'jpeg':
			case 'gif':
			$fileType = 'image';
			break;
			case 'txt':
			$fileType = 'text';
			break;
			case  'pdf':
			$fileType = 'pdf';
			break;
			case  'xls':
			case  'xlsx':
			$fileType = 'xls';
			break;
			case  'doc':
			case  'docx':
			$fileType = 'doc';
			break;
		}
		return $fileType;
	}

	public static function getFilePrevieIcon($extension){
		$previewIcon = '';
		switch($extension){
			case 'png':
			case 'jpg':
			case 'jpeg':
			$previewIcon = 'fa fa-file-photo-o text-warning';
			break;
			case 'txt':
			$previewIcon = 'fa fa-file-text-o text-info';
			break;
			case  'pdf':
			$previewIcon = 'fa fa-file-pdf-o text-danger';
			break;
			case  'xls':
			case  'xlsx':
			$previewIcon = 'fa fa-file-excel-o text-success';
			break;
			case  'doc':
			case  'docx':
			$previewIcon = 'fa fa-file-word-o text-primary';
			break;
		}
		return $previewIcon;
	}

	public static function getDistance($latlong1, $latlong2, $unit = self::DISTANCE_UNIT) {
		$lat1 = $latlong1['lat'];
		$lon1 = $latlong1['long'];

		$lat2 = $latlong2['lat'];
		$lon2 = $latlong2['long'];
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = ceil($dist * 60 * 1.1515);
		$unit = strtoupper($unit);

		if ($unit == "KILOMETER") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else if($unit == "MILES"){
			return $miles;
		}
	}

	public static function getLatlong($address){	
		$latlong = [];
		$latlong['lat'] = null;
		$latlong['long'] = null;
		$address = preg_replace("/\s+/","+",$address);
		
		$geocode_url = 'https://maps.google.com/maps/api/geocode/json?address='.$address.'&key=AIzaSyCmuNcekF2GK0ArAsKTPzaC26eoCEuoDWQ';	

		$geocode = @file_get_contents($geocode_url); 
		
		$res = json_decode($geocode);
		
		if(count($res->results) > 0 && $res->status != 'OVER_QUERY_LIMIT'){
			$latlong['status'] = true;
			$latlong['lat'] = $res->results[0]->geometry->location->lat;
			$latlong['long'] = $res->results[0]->geometry->location->lng;
			return $latlong;
		}
		else{
			$latlong['status'] = false;				
			return $latlong;
		}
	}

	public static function curl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	public static function convertTimeZone($date, $from = 'UTC', $to = ''){
		if($to == ''){
			$to = \Session::get('local_tz');
		}
		$date = \Carbon::parse($date, $from);
		$date->setTimezone($to);
		return $date;
	}

	public static function getSelectedTimeZone(){

		$admin = User::where('role',2)->where('db_version', Auth::user()->db_version)->first();

		$setting = Setting::where(['user_id' => $admin->id])->first();

		return $setting->timezone;
	}

	public static function getUTCDateTime($date = '', $time = ''){
		if(Auth::user()->roles->slug == 'admin'){

			$timezone = self::getSelectedTimeZone();

		}else{

			$timezone = \Session::get('local_tz');
			
		}

		if($date == ''){

			$date = date(AppHelper::DATE_SAVE_FORMAT);
		}

		$date = date_formats($date,self::DATE_SAVE_FORMAT);

		$time = date_formats($time,self::TIME_SAVE_FORMAT);

		$date_time = $date.' '.$time;

		return self::convertTimeZone($date_time, $timezone, 'UTC');
	}

	public static function getLocalTimeZone($date,$format,$request = ''){
		if(strlen($date)){
			$date = \Carbon::parse($date);
			$format = self::TIMESTAMP_FORMAT;
			$new_date = \Carbon::createFromFormat($format, $date)->setTimezone(\Session::get('local_tz'));
		}else{
			$new_date = null;
		}
		return $new_date;
	}

	public static function getTimeZone(){
		return $timezones = [
		"Etc/GMT+12"											=> '(GMT-12:00) Internation Date Line West',
		"Etc/GMT+11"											=> '(GMT-11:00) Coordinated Universal Time-02',
		"Pacific/Honolulu" 								=> "(GMT-10:00) Hawaii",
		"America/Anchorage" 							=> "(GMT-08:00) Alaska",
		"America/Tijuana" 								=> "(GMT-07:00) Baja California, Tijuana",
		"America/Los_Angeles" 						=> "(GMT-07:00) Pacific Time (US & Canada)",
		"America/Phoenix" 								=> "(GMT-07:00) Arizona",
		"America/Chihuahua" 							=> "(GMT-07:00) Chihuahua, La Paz, Mazatlan", //"(GMT-06:00) Chihuahua, La Paz, Mazatlan",
		"America/Denver" 									=> "(GMT-07:00) Mountain Time (US & Canada)", //"(GMT-06:00) Mountain Time (US & Canada)",
		"America/Managua" 								=> "(GMT-06:00) Central America",
		"America/Chicago" 								=> "(GMT-06:00) Central Time (US & Canada)", //"(GMT-05:00) Central Time (US & Canada)",
		"America/Mexico_City" 						=> "(GMT-06:00) Guadalajara, Mexico_City, Monterrey", //"(GMT-05:00) Guadalajara, Mexico_City, Monterrey",
		"America/Regina" 									=> "(GMT-06:00) Saskatchewan",
		"America/Bogota" 									=> "(GMT-05:00) Bogota, Lima, Quito, Rio Branco",
		"America/Monterrey" 							=> "(GMT-05:00) Chetumal",
		"America/New_York" 								=> "(GMT-05:00) Eastern Time (US & Canada)", //"(GMT-04:00) Eastern Time (US & Canada)",
		"America/Indiana/Indianapolis" 		=> "(GMT-04:00) Indiana (East)",
		"America/Caracas"	 								=> "(GMT-04:30) Caracas", ///*"America/Caracas"*/
		"America/Asuncion" 								=> "(GMT-04:00) Asuncion",
		"America/Halifax" 								=> "(GMT-04:00) Atlantic Time (Canada)",//"(GMT-03:00) Atlantic Time (Canada)",
		"America/Cuiaba" 									=> "(GMT-04:00) Cuiaba",
		"America/Manaus" 									=> "(GMT-04:00) Georgetown, La Paz, Manaus, San Juan", //"(GMT-03:00) Georgetown, La Paz, Manaus, San Juan",
		"America/St_Johns" 								=> "(GMT-03:30) Newfoundland", //"(GMT-02:30) Newfoundland",
		"America/Sao_Paulo" 							=> "(GMT-03:00) Brasilia",
		"America/Cayenne" 								=> "(GMT-03:00) Cayenne, Fortaleza",
		"America/Argentina/Buenos_Aires" 	=> "(GMT-03:00) City of Buenos Aires",
		"America/Godthab" 								=> "(GMT-03:00) Greenland", //"(GMT-02:00) Greenland",
		"America/Montevideo" 							=> "(GMT-03:00) Montevideo",
		"America/Halifaxs" 								=> "(GMT-03:00) Salvador",
		"Etc/GMT+3" 											=> "(GMT-03:00) Santiago", //"(GMT-04:00) Santiago",
		//"America/Santiago" 							=> "(GMT-04:00) Santiago",
		"Etc/GMT+2" 											=> "(GMT-02:00) Coordinated Universal Time-02",
		"Atlantic/Azores" 								=> "(GMT-01:00) Azores", //"(GMT+00:00) Azores",
		"Atlantic/Cape_Verde" 						=> "(GMT-01:00) Cape Verde Is.",
		"Etc/GMT-1" 											=> "(GMT) Casablanca",
		"Etc/GMT"					 								=> "(GMT) Coordinate Universal Time",
		"Europe/London" 									=> "(GMT) Dublin, Edinburgh, Lisbon, London",
		"Atlantic/Reykjavik" 							=> "(GMT) Monrovia, Reykjavik",
		"Europe/Berlin" 									=> "(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna",
		"Europe/Belgrade" 								=> "(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague",
		"Europe/Paris" 										=> "(GMT+01:00) Brussels, Copenhagen, Madrid, Paris",
		"Europe/Sarajevo" 								=> "(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb",
		"Africa/Lagos" 										=> "(GMT+01:00) West Central Africa",
		"Africa/Windhoek" 								=> "(GMT+01:00) Windhoek",
		"Asia/Amman" 											=> "(GMT+02:00) Amman",
		"Europe/Athens" 									=> "(GMT+02:00) Athens, Bucharest",
		"Asia/Beirut" 										=> "(GMT+02:00) Beirut",
		"Africa/Cairo" 										=> "(GMT+02:00) Cairo",
		"Asia/Damascus" 									=> "(GMT+02:00) Damascus",
		"Africa/Johannesburg" 						=> "(GMT+02:00) Harare, Pretoria",
		"Europe/Helsinki" 								=> "(GMT+02:00) Helsinki, Kiev, Riga, Sofia, Tallinn, Vilnius",
		"Europe/Istanbul" 								=> "(GMT+02:00) Istanbul",
		"Asia/Jerusalem" 									=> "(GMT+02:00) Jerusalem",
		"Europe/Kaliningrad" 							=> "(GMT+02:00) Kaliningrad (RTZ-1)",
		"Africa/Tripoli" 									=> "(GMT+02:00) Tripoli",
		"Asia/Baghdad" 										=> "(GMT+03:00) Baghdad",
		"Asia/Riyadh" 										=> "(GMT+03:00) Kuwait, Riyadh",
		"Europe/Minsk" 										=> "(GMT+03:00) Minsk",
		"Europe/Moscow" 									=> "(GMT+03:00) Moscow, St. Petersburg, Volgograd",
		"Africa/Nairobi" 									=> "(GMT+03:00) Nairobi",
		"Asia/Tehran" 										=> "(GMT+03:30) Tehran",
		"Asia/Muscat" 										=> "(GMT+04:00) Abu Dhabi, Muscat",
		"Etc/GMT-5" 											=> "(GMT+04:00) Baku",
		"Europe/Samara" 									=> "(GMT+04:00) Izhevsk, Samara (RTZ 3)",
		"Etc/GMT-4"			 									=> "(GMT+04:00) Port Louis",
		"Asia/Tbilisi" 										=> "(GMT+04:00) Tbilisi",
		"Asia/Yerevan" 										=> "(GMT+04:00) Yerevan",
		"Asia/Kabul" 											=> "(GMT+04:30) Kabul",
		"Asia/Tashkent" 									=> "(GMT+05:00) Ashgabat Tashkent",
		"Asia/Yekaterinburg" 							=> "(GMT+05:00) Ekaterinburg (RTZ 4)",
		"Asia/Karachi"	 									=> "(GMT+05:00) Islamabad, Karachi",
		"Asia/Kolkata" 										=> "(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi",
		"Asia/Colombo" 										=> "(GMT+05:30) Sri Jayawardenepura",
		"Asia/Katmandu" 									=> "(GMT+05:45) Kathmandu",
		"Etc/GMT-6" 											=> "(GMT+06:00) Astana",
		"Asia/Dhaka" 											=> "(GMT+06:00) Dhaka",
		"Asia/Novosibirsk" 								=> "(GMT+06:00) Novosibirsk",
		"Asia/Rangoon" 										=> "(GMT+06:30) Yangon (Rangoon)",
		"Asia/Bangkok" 										=> "(GMT+07:00) Bangkok, Hanoi, Jakarta",
		"Asia/Krasnoyarsk"								=> "(GMT+07:00) Krasnoyarsk",
		"Asia/Hong_Kong"			  					=> "(GMT+08:00) Beijing, Chongqing, Hong Kong SAR, Urumqi",
		"Asia/Irkutsk"			    					=> "(GMT+08:00) Irkutsk (RTZ 7)",
		"Asia/Singapore"			  					=> "(GMT+08:00) Kuala Lumpur, Singapore",
		"Australia/Perth"			  					=> "(GMT+08:00) Perth",
		"Asia/Taipei"					  					=> "(GMT+08:00) Taipei",
		"Etc/GMT-9"			 									=> "(GMT+08:00) Ulaanbataar",
		"Asia/Pyongyang" 									=> "(GMT+08:30) Pyongyang",
		"Asia/Tokyo" 											=> "(GMT+09:00) Osaka, Sapporo, Tokyo",
		"Asia/Seoul" 											=> "(GMT+09:00) Seoul",
		"Asia/Yakutsk" 										=> "(GMT+09:00) Yakutsk",
		"Australia/Adelaide" 							=> "(GMT+09:30) Adelaide",
		"Australia/Darwin" 								=> "(GMT+09:30) Darwin",
		"Australia/Brisbane" 							=> "(GMT+10:00) Brisbane",
		"Australia/Sydney" 								=> "(GMT+10:00) Canberra, Melbourne, Sydney",
		"Pacific/Guam"		 								=> "(GMT+10:00) Guam, Port Moresby",
		"Australia/Hobart" 								=> "(GMT+10:00) Hobart",
		"Asia/Magadan" 										=> "(GMT+10:00) Magadan",
		"Asia/Vladivostok" 								=> "(GMT+10:00) Vladivostok",
		"Etc/GMT-11"											=> "(GMT+11:00) Chokurdakh, Solomon Is. New Caledonia",
		"Etc/GMT-12"											=> "(GMT+12:00) Anadyr, Petropavlovsk-Kamchatsky, Coordinated Universal Time+12",		
		"Pacific/Auckland" 								=> "(GMT+12:00) Auckland, Wellington",
		"Pacific/Fiji" 										=> "(GMT+12:00) Fiji",
		"Pacific/Tongatapu" 							=> "(GMT+13:00) Nuku'alofa",
		"Pacific/Apia" 										=> "(GMT+13:00) Samoa",
		"Pacific/Kiritimati" 							=> "(GMT+14:00) Kiritimati Island",
		];
	}
}