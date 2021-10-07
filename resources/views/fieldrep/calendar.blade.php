@extends('fieldrep.app')
@section('page-title') | Calendar @stop
@section('custome-style')
{!! Html::style(AppHelper::ASSETS.'plugins/fullcalendar/fullcalendar.min.css') !!}
{!! Html::style(AppHelper::ASSETS.'plugins/fullcalendar/fullcalendar.print.css',['media' => 'print']) !!}
@stop

@section('content')

	<div class="content-wrapper">
		<section class="content">
    	<div class="box">
    		<div class="box-header with-border">
    			<i class="fa fa-calendar"></i>
    			<h3 class="box-title">Calendar</h3>          
        </div><!-- /.box-header -->  
    		<div class="box-body">
	    		<!-- <div class="col-md-12"> -->
	    			<!-- <div class="box box-default"> -->
              
	    				<!-- <div class="box-body no-padding"> -->
	    					<!-- THE CALENDAR -->    
                <div class="col-sm-12 pull-right">
                <span class="label label-success">{{ trans('messages.assignment_status.completed') }}</span>
                <span class="label label-primary">{{ trans('messages.assignment_status.scheduled') }}</span>
                <span class="label label-danger">{{ trans('messages.assignment_status.rejected') }}</span>
                <span class="label bg-purple">{{ trans('messages.assignment_status.reported') }}</span>          
	    					<div id="calendar"></div>
                </div>
	    				<!-- </div>/.box-body -->
	    			<!-- </div>/. box -->
	    		<!-- </div>/.col -->
	    	</div>
    	</div>
      @include('fieldrep.calendar_modal')
    </section>
  </div>
@stop

@section('custom-script')
  {{ Html::script(AppHelper::ASSETS.'plugins/fullcalendar/fullcalendar.min.js') }}
  
	<script type="text/javascript">

		$(document).ready(function(){
      $('#calendar').fullCalendar({

       contentHeight:650,
       	header: {
            left: 'title',
            center: 'today prev,next',
            right: 'month,agendaWeek,agendaDay'
        },          
        displayEventEnd:true,
        displayEventTime:true,
        events: APP_URL + '/fieldrep/get-events',
        eventColor: '#ff0000',
        eventClick:  function(event, jsEvent, view) {
          jsEvent.preventDefault();
          table = $('#detail-grid');
          var xhrcall = $.ajax({
            type: 'POST',
            url: APP_URL+'/fieldrep/assignment-details',
            data: { assignment_id :event.url },
            success: function (res) {
              details = res.details;
              table.find('#assignment_id').text(details.code);
              table.find('#start_date').text(details.start_date);
              table.find('#deadline_date').text(details.deadline_date);
              table.find('#schedule_date').text(details.schedule_date);
              table.find('#round_name').text(details.round_name);
              table.find('#project_name').text(details.project_name);
              table.find('#client_name').text(details.client_name);
              table.find('#location').text(details.location);
              $('#client_logo').attr('src',APP_URL+'/{{ AppHelper::CLIENT_LOGO }}'+details.client_logo);
            },
          });
          $('#calendarModal').modal('show');
        },
      })
			// $('#calendar').fullCalendar({
			// 	header: {
   //          left: 'title',
   //          center: 'prev,next today',
   //          right: 'month,agendaWeek,agendaDay'
   //      },
   //      buttonText: {
   //          today: 'today',
   //          month: 'month',
   //          week: 'week',
   //          day: 'day'
   //      },
			// });
		});

	</script>

@stop