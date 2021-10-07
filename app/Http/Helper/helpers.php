<?php
use App\Http\AppHelper;
use App\Round;
use App\Project;
use App\Site;
use App\Assignment;
use Carbon\Carbon;

function date_formats($date,$format = "{{ AppHelper::DATE_SAVE_FORMAT }}"){
	if($date != null){
		return date($format, strtotime($date));	
	}

}

function format_time($time,$format = "{{ AppHelper::DATE_SAVE_FORMAT }}"){
	if($time != null){
		if($format == '12'){
			return date("g:i A", strtotime($time));
		}
		elseif($format == '24'){
			return date("H:i:s", strtotime($time));
		}else{
			return date($format, strtotime($time));
		}	
	}
}

function format_location($city,$state,$zipcode){
	$location = "";
	if($city != ''){
		$location .= $city .", ";
	}
	if($state != ''){
		$location .= $state." ";
	}
	if($zipcode != ''){
		$location .= $zipcode;
	}
	return $location;
}

function format_code($id, $digit = '6'){
	return (string)str_pad($id, $digit, '0', STR_PAD_LEFT);
}

function fullname($firstname,$lastname){
	$fullname = "";
	if($firstname != ''){
		$fullname .= $firstname ." ";
	}
	if($lastname != ''){
		$fullname .= $lastname;
	}
	return $fullname;
}

function image_resize($image_path, $h, $w){
	$image = $image_path;
		//$image = env("FRONT_IMG").'uploads/default/cover.jpg';

	$img = Image::make($image)->resize($h,$w);
	return $img->response();
}

function get_unique_num($digit = 4){

	return mt_rand(str_pad(1, $digit, '0', STR_PAD_LEFT), str_pad(9, $digit, '9', STR_PAD_LEFT));
}

function get_available_sites($round){
	$project = Project::where(['id'=>$round->project_id])->first(['chain_id']);

	$assignment = Assignment::where(['round_id'=>$round->id])->selectRaw('group_concat(site_id) as selected_sites')->get();
	$selected_sites = explode(',', $assignment[0]->selected_sites);

	//$sites = Site::where(['chain_id'=>$project->chain_id])->where('status','=','1')->whereNotIn('id', $selected_sites)->select(DB::raw("CONCAT(site_name, ', ', city, ', ', state) AS site_names, id"))->lists('site_names', 'id')->all();
	$sites = Site::where(['chain_id'=>$project->chain_id])->where('status','=','1')->whereNotIn('id', $selected_sites)->select(DB::raw("CONCAT(site_code,'-',site_name, ', ', city, ', ', state) AS site_names, id"))->orderBy(DB::raw('lpad(trim(site_code), 10, 0)'), 'asc')->lists('site_names', 'id')->all();
	return $sites;
}

function upload_file($path,$file){

	$name =  $file->getClientOriginalName();

	$extension = $file->getClientOriginalExtension();

	$file_name =  md5(uniqid().time()).".".$extension;

	$file->move($path,$file_name);

	return $file_name;
}
function UploadFile($file,$destination,$prefix=""){
	$name =  $file->getClientOriginalName();
	$extension = $file->getClientOriginalExtension();
	$encrypted_name = $prefix.md5(uniqid().time()).".".$extension;
	$file->move($destination,$encrypted_name);
	$Image = $destination.$encrypted_name;
    chmod($Image,0777);
	return array(
		"name"=>$name,        
		"encrypted_name"=>$encrypted_name
	); 
}

if (! function_exists('escape_like')) {
    /**
     * @param $string
     * @return mixed
     */
    function escape_like($string)
    {
    	$search = array('<', '>');
    	$replace   = array('', '');
    	return str_replace($search, $replace, $string);
    }
}

if (! function_exists('getClientCodeFromURL')) {
	function getClientCodeFromURL(){

		$sUrl = trim(url()->current(), '/');

		if (!preg_match('#^http(s)?://#', $sUrl)) {
			$sUrl = 'http://' . $sUrl;
		}

		$aUrlParts = parse_url($sUrl);

		$sDomain = $sSubDomain = preg_replace('/^www\./', '', $aUrlParts['host']);

		if(preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $sSubDomain, $matches))
		{
			$sDomain = $matches['domain'];
		} else {
			$sDomain = $domain;
		}

		$sClientCode = rtrim(strstr($sSubDomain, $sDomain, true), '.');

		/* just for multiple sub-domain http://www.klp.ars.ts.wingsts.com */
		if(strpos($sClientCode, ".")){
			$sClientCode = strstr($sClientCode, '.', true );
		}

		return $sClientCode;
	}
}

if (! function_exists('getFileType')) {
	function getFileType($extension){
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
	if (! function_exists('getTextImage')) {
		function getTextImage($sFirstName, $sLastName){
			$html = '<span class=" deault-img profile-background-color-'.(strlen($sFirstName.$sLastName))%config('constants.COLORCOUNT').'" >'
			.'<p>'.mb_substr($sFirstName, 0, 1).' '.mb_substr($sLastName, 0, 1).'</p>'
			.'</span>';

			return $html;
		}
	}
}

if (! function_exists('getImage')) {
	function getImage($sImagePath){
		if($sImagePath && File::exists(public_path($sImagePath))){
			return Html::image(asset('public'.$sImagePath), "", ["height" => "25", "width" => "45", "class" => "client_1"]);
		}
		return '<i class="fa fa-user text text-gray"></i>';
	}
}

if (! function_exists('getImageURL')) {
	function getImageURL($sImagePath){
		if($sImagePath && File::exists(public_path($sImagePath))){
		    if(is_dir(public_path($sImagePath))){
				return  asset('public'.config('constants.AVATARIMAGE')) ;
			}
			return asset('public'.$sImagePath);
		}
		return  asset('public'.config('constants.AVATARIMAGE')) ;
	}
}




