@extends('layouts.web.main_layout')
@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="box box-solid">
        <div class="cards-panel">
          <div class="tab-content">
            <ul class="nav nav-tabs">
              <li class="{{ (@$sAssignmentStatus == config("constants.ASSIGNMENTSTATUS.SCHEDULED")) ? 'active' : ''}}" onClick="callShowAssignmentList('{{ config("constants.ASSIGNMENTSTATUS.SCHEDULED") }}', '{{ route('client.assignment-list') }}')">
                <a href="#scheduled_assignments" data-toggle="tab">{{ trans('messages.assignment_status.scheduled') }} | {{ $nScheduledAssignmentCount }}</a>
              </li>
              <li class="{{ (@$sAssignmentStatus == config("constants.ASSIGNMENTSTATUS.COMPLETED")) ? 'active' : ''}}" onClick="callShowAssignmentList('{{ config("constants.ASSIGNMENTSTATUS.COMPLETED") }}', '{{ route('client.assignment-list') }}')">
                <a href="#completed_assignments" data-toggle="tab">{{ trans('messages.assignment_status.completed') }} | {{ $nCompletedAssignmentCount }}</a>
              </li>
            </ul>
            <div class="card-body">
              <div class="card-list">
                {{-- <div class="input-group margin">
                  <input type="text" class="form-control" placeholder="Search...">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </span>
                </div> --}}

                @include('layouts.web.loader')
                <div class="" id="assigment-list">
                 @include('WebView::client.assignments._more_assignments_list', ['oAssignments' => $oAssignments])
               </div>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </section>
{{-- </div> --}}
<!--Tab Content-->
{{-- </section> --}}
@stop

