<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\AppHelper;
use Illuminate\Http\Request;
use App\Assignment;
use Auth;

use App\Http\Requests;
class surveys extends Model
{
    //
    protected $fillable = [
    'template_id',
    'assignment_id',
    'reference_id',
    'service_code',
    'template',
    'surveydata',
    'filled_surveydata',
    'keypairs',
    'is_exported',
    'status',
    'is_auto_export',
    'exported_at'
    ];

    public function getSurveyDetail(surveys $survey){
        $details['code']            =   $survey->assignments->assignment_code;
        $details['round_name']      =   '['.format_code($survey->assignments->rounds->id).'] '.$survey->assignments->rounds->round_name;
        $details['fieldrep_name']   =   $survey->assignments->fieldreps->first_name.' '.$survey->assignments->fieldreps->last_name;
        $details['site_name']       =   $survey->assignments->sites->site_name;
        $details['site_code']       =   $survey->assignments->sites->site_code;
        $details['site_location']   =   $survey->assignments->sites->street.', '.$survey->assignments->sites->city.', '.$survey->assignments->sites->state;
        $details['project_name']    =   $survey->assignments->rounds->projects->project_name;
        $details['client_name']     =   $survey->assignments->rounds->projects->chains->clients->client_name;
        $details['reported_at']   =   $survey->assignments->reported_at;

         return $details;
    }

    public function assignments()
    {
        return $this->belongsTo(Assignment::class,'assignment_id');
    }

    public function getSurveyStatus($status){
        $survey_satus = '';
        if($status == 0){
            $survey_satus = '<span class="label label-default">Pending</span>';
        }else if($status == 1){
            $survey_satus = '<span class="label label-primary">Scheduled</span>';
        }else if($status == 2){
            $survey_satus = '<span class="label bg-purple">Reported</span>';
        }else if($status == 3){
            $survey_satus = '<span class="label label-warning">Partial</span>';
        }else if($status == 4){
            if(Auth::user()->hasRole('admin')){
                $survey_satus = '<span class="label label-success">Approved</span>';
            }else if(Auth::user()->hasRole('fieldrep')){
                $survey_satus = '<span class="label label-success">Completed</span>';
            }
        }
        return $survey_satus;
    }
}
