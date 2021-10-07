@extends('layouts.web.main_layout')
@section('content')
<section id="" class="content profile_page survey_page">
   <div class="container">
   	<div class="show_survery_info">
   		<div class="col-md-3">
            <div class="box box-solid survey_info">
               <div class="box-body">
                  {{--*/ $oAssignmentSchedulDate = \Carbon::createFromFormat(config('constants.DATEFORMAT.TIMESTAMP'), $oAssignment->assignment_scheduled_date) /*--}}
                  {{--*/ $oAssignmentStartDate = \Carbon::createFromFormat(config('constants.DATEFORMAT.TIMESTAMP'), $oAssignment->assignment_start_date_time) /*--}}
                  {{--*/ $oAssignmentDeadlineDate = \Carbon::createFromFormat(config('constants.DATEFORMAT.TIMESTAMP'), $oAssignment->assignment_deadline_date_time) /*--}}
                  <div class="row">
                     <div class="col-md-12">
                        <span class="btn btn-primary btn-block">
                           <i class="fa fa-calendar"></i> {{ $oAssignmentStartDate->format(config('constants.DATEFORMAT.DATEDISPLAY')) }}
                        </span>
                     </div>
                  </div>
                  <ul class="list-group list-group-unbordered">
                     <li class="list-group-item">
                        {{ trans('messages.start') }} <span class="pull-right"><small>{{ $oAssignmentStartDate->format(config('constants.DATEFORMAT.DATETIMEDISPLAY')) }}</small></span>
                     </li>
                     <li class="list-group-item">
                        {{ trans('messages.end') }} <span class="pull-right"><small>{{ $oAssignmentDeadlineDate->format(config('constants.DATEFORMAT.DATETIMEDISPLAY')) }}</small></span>
                     </li>
                  </ul>
               </div>
            </div>
            <div class="box box-solid">
               <div class="box-body">
                  <strong><i class="fa fa-th margin-r-5"></i> {{ trans('messages.project') }}</strong>
                  <p class="text-muted">{{ $oSurveyDetails->project_name }}</p>
                  <hr>
                  <strong><i class="fa fa-dot-circle-o margin-r-5"></i> {{ trans('messages.round') }}</strong>
                  <p class="text-muted">{{ $oSurveyDetails->round_name }}</p>
                  <hr>
                  <strong><i class="fa fa-map-marker margin-r-5"></i> {{ trans('messages.location') }}</strong>
                  <p class="text-muted">{{ $oSurveyDetails->site_location }}</p>
                  
               </div>
            </div>
         </div>
         <div class="col-md-9">
            <div class="box box-solid">
               <div class="box-body fill-survey">
                  <div class="controls-holder review-survey">
                     {!! $oSurvey->filled_surveydata !!}
                  </div>
                  {{-- @foreach($aSurveyQuestions as $nQuestionNo => $aSurveyQuestion)

                  @if($aSurveyQuestion['que_no'] != 'name_service_code')
                  <div class="row survey_question">
                     <div class="col-md-12">
                        <label class="label label-primary que_no">{{ trans('messages.q').' '.$nQuestionNo }} </label>
                        <label class="control-label label_text" >{{ $aSurveyQuestion['ques'] }}</label>
                        <div class="form-group">
                           @include('WebView::client.surveys.survey_answer', ['aSurveyQuestion' => $aSurveyQuestion])
                        </div>
                     </div>
                  </div>
                  @endif
                  @endforeach --}}
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@stop

