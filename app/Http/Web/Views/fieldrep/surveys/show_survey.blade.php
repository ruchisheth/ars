@extends('layouts.web.main_layout')
@section('page-title') | View Survey @stop
@section('content')
<section id="" class="content profile_page survey_page">
  <div class="fill-survey container">
    <div class="row">
      {{--*/ $oAssignmentSchedulDate = \Carbon::createFromFormat(config('constants.DATEFORMAT.TIMESTAMP'), $oAssignment->assignment_scheduled_date) /*--}}
      {{--*/ $oAssignmentStartDate = \Carbon::createFromFormat(config('constants.DATEFORMAT.TIMESTAMP'), $oAssignment->assignment_start_date_time) /*--}}
      {{--*/ $oAssignmentDeadlineDate = \Carbon::createFromFormat(config('constants.DATEFORMAT.TIMESTAMP'), $oAssignment->assignment_deadline_date_time) /*--}}
      <div class="survey-info">
        <div class="info-header">
          <div class="icon-image img-150">
            <img src="https://d1pcf5ua3u1h1w.cloudfront.net/user-media/150_1522912122.jpg">
          </div>
          <div class="entity-name"> 
            {{ $oSurveyDetails->client_name }}
          </div>
        </div>
        <div class="survey-details">
          <strong><i class="fa fa-clock margin-r-5"></i> Schedule Date</strong>
          <p class="text-muted">{{ $oAssignmentStartDate->format(config('constants.DATEFORMAT.DATETIMEDISPLAY')) }}</p>
        </div>
        <div class="survey-details">
          <strong><i class="fa fa-th margin-r-5"></i> Project</strong>
          <p class="text-muted">{{ $oSurveyDetails->project_name }}</p>
        </div>
        <div class="survey-details">
          <strong><i class="fa fa-dot-circle margin-r-5"></i> Round</strong>
          <p class="text-muted">{{ $oSurveyDetails->round_name }}</p>
        </div>
        <div class="survey-details">
          <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
          <p class="text-muted">{{ $oSurveyDetails->site_location }}</p>
        </div>
      </div>

      <div class="col-md-10 survey-fill-section">
        <div class="box box-solid">
          <div class="box-header with-border">
            <h4 class="box-title">Survey
            </h4>
          </div>
          <div class="box-body fill-survey">
            <div class="controls-holder review-survey">
             {!! $oSurvey->filled_surveydata !!}
           </div>
         </div>
       </div>
     </div>
   </div>
 </section>
</div>
@stop