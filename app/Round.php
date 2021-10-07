<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Round;

use App\Assignment;

use App\Project;
use App\Setting;
use App\RoundsAcknowledge;
use App\Http\AppHelper;
use Carbon;
use Auth;
use DB;

class Round extends Model
{
    protected $fillable = ['project_id','template_id','round_name','start_date','deadline_date', 'schedule_date', 'start_time', 'deadline_time', 'activity','survey_entry_before','survey_entry_after', 'is_bulletin', 'bulletin_text', 'status'];
    
    protected $table = 'rounds';

    protected $user_role = '';

    protected $timezone = '';

    public function setDefaults(){
        if($this->user_role == ''){
            // $this->user_role = Auth::user()->roles->slug;
            if(Auth::check()){
				$this->user_role = Auth::user()->roles->slug;
			}else{
				$this->user_role = config('constants.USERTYPE.ADMIN');
			}
        }
        
        if($this->timezone == ''){

            if($this->user_role == 'admin'){

                $this->timezone = AppHelper::getSelectedTimeZone();
            }else{
                $this->timezone = \Session::get('local_tz');
            }
        }
    }

    public function getStartDateAttribute($value){
        $this->setDefaults();
        $date = $value;
        
        $time = AppHelper::convertTimeZone($this->start_time, $this->timezone, 'UTC');
        $time = date_formats($time,AppHelper::TIME_SAVE_FORMAT);
        $date_time = $date.' '.$time;

        $value = AppHelper::convertTimeZone($date_time, 'UTC', $this->timezone);
        return date_formats($value,AppHelper::DATE_DISPLAY_FORMAT);
    }

    public function getDeadlineDateAttribute($value){
        $this->setDefaults();

        $date = $value;
        $time = AppHelper::convertTimeZone($this->deadline_time, $this->timezone, 'UTC');
        $time = date_formats($time,AppHelper::TIME_SAVE_FORMAT);

        $date_time = $date.' '.$time;
        $value = AppHelper::convertTimeZone($date_time, 'UTC', $this->timezone);
        
        return date_formats($value,AppHelper::DATE_DISPLAY_FORMAT);
    }

    public function getScheduleDateAttribute($value){
        $this->setDefaults();

        $date = $value;
        $time = AppHelper::convertTimeZone($this->start_time, $this->timezone, 'UTC');
        $time = date_formats($time,AppHelper::TIME_SAVE_FORMAT);        
        $date_time = $date.' '.$time;

        $value = AppHelper::convertTimeZone($date_time, 'UTC', $this->timezone);
        
        return date_formats($value,AppHelper::DATE_DISPLAY_FORMAT);
    }

    public function getStartTimeAttribute($value){
        $this->setDefaults();
        
        $value = AppHelper::convertTimeZone($value, 'UTC', $this->timezone);
        return format_time($value,AppHelper::TIME_DISPLAY_FORMAT);
    }

    public function getDeadlineTimeAttribute($value){
        $this->setDefaults();

        $value = AppHelper::convertTimeZone($value, 'UTC', $this->timezone);
        return format_time($value,AppHelper::TIME_DISPLAY_FORMAT);
    }

    public function setStartDateAttribute($value){
        $this->setDefaults();

        $date = $value;
        $time = AppHelper::convertTimeZone($this->start_time, $this->timezone, AppHelper::getSelectedTimeZone());

        $utc_date = AppHelper::getUTCDateTime($date, $time);
        
        $start_date = date_formats($utc_date,AppHelper::DATE_SAVE_FORMAT);

        $this->attributes['start_date'] = $start_date;

    }

    public function setDeadlineDateAttribute($value){
        $this->setDefaults();

        $date = $value;

        $time = AppHelper::convertTimeZone($this->deadline_time, $this->timezone, AppHelper::getSelectedTimeZone());

        $utc_date = AppHelper::getUTCDateTime($date, $time);
        
        $deadline_date = date_formats($utc_date,AppHelper::DATE_SAVE_FORMAT);

        $this->attributes['deadline_date'] = $deadline_date;
    }

    public function setScheduleDateAttribute($value){

        $this->setDefaults();

        $date = $value;

        $time = AppHelper::convertTimeZone($this->start_time, $this->timezone, AppHelper::getSelectedTimeZone());

        $utc_date = AppHelper::getUTCDateTime($date, $time);
        
        $schedule_date = date_formats($utc_date,AppHelper::DATE_SAVE_FORMAT);

        $this->attributes['schedule_date'] = $schedule_date;        
    }

    public function setStartTimeAttribute($value){
        if($value == ""){
            $this->attributes['start_time'] = NULL;
        }else{
            $date = '';
            $time = $value;

            $utc_date = AppHelper::getUTCDateTime($date, $time);        


            $start_time = date_formats($utc_date,AppHelper::TIME_SAVE_FORMAT);

            $this->attributes['start_time'] = $start_time;
        }
    }

    public function setDeadlineTimeAttribute($value){
        if($value == ""){
            $this->attributes['deadline_time'] = NULL;
        }else{
            $date = '';
            $time = $value;

            $utc_date = AppHelper::getUTCDateTime($date, $time);

            $deadline_time = date_formats($utc_date,AppHelper::TIME_SAVE_FORMAT);

            $this->attributes['deadline_time'] = $deadline_time;
        }
    }


    public function assignments()
    {
        return $this->hasMany(Assignment::class,'round_id');
    }

    public function projects()
    {
        return $this->belongsTo(Project::class,'project_id');
    }

    public function acknowledgements()
    {
        return $this->hasMany(RoundsAcknowledge::class);
    }

    public function surveys()
    {
        return $this->hasManyThrough(
            'App\surveys', 'App\Assignment',
            'round_id', 'assignment_id', 'id'
            );
    }

    public function isDeadlinePast(){
        $this->setDefaults();
        $current_date = Carbon::now($this->timezone);
        //$current_date = Carbon::now(\Session::get('timezone'))->setTime(0, 0, 0);

        $end_date = Carbon::parse($this->deadline_date.' '.$this->deadline_time, $this->timezone);
        return $current_date->gt($end_date) ? true :false;
    }
    
    public static function getUserRoundsEndInThreeDays($nIdUser){
            $nIdFieldRep = User::find($nIdUser)->UserDetails->id;

            $oRounds = Assignment::from('assignments as a')
            ->leftJoin('rounds as r', 'r.id', '=', 'a.round_id')
            ->leftJoin('fieldreps as f', 'f.id', '=', 'a.fieldrep_id')
            ->where([
                'f.id' => $nIdFieldRep,
                'a.is_reported' => FALSE,
                'a.is_scheduled' => TRUE,
                'a.is_partial' => FALSE,
            ])
            ->where(DB::raw('IFNULL(a.deadline_date, r.deadline_date)'), '>=', DB::raw('CURDATE()'))
            ->where(DB::raw('IFNULL(a.deadline_date, r.deadline_date)'), '<=', DB::raw('DATE_ADD(CURDATE(), INTERVAL +4 DAY)'))
            ->select(
                'a.id as assignment_id',
                'r.id as round_id',
                'r.round_name as round_name',
                'a.deadline_date as assignment_end', 
                'r.deadline_date as round_deadline_date', 
                'f.id as fieldrep_id', 
                'f.first_name as first_name', 
                'f.last_name as last_name',
                DB::raw("CONCAT(IFNULL(a.deadline_date, r.deadline_date),' ',IFNULL(a.deadline_time, r.deadline_time)) as round_end")
            )
            ->groupBy('round_id')
            ->orderBy('round_end')
            ->get();

            return $oRounds;
        }

        public static function getCountOfUserRoundsEndInThreeDays($nIdUser){
            $oRounds = self::getUserRoundsEndInThreeDays($nIdUser);
            return $oRounds->count();
        }
}
