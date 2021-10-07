<div class="tab-pane active" id="scheduled_assignments">
   @if($oAssignments->total() > 0)
   @foreach($oAssignments as $oAssignment)
   <div class="row no-padding">
      <div class="card col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
         <div class="cards-date-block col-md-2">
            {{--*/ $oAssignmentSchedulDate = \Carbon::createFromFormat(config('constants.DATEFORMAT.TIMESTAMP'), $oAssignment->assignment_scheduled_date) /*--}}
            {{--*/ $oAssignmentStartDate = \Carbon::createFromFormat(config('constants.DATEFORMAT.TIMESTAMP'), $oAssignment->assignment_start_date_time) /*--}}
            {{--*/ $oAssignmentDeadlineDate = \Carbon::createFromFormat(config('constants.DATEFORMAT.TIMESTAMP'), $oAssignment->assignment_deadline_date_time) /*--}}
            <div class="date-block">
               <p class='month'>{{ $oAssignmentSchedulDate->format(config('constants.DATEFORMAT.DISPLAYMONTH')) }}</p>
               <span class='day'>{{ $oAssignmentSchedulDate->day }}</span>
            </div> 
         </div>
         <div class="card-detail col-md-8">
            <h2>{{ $oAssignment->project_name }}</h2>
            <p><i class="fa fa-dot-circle fa-lg"></i>{{ $oAssignment->round_name }}</p>
            <p><i class="fa fa-cubes fa-lg"></i>{{$oAssignment->site_name.', '.format_location($oAssignment->city, $oAssignment->state, $oAssignment->zipcode)}}</p>
            <p>
               <i class="fa fa-calendar fa-lg"></i>
               <b>{{ trans('messages.start') }}</b> : {{ $oAssignmentStartDate->format(config('constants.DATEFORMAT.DATETIMEDISPLAY')) }} | <b>{{ trans('messages.end') }}</b> : {{ $oAssignmentDeadlineDate->format(config('constants.DATEFORMAT.DATETIMEDISPLAY')) }}
            </p>
            @if(@$sAssignmentStatus == config("constants.ASSIGNMENTSTATUS.COMPLETED"))
            {{--*/ $oAssignmentApprovedDate = \Carbon::createFromFormat(config('constants.DATEFORMAT.TIMESTAMP'), $oAssignment->assignment_approved_date_time) /*--}}
            <p>
               <i class='fa fa-check'></i>
               <b>{{ trans('messages.approved_date') }}</b> : {{ $oAssignmentApprovedDate->format(config('constants.DATEFORMAT.DATEDISPLAY')) }}
            </p>
            @endif
         </div>
         @if(@$sAssignmentStatus == config("constants.ASSIGNMENTSTATUS.COMPLETED") || @$sAssignmentStatus == config("constants.ASSIGNMENTSTATUS.REPORTED"))

         <div class="card-other-detail pull-right col-md-2">
            <a href="{{ route('view-survey',['nIdSurvey' => $oAssignment->id_survey]) }}" target="_blank" class="btn">{{ trans('messages.view_survey') }}</a>
         </div>
         @endif

         @if(@$sAssignmentStatus == config("constants.ASSIGNMENTSTATUS.SCHEDULED") && Auth::user()->user_type == config('constants.USERTYPE.FIELDREP'))
         <div class="card-other-detail pull-right col-md-2">
            @if($oAssignment->isSurveyAvailable())
            <a href="{{ url('/fieldrep/survey/').'/'.Crypt::encrypt($oAssignment->survey_id).'/'.base64_encode(Auth::User()->client_code) }}" target="_blank" class="btn">{{ trans('messages.fill_survey') }}</a>
            @else
            @if(!$oAssignment->isSurveyDeadlinePast()){
            <small>Survey will be available soon<small>";
               @endif
               @endif
            </div>
            @endif
         </div>
      </div>
      @endforeach
      @else
      @include('layouts.web.data_not_found')
      @endif
   </div>
   <!--Pagination-->
   <div class="card-pagination pull-right">
      <nav aria-label="Page navigation example">
       {{-- <ul class="pagination"> --}}
         {{ $oAssignments->links() }}
      {{-- </ul> --}}
   </nav>
</div>
<!--Pagination-->