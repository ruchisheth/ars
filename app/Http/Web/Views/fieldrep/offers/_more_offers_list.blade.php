<div class="tab-pane active" id="scheduled_assignments">
   @if($oAssignmentOffers->total() > 0)
   @foreach($oAssignmentOffers as $oAssignmentOffer)
   <div class="row no-padding">
      <div class="card col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
         <div class="cards-date-block col-md-2">
            {{--*/ $oAssignmentSchedulDate = \Carbon::createFromFormat(config('constants.DATEFORMAT.TIMESTAMP'), $oAssignmentOffer->assignment_scheduled_date) /*--}}
            {{--*/ $oAssignmentStartDate = \Carbon::createFromFormat(config('constants.DATEFORMAT.TIMESTAMP'), $oAssignmentOffer->assignment_start_date_time) /*--}}
            {{--*/ $oAssignmentDeadlineDate = \Carbon::createFromFormat(config('constants.DATEFORMAT.TIMESTAMP'), $oAssignmentOffer->assignment_deadline_date_time) /*--}}
            <div class="date-block">
               <p class='month'>{{ $oAssignmentSchedulDate->format(config('constants.DATEFORMAT.DISPLAYMONTH')) }}</p>
               <span class='day'>{{ $oAssignmentSchedulDate->day }}</span>
            </div> 
         </div>
         <div class="card-detail col-md-8">
            <h2>{{ $oAssignmentOffer->project_name }}</h2>
            <p><i class="fa fa-dot-circle-o fa-lg"></i>{{ $oAssignmentOffer->round_name }}</p>
            <p><i class="fa fa-cubes fa-lg"></i>{{$oAssignmentOffer->site_name.', '.format_location($oAssignmentOffer->city, $oAssignmentOffer->state, $oAssignmentOffer->zipcode)}}</p>
            <p>
               <i class="fa fa-calendar fa-lg"></i>
               <b>{{ trans('messages.start') }}</b> : {{ $oAssignmentStartDate->format(config('constants.DATEFORMAT.DATETIMEDISPLAY')) }} | <b>{{ trans('messages.end') }}</b> : {{ $oAssignmentDeadlineDate->format(config('constants.DATEFORMAT.DATETIMEDISPLAY')) }}
            </p>
            @if(@$sOfferStatus == config("constants.OFFERSTATUS.REJECTED") )
            <p>
               <i class="fa fa-clipboard fa-lg"></i>
               <b>{{ trans('messages.reason_to_reject') }}</b> : {{ ($oAssignmentOffer->reject_reason < 5) ? config('constants.OFFERREJECTREASON.'.$oAssignmentOffer->reject_reason) : $oAssignmentOffer->other_reason }}
            </p>
            @endif
         </div>
         @if(@$sOfferStatus == config("constants.OFFERSTATUS.PENDING"))
         <div class="card-other-detail pull-right col-md-2">
            <a href="javascript:void(0)" class="offer-response offer-accept" data-id_offer="{{ $oAssignmentOffer->id_offer }}"><i class="fa fa-check"></i></a>
            <a href="javascript:void(0)" class="offer-response offer-reject" data-id_offer="{{ $oAssignmentOffer->id_offer }}"><i class="fa fa-times"></i></a>
         </div>
         @endif

         @if(@$sOfferStatus == config("constants.ASSIGNMENTSTATUS.SCHEDULED") && Auth::user()->user_type == config('constants.USERTYPE.FIELDREP'))
         <div class="card-other-detail pull-right col-md-2">
            @if($oAssignment->isSurveyAvailable())
            {{-- <a href='.url("/survey/").'/'.Crypt::encrypt($assignments->survey_id).'/'.base64_encode($sClientCode).' data-fieldrep-id="'.$assignments->survey_id.'">Survey</a>'; --}}
            <a href="{{ url('/fieldrep/survey/').'/'.Crypt::encrypt($oAssignment->survey_id).'/'.base64_encode(Auth::User()->client_code) }}" target="_blank" class="btn">{{ trans('messages.fill_survey') }}</a>
            @else
            @if(!$oAssignment->isSurveyDeadlinePast()){
            <small>{{ trans('messages.survey_available_soon') }}<small>";
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
         {{ $oAssignmentOffers->links() }}
      {{-- </ul> --}}
   </nav>
</div>
<!--Pagination-->
