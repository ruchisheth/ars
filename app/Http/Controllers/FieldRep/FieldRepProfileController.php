<?php

namespace App\Http\Controllers\FieldRep;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\AppHelper;

use Illuminate\Database\Eloquent\Collection;

use Html;

use App\FieldRep,
App\FieldRep_Org,
App\_List,
App\User,
App\Project,
App\AppData,
DB;

use Auth;

class FieldRepProfileController extends Controller
{
    public function getProfile(Request $request){

    	$fieldrep = FieldRep::find(Auth::user()->UserDetails->id);
        $user = User::where(['id'=>$fieldrep->user_id])->first();

    	$organizations = ['' => 'Select FieldRep Organization'] + FieldRep_Org::lists('fieldrep_org_name','id')->all();       

        $highest_edu = ['' => 'Select Education'] + DB::table('_list')->where('list_name','=','rep_highest_edu_level')->orderBy('list_order')->lists('item_name','id','list_order');

        $internet_browser = ['' => 'Select Browser'] + DB::table('_list')->where('list_name','=','rep_internet_browser')->orderBy('list_order')->lists('item_name','id','list_order');
        
        $distance_willing_to_travel = ['' => 'Select Distance'] + DB::table('_list')->where('list_name','=','rep_distance_willing_to_travel')->orderBy('list_order')->lists('item_name','id','list_order');

        $project_types = Project::getProjectTypes();

        $states = ['' => 'Select State'] + DB::table('_list')->where('list_name','=','state')->lists('item_name','id','list_order');        

        $days = array('availability_monday','availability_tuesday','availability_wednesday','availability_thursday','availability_friday','availability_saturday','availability_sunday');

        foreach ($days as $day) {

            $key = array('a','b','c');

            $fieldrep->$day = explode(',',$fieldrep->$day);

            $fieldrep->$day = array_combine($key, $fieldrep->$day);              
        }

        $app = new AppData;

        $entity_type = $app->entity_types['rep'];

        $contact_types = ['' => 'Select Contact'] + $app->contact_types['rep'];

        $fieldrep->updated = date_formats(AppHelper::getLocalTimeZone($fieldrep->updated_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT); 
        $fieldrep->created = date_formats(AppHelper::getLocalTimeZone($fieldrep->created_at,AppHelper::TIMESTAMP_FORMAT,$request),AppHelper::DATE_DISPLAY_FORMAT);

        $data = [
            'fieldrep'                      =>  $fieldrep,
            'user'                          =>  $user,
            'organizations'                 =>  $organizations,
            'highest_edu'                   =>  $highest_edu,
            'internet_browser'              =>  $internet_browser,
            'distance_willing_to_travel'    =>  $distance_willing_to_travel,
            'entity_type'                   =>  $entity_type,
            'contact_types'                 =>  $contact_types,
            'project_types'                 =>  $project_types,
            'states'                        =>  $states,
        ];

    	return view('fieldrep.profile',$data);
    }



}
