<!-- assignment editor  -->
<div class="row">
  <div class="modal fade" id="assignment_schedule_modal"><!-- modal -->
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">
            Assignment Schedule
          </h4>
        </div>
        <div class="modal-body">
          {{  Form::open([
            'method' => 'post',
            'url' => route('schedule.assignment'), 
            'id' => 'assignment_schedule']) 
          }}

          {{  Form::hidden('assignment_id')  }}
          {{  Form::hidden('fieldrep_id')  }}

          <div class="box">
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="alert" style="display: none"></div>
                </div>
              </div>
              
              <div class="bg-primary tls" style="">
                <ul>
                  <li class="small"><label>FieldRep : <label for="fieldrep_name"></label></label></li>
                 {{--  <li class="small"><label for="fieldrep_name"></label></li> --}}
                  <li class="small"><label>Location : <label for="location"></label></label></li>
                  {{-- <li class="small"><label for="location"></label></li> --}}
                </ul>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    {{  Form::label('schdule_date', 'Schedule Date', ['class' => 'mandatory'])}}
                    {{  Form::text(
                      'schedule_date','',
                      [
                      'id' => 'assignment_sched',
                      'class' => 'form-control no_key',
                      ])
                    }} 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="bootstrap-timepicker">
                    <div class="form-group">
                      {{  Form::label('start_time', 'Start Time', ['class' => 'mandatory']) }}
                      {{  Form::text(
                        'schedule_start_time','',
                        [
                        'id' => 'schedule_start_time',
                        'class' => 'form-control timepicker',
                        ])
                      }} 
                    </div><!-- /.form group -->
                  </div>
                </div>
              </div>

              <div class="row">                

                <div class="col-md-6">
                  <div class="form-group horizontal-checkbox">
                    <label>
                      {{  Form::checkbox(
                        'notify_via_email',1,false,
                        [
                        'class' => 'minimal custom_radio',
                        'id' => 'notify_via_email',
                        ])
                      }}
                      <span class="chk_label">
                        Notify FieldRep via email.
                      </span>
                    </label>

                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('notes', 'Notes')}}
                    {{  Form::textarea(
                      'notes','',
                      [
                      'id' => 'notes',
                      'class' => 'form-control',
                      'rows' => '2'
                      ])
                    }} 
                  </div>
                </div>
              </div>
              <div class="row">

              </div>
            </div>
            <div class="box-footer">

              <div class="pull-right">
                <div class="pull-right">
                  <button type="button" data-dismiss="modal" id="cancel" class="btn btn-default">Cancel</button>
                </div>
                <div class="col-md-1 pull-right">
                  <button type="button" class="btn btn-primary pull-right" id="schedule_assignment" name="schedule_assignment">Schedule</button>
                </div>
              </div>                                                                                                    
            </div><!-- /.box-footer -->
          </div><!-- /.box -->

          {{ Form::close() }}
        </div><!-- /.modal-body -->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</div><!-- /row -->

@include('includes.confirm-modal',
  [
  'action' => 'Unschedule',
  'name'   => 'Fieldrep',
  'id'  => 'confirm_unschedule',
  'msg' => 'Are you sure to unschedule this FieldRep?',
  'btn' => ['Yes','No'],
  ])


  @section('custom-script')

  <script type="text/javascript">

    $(document).ready(function () {


      $("#schedule_start_time").timepicker({
        showInputs: false,
        showMeridian: true,
        minuteStep: 5,
      });
      
      
      $('#assignment_schedule_modal').on('hidden.bs.modal', function (event) 
      {
       var form = $("#assignment_schedule");
       $('#notify_via_email').iCheck('uncheck');
       form[0].reset();
       $('button[name="schedule_assignment"]').attr('disabled',false);
       if($('#fieldrep_schedule_modal').hasClass('in'))
       {
        setTimeout(function(){$('body').addClass('modal-open')}, 300);
      }
    });

      $(document).on('click', 'button[name="schedule_assignment"]', function (e) {

        e.preventDefault();
        $('button[name="schedule_assignment"]').attr('disabled',true);
        var form = $("#assignment_schedule");
        var formData = $(form).serialize();
        var url = form.attr('action');
        var type = "POST";
        $.ajax({
         type: "POST",
         url: url,
         data: formData,
         dataType: 'json',
         success: function (res) {
           $("#assignment_schedule_modal").modal('hide');
           setTimeout(function(){$("#fieldrep_schedule_modal").modal('hide');}, 300);
           oAssignmentTable.draw(true);
           DisplayMessages(res.message);
         },
         error: function (jqXHR, exception) {
           var Response = jqXHR.responseText;
           ErrorBlock = $(form).find('.alert');
           Response = $.parseJSON(Response);
           DisplayErrorMessages(Response, ErrorBlock, 'div');
           $('button[name="schedule_assignment"]').attr('disabled',false);
         }
       });
      });/*   Schedule Assignment */


    }); /* ready over*/

    function initAssignmentSchedule(min_date,max_date){
      $('#assignment_sched').daterangepicker({
        "singleDatePicker": true,
        "showDropdowns": true,
        "minDate": min_date,
        "maxDate": max_date,
      });
    }

    function unscheduleRep(ele,event){
      event.preventDefault();
      assignment_id = $(ele).data('id');
      type = 'POST';
      var url =  APP_URL+'/unschedule-rep';
      $('#confirm_unschedule').modal({keyboard: false });

    //$('#confirm_unschedule').modal({ backdrop: 'static', keyboard: false }).one('click', '#delete', function(){
      $('#confirm_unschedule').find('#delete').bind('click', function() {    
        $.ajax({
          type: type,
          url: url,
          data: { assignment_id: assignment_id },
          dataType: 'json',
          success: function (data) {
            oAssignmentTable.draw(true);
            DisplayMessages(data['message']);
          },
          error: function (data) {
          //DisplayMessages('Please try again','error');
        }
      });
      });
    }

  </script>
  @append