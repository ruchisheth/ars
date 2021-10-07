  <div class="row"><!-- assignment editor  -->
    <!-- modal -->
    <div class="modal fade" id="assignments_edit">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">
              Assignment Edit 
              <small><span id="status-label" class="">Approved</span></small>           
            </h4>

          </div>
          <div class="modal-body">
            {{  Form::open([
              'method'=>'post',
              'url' => route('add.assignment'), 
              'id' => 'assignment_edit']) 
            }}

            {{  Form::hidden('assignment_id',@$assignment_id)  }}

            <div class="box">
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="alert" style="display: none"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      {{  Form::label('site', 'Site')}}
                      {{  Form::text(
                        'site_name','',
                        [
                        'id' => 'site_name',
                        'disabled' => true,
                        'class' => 'form-control',
                        ])
                      }} 
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      {{  Form::label('fieldrep_id', 'Field Rep')}}
                      {{  Form::text(
                        'fieldrep_name','',
                        [
                        'id' => 'fieldrep_name',
                        'class' => 'form-control',
                        'readonly' => 'true',
                        ])
                      }}
                    </div>
                  </div>
                </div>
                

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      {{  Form::label('round_start_date', 'Actual Start Date')}}
                      {{  Form::text(
                        'round_start_date','',
                        [
                        'id' => 'round_start_date',
                        'class' => 'form-control ',
                        'disabled' => 'true',
                        ])
                      }} 
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                     {{  Form::label('start_date', 'Override Start Date')}}
                     {{  Form::text(
                       'start_date','',
                       [
                       'id' => 'assignment_start_date',
                       'class' => 'form-control no_key e_i',

                       ])
                     }} 
                   </div>
                 </div>
               </div><!-- /.row -->
               <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    {{  Form::label('round_deadline_date', 'Actual Deadline Date')}}
                    {{  Form::text(
                      'round_deadline_date','',
                      [
                      'id' => 'round_deadline_date',
                      'class' => 'form-control',
                      'disabled' => 'true',
                      ])
                    }} 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                   {{  Form::label('dealine_date', 'Override Deadline Date')}}
                   {{  Form::text(
                     'deadline_date','',
                     [
                     'id' => 'assignment_deadline_date',
                     'class' => 'form-control no_key e_i',

                     ])
                   }} 
                 </div>
               </div>
             </div><!-- /.row -->

             <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  {{  Form::label('round_schedule_date', 'Schedule Date')}}
                  {{  Form::text(
                    'round_schedule_date','',
                    [
                    'id' => 'round_schedule_date',
                    'class' => 'form-control',
                    'disabled' => 'true',
                    ])
                  }} 
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  {{  Form::label('schedule_date', 'Override Schedule Date')}}
                  {{  Form::text(
                    'schedule_date','',
                    [
                    'id' => 'assignment_schedule_date',
                    'class' => 'form-control no_key e_i',
                    ])
                  }} 
                </div>
              </div>
            </div><!-- /.row -->

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  {{  Form::label('round_start_time', 'Actual Start Time')}}
                  {{  Form::text(
                    'round_start_time','',
                    [
                    'id' => 'round_start_time',
                    'class' => 'form-control no_key',
                    'disabled' => 'true',
                    ])
                  }} 
                </div>
              </div>
              <div class="col-md-6">
                <div class="bootstrap-timepicker">
                  <div class="form-group">
                    <label>Start Time</label>
                    {{  Form::text(
                      'start_time','',
                      [
                      'id' => 'assignment_start_time',
                      'class' => 'form-control timepicker e_i',

                      ])
                    }} 
                  </div><!-- /.form group -->
                </div>
              </div>
            </div><!-- /.row -->

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  {{  Form::label('round_deadline_time', 'Actual Deadline Time')}}
                  {{  Form::text(
                    'round_deadline_time','',
                    [
                    'id' => 'round_deadline_time',
                    'class' => 'form-control',
                    'disabled' => 'true',
                    ])
                  }} 
                </div>
              </div>
              <div class="col-md-6">
                <div class="bootstrap-timepicker">
                  <div class="form-group">
                   {{  Form::label('deadline_time', 'Deadline Time')}}
                   {{  Form::text(
                     'deadline_time','',
                     [
                     'id' => 'assignment_deadline_time',
                     'class' => 'form-control timepicker e_i',
                     ])
                   }} 
                 </div>
               </div>
             </div>
           </div><!-- /.row -->
         </div><!-- .box-body -->
         <div class="box-footer">

          <div class="pull-right">
            <div class="pull-right">
              <button type="button" class="btn btn-primary" id="save_assignment" name="save_assignment">Save</button>
            </div>
            <div class="col-md-1 pull-right">
              <button type="button" data-dismiss="modal" id="cancel" class="btn btn-default pull-right">Cancel</button>
            </div>
          </div>

          <h6><small class="text-grey">
            <label class="modal-label">Created</label> <label class="modal-label" for="created_at"></label> |
            <label class="modal-label">Last modified</label> <label class="modal-label" for="updated_at"></label>
          </small></h6>

        </div><!-- /.box-footer -->
      </div><!-- /.box -->

      {{ Form::close() }}
    </div><!-- /.modal-body -->
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div><!-- /row -->


@section('custom-script')

<script type="text/javascript">
  var rows_selected = [];
  $(document).ready(function () {
    var round_start_date = $('#round_start_date').val();
    console.log(round_start_date);

    $('#assignment_start_date').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
    },function(chosen_date) {
      initAssignmentDates(chosen_date.format('DD MMM YYYY'));
    });
    //initAssignmentDates();

    
    $("#assignment_start_time, #assignment_deadline_time").timepicker({
      showInputs: false,
      showMeridian: true,
      minuteStep: 5,
    });

    $('#assignments_edit').on('hidden.bs.modal', function () {
      $('.alert').hide();
      var form = $("#assignment_edit");
      form[0].reset();
    });
    

    $(document).on('click', 'button[name="save_assignment"]', function (e) {
      e.preventDefault();
      
      var form = $("#assignment_edit");
      var formData = form.serialize();
      console.log(formData);
      var url = form.attr('action');
      var type = "POST";
      $.ajax({
        type: type,
        url: url,
        data: formData,
        dataType: 'json',
        success: function (data) {
          $("#assignments_edit").modal('hide');
          oAssignmentTable.draw(true);
          DisplayMessages(data['message']);
        },
        error: function (jqXHR, exception) {
          var Response = jqXHR.responseText;
          ErrorBlock = $(form).find('.alert');
          Response = $.parseJSON(Response);
          DisplayErrorMessages(Response, ErrorBlock, 'div');
        }
      });
    });/*   Save Assignment */

    $(document).on('click', 'button[name="remove_assignment"]', function(e){
      e.preventDefault();
      var assignment_id = $(this).data('id') || "";
      var round_id = "";
      if(assignment_id != ""){
        deselect_all();
        $('#assignments-grid tbody input[type="checkbox"].entity_chkbox:checked').iCheck('uncheck');
        rows_selected.push(assignment_id);
      }else{
        round_id = $(this).data('round_id') || "";
        if(round_id != ""){
          deselect_all();
          $('#assignments-grid tbody input[type="checkbox"].entity_chkbox:checked').iCheck('uncheck');
        }
      }

      var assignment_ids = rows_selected;
      var formData = {assignment_ids :assignment_ids, round_id: round_id};
      if(round_id == ""){
        var oModalPopUp = $('#delete_assignment');
      }else{
        var oModalPopUp = $('#delete_assignments');
      }
        // $('#delete_assignments').modal({ backdrop: 'static', keyboard: false });
    //   $('#delete_assignments').find('#delete').bind('click', function() {
          
      oModalPopUp.modal({ backdrop: 'static', keyboard: false });
      oModalPopUp.find('#delete').bind('click', function() {
          
      

        $.ajax({
          type: 'POST',
          url: APP_URL+'/assignments-delete',
          data: formData,
          dataType: 'json',
          success: function (data) {
            oAssignmentTable.draw();
            oInstructionTable.draw();
            /* remove already selected sites and add reamain to list box*/
            $('select[name="available_store[]"]').empty();
            $.each(data.sites, function(key, value) {
              $('select[name="available_store[]"]')
              .append($("<option></option>")
                .attr("value",key)
                .text(value));
            });
            DisplayMessages(data.message);
          },
          error: function (data) {
            var Response = data.responseText;
            Response = $.parseJSON(Response);
            DisplayMessages(Response.message,'error');
          }
        });
      });
    });
    // $(document).on('click', 'button[name="remove_assignment"]', function(e){

    //   e.preventDefault();

    //   var $form=$(this).closest('form');
    //   var $parent_tr = $(this).closest('tr');
    //   var assignment_id =  $(this).data('id') || "";
    //   var round_id = "";
    //   if(assignment_id == ""){
    //     round_id = $(this).data('round_id');
    //   }
    //   var formData = {assignment_id :assignment_id, round_id: round_id};
    //   var url = APP_URL+'/assignments-delete';
    //   var type = "POST";

    //   $('#confirm').modal({ backdrop: 'static', keyboard: false });
    //   $('#confirm').find('#delete').bind('click', function() {
    //     $.ajax({
    //       type: type,
    //       url: url,
    //       data: formData,
    //       dataType: 'json',
    //       success: function (data) {

    //         oAssignmentTable.draw();
    //         oInstructionTable.draw();
    //         /* remove already selected sites and add reamain to list box*/
    //         $('select[name="available_store[]"]').empty();
    //         $.each(data.sites, function(key, value) {
    //           $('select[name="available_store[]"]')
    //           .append($("<option></option>")
    //             .attr("value",key)
    //             .text(value));
    //         });
    //         DisplayMessages(data.message);
    //       },
    //       error: function (data) {
    //         var Response = data.responseText;
    //         Response = $.parseJSON(Response);
    //         DisplayMessages(Response.message,'error');
    //       }
    //     });
    //   });
    // });
    /*  Delete Assignment  */

  });/* ready over*/

  function initAssignmentDates(){
    if(arguments.length <= 0 || arguments[0] === undefined){
      if($('#assignment_start_date').val() != ''){
        min_date = $('#assignment_start_date').val();
      }else{
        max_date = $('#round_start_date').val();
      }
    }else{
      min_date = arguments[0];
    }

    if(arguments.length <= 1 || arguments[1] === undefined){
      if($('#assignment_deadline_date').val() != ''){
        max_date = $('#assignment_deadline_date').val();
      }else{
        max_date = $('#round_deadline_date').val(); 
      }
    }else{
      max_date = arguments[1];
    }

    min_dates  = new Date(min_date);
    max_dates  = new Date(max_date);

    if(min_dates > max_dates){
      max_date = min_date;
      $('#assignment_deadline_date').val(min_date);
      $('#assignment_schedule_date').val(min_date);
    }
    $('#assignment_deadline_date').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      minDate: min_date,
      startDate: max_date,
    },function(chosen_date) {
      initAssignmentDates(min_date,chosen_date.format('DD MMM YYYY'));
    });

    $('#assignment_schedule_date').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      minDate: min_date,
      maxDate: max_date,
      startDate: min_date,
    });
  }

  function SetAssignmentEdit(element,e)
  {
    e.preventDefault();
    var Form = $("#assignment_edit");
    var Id = $(element).attr('data-id');
    var APP_URL = $('meta[name="_base_url"]').attr('content');
    var url = APP_URL + '/assignments/' + Id + '/edit';
    $.ajax({
      type: "POST",
      url: url,
      data: "id=" + Id,
      dataType: "json",
      success: function (res) {
        $('#status-label').html(res.inputs.status.value);      
        $('#assignments_edit').modal('show');
          $.fn.modal.Constructor.prototype.enforceFocus = function () { }; //To display month and year selector dropdown in modal
          SetFormValues(res.inputs, Form);
          assignment_status = res.inputs.status.value;
          if(assignment_status == 4){
            setTimeout(function(){
              $('#assignment_edit .e_i').attr('disabled',true);
            },500);
          }else{
            setTimeout(function(){
              $('#assignment_edit .e_i').attr('disabled',false);
            },500);
          }
          $('#assignment_start_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            startDate: res.minDate,
          },function(chosen_date) {
            initAssignmentDates(chosen_date.format('DD MMM YYYY'));
          });
          initAssignmentDates(res.minDate,res.maxDate);
        },
        error:function(jqXHR, exception){
          var Response = jqXHR.responseText;
          Response = $.parseJSON(Response);
          DisplayMessages(Response.message, 'error');
        }
      });
  }

</script>
@append