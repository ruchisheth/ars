@extends('layouts.web.main_layout')
@section('content')
<section class="content">
   <div class="row">
      <div class="col-md-8 col-md-offset-2">
      <div class="box box-solid">
         <div class="cards-panel">
            <div class="tab-content">
               <ul class="nav nav-tabs">
                  <li class="{{ (@$sAssignmentStatus == config("constants.ASSIGNMENTSTATUS.SCHEDULED")) ? 'active' : ''}}" onClick="callShowAssignmentList('{{ config("constants.ASSIGNMENTSTATUS.SCHEDULED") }}', '{{ route('fieldrep.assignment-list') }}');">
                     <a href="#scheduled_assignments" data-toggle="tab">{{ trans('messages.assignment_status.scheduled') }} | {{ $aAssignmentsCount['scheduled_assignments_count'] }}</a>
                  </li>
                  <li class="{{ (@$sAssignmentStatus == config("constants.ASSIGNMENTSTATUS.LATE")) ? 'active' : ''}}" onClick="callShowAssignmentList('{{ config("constants.ASSIGNMENTSTATUS.LATE") }}', '{{ route('fieldrep.assignment-list') }}');">
                     <a href="#scheduled_assignments" data-toggle="tab">{{ trans('messages.assignment_status.late') }} | {{ $aAssignmentsCount['late_assignments_count'] }}</a>
                  </li>
                  <li class="{{ (@$sAssignmentStatus == config("constants.ASSIGNMENTSTATUS.PARTIAL")) ? 'active' : ''}}" onClick="callShowAssignmentList('{{ config("constants.ASSIGNMENTSTATUS.PARTIAL") }}', '{{ route('fieldrep.assignment-list') }}')">
                     <a href="#completed_assignments" data-toggle="tab">{{ trans('messages.assignment_status.rejected') }} | {{ $aAssignmentsCount['partial_assignments_count'] }}</a>
                  </li>
                  <li class="{{ (@$sAssignmentStatus == config("constants.ASSIGNMENTSTATUS.REPORTED")) ? 'active' : ''}}" onClick="callShowAssignmentList('{{ config("constants.ASSIGNMENTSTATUS.REPORTED") }}', '{{ route('fieldrep.assignment-list') }}');">
                     <a href="#scheduled_assignments" data-toggle="tab">{{ trans('messages.assignment_status.reported') }} | {{ $aAssignmentsCount['reported_assignments_count'] }}</a>
                  </li>
                  <li class="{{ (@$sAssignmentStatus == config("constants.ASSIGNMENTSTATUS.COMPLETED")) ? 'active' : ''}}" onClick="callShowAssignmentList('{{ config("constants.ASSIGNMENTSTATUS.COMPLETED") }}', '{{ route('fieldrep.assignment-list') }}')">
                     <a href="#completed_assignments" data-toggle="tab">{{ trans('messages.assignment_status.approved') }} | {{ $aAssignmentsCount['approved_assignments_count'] }}</a>
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
                     @include('WebView::fieldrep.assignments._more_assignments_list', ['oAssignments' => $oAssignments])
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

