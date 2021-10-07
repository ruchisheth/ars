<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">
      Instructions
    </h3>
    <div class="box-tools pull-right">
      <!-- <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
      {{  Form::button('<i class="fa fa-plus"></i>',
        [
        'id' => 'create_assignment_instructions',
        'class' => 'btn btn-box-tool',
        'data-toggle' => 'modal',
        'data-target' => '#assignment_insturctions'
        ])
      }}
    </div>
    <div class="col-md-6" style="float:right">
      <div class="alert" style="display: none"></div>
    </div>
  </div><!-- /.box-header -->
  <div class="box-body">
    <div class="table-responsive">
      <table id="assignment-instructions-grid" class="table table-bordered">
        <thead>
          <tr>
            <!-- <th>Code</th> -->
            <th>Name</th>
            <th>Applied To</th>
            <th>&nbsp;</th>  <!-- apply to assignment -->
            <th>&nbsp;</th> <!-- action -->
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div><!-- /.box-body -->
</div><!-- /.box -->

<div class="row"><!-- assignment generator -->
  <!-- modal -->
  <div class="modal fade" id="assignment_insturctions">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">
            Add / Edit Instruction
          </h4>
        </div>
        <div class="modal-body">
          <div class="nav-tabs-custom no-shadow">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#schedule_instruction_tab" id="schedule_instruction" data-toggle="tab">Schedule Instruction</a></li>
              <li><a href="#offer_instruction_tab" data-toggle="tab">Offer Instruction</a></li>
            </ul>
            {{  Form::open([
              'method'  =>  'post',
              'url'  =>  route('store.instruction'),
              'id' => 'assignment_instructions_form',
              'enctype'  =>  'multipart/form-data',
              ]) 
            }}

            <div class="tab-content">
              <div class="active tab-pane" id="schedule_instruction_tab">
                {{  Form::hidden('round_id',@$round_id)  }}
                {{  Form::hidden('instruction_id','0',['id' => 'instruction_id'])  }}

                <div class="box no-border">
                  <div class="box-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="alert" style="display: none"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          {{  Form::label('instruction_name', 'Instruction Name',['class' => 'mandatory']) }}
                          {{  Form::text('instruction_name', '',
                            [
                            'id' => 'instruction_name',
                            'class' => 'form-control',
                            'autofocus' => true,
                            ])
                          }}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>
                            {{  Form::checkbox(
                              'is_default',1,false,
                              [
                              'id' => 'is_default',
                              'class' => 'minimal custom_radio',
                              ])
                            }}
                            <span class="chk_label">
                              Default instruction for all new assignments
                            </span>
                          </label>

                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          {{  Form::label('instruction', 'Assignment Instructions',['class' => 'mandatory']) }}
                          {{  Form::textarea(
                            'instruction','',
                            [
                            'id' => 'assignment_instruction',
                            'class' => 'form-control',
                            'rows' => 2,
                            ]) 
                          }}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          {{  Form::label('attachment', 'Assignment Attachments') }}
                          <div class="custom-file-input custom-size">
                            {{  Form::file(
                              'attachment[]',
                              [
                              'id' => 'attachment',
                              'class' => 'file-loading',
                              'data-allowed-file-extensions'  =>  '["gif", "jpg","png", "txt", "pdf"]',
                              'multiple' => 'multiple',
                              ]) 
                            }}
                          </div>
                          <p class="help-block">Upload your Attachment.</p>
                        </div>
                      </div>
                    </div>      
                  </div>
                  <div class="box-footer">

                    <div class="pull-right">
                      <div class="pull-right">
                        <button type="button" class="btn btn-primary" id="save_instruction" name="save_instruction">Save</button>
                      </div>
                      <div class="col-md-1 pull-right">
                        <button type="button" data-dismiss="modal" id="cancel" class="btn btn-default pull-right">Cancel</button>
                      </div>
                    </div>                                                                                                    
                  </div><!-- /.box-footer -->
                </div><!-- /.box -->
              </div>
              <div class="tab-pane" id="offer_instruction_tab">
                <div class="box no-border">
                  <div class="box-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="alert" style="display: none"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          {{  Form::label('offer_instruction_name', 'Instruction Name',['class' => 'mandatory']) }}
                          {{  Form::text('offer_instruction_name', '',
                            [
                            'id' => 'offer_instruction_name',
                            'class' => 'form-control',                
                            ])
                          }}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          {{  Form::label('instruction', 'Offer Instructions') }}
                          {{  Form::textarea(
                            'offer_instruction','',
                            [
                            'id' => 'assignment_instruction',
                            'class' => 'form-control',
                            'rows' => 2,
                            ]) 
                          }}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          {{  Form::label('offer_attachment', 'Offer Attachments') }}
                          <div class="custom-file-input custom-size">
                            {{  Form::file(
                              'offer_attachment[]',
                              [
                              'id' => 'offer_attachment',
                              'class' => 'file-loading',
                              'data-allowed-file-extensions'  =>  '["gif", "jpg","png", "txt", "pdf"]',
                              'multiple' => 'multiple',
                              ]) 
                            }}
                          </div>
                          <p class="help-block">Upload your Attachment.</p>
                        </div>
                      </div>
                    </div>      
                  </div>
                  <div class="box-footer">

                    <div class="pull-right">
                      <div class="pull-right">
                        <button type="button" class="btn btn-primary" id="save_instruction" name="save_instruction">Save</button>
                      </div>
                      <div class="col-md-1 pull-right">
                        <button type="button" data-dismiss="modal" id="cancel" class="btn btn-default pull-right">Cancel</button>
                      </div>
                    </div>                                                                                                    
                  </div><!-- /.box-footer -->
                </div><!-- /.box -->
              </div><!-- /.tab-pane -->
            </div><!-- /.tab-content -->
            {{ Form::close() }}
          </div><!-- /.nav-tabs-custom -->
        </div><!-- /.modal-body  -->
      </div><!-- /.modal-content  -->
    </div><!-- /.modal-dialog  -->
  </div><!-- /.modal -->
</div><!-- /row -->

@include('admin.assignments.assignment_insturctions_apply_modal')

@include('includes.confirm-modal',['name'   => 'Assignment Instruction','id' => 'delete_instruction_modal'])

@section('custom-script')


<script type="text/javascript">

  $(document).ready(function () { 

    $("#attachment").fileinput(fileinput_options);   
    $("#offer_attachment").fileinput(fileinput_options);

    $('#is_default').on('ifChecked', function(event){
      $('#instruction_name').val('Default Instruction');
      $('#offer_instruction_name').val('Default Instruction');
      //$('#instruction_name').attr('readonly', true);
    });

    $('#instruction_name').on('change',function(event){
      var instruction_name = $('#instruction_name').val();
      $('#offer_instruction_name').val(instruction_name);
    });

    $('#offer_instruction_name').on('change',function(event){
      var offer_instruction_name = $('#offer_instruction_name').val();
      $('#instruction_name').val(offer_instruction_name);
    });


    $('#is_default').on('ifUnchecked', function(event){
      $('#instruction_name').val('');
      $('#offer_instruction_name').val('');
     // $('#instruction_name').attr('readonly', false);           
   });

    // $('.file-zoom-dialog').on('hidden.bs.modal',function(){
    //   // if($('#assignment_insturctions').hasClass('in'))
    //   // {
    //   //   setTimeout(function(){$('body').addClass('modal-open')}, 1000);
    //   // }

    // });



  //   $(function() {
  //  // Place the above code inside this block
  //   $('.btn btn-default btn-close').click(function() {
  //   $('#kvFileinputModal').modal('hide');
  //   });
  // });

     // $('#kvFileinputModal').click(function() {
     // $('#kvFileinputModal').modal('hide');
     // });

     $('#assignment_insturctions').on('hidden.bs.modal', function () {
      $('.alert').hide();
      var form = $("#assignment_instructions_form");
      form[0].reset();

      $('#instruction_id').val('0');

      $('#is_default').iCheck('update');

      $('#instruction_name').attr('readonly', false); 
      $("#attachment").fileinput('clear');
      $("#attachment").fileinput('destroy');

      $("#offer_attachment").fileinput('clear');
      $("#offer_attachment").fileinput('destroy');

      $("#attachment").fileinput('refresh',fileinput_options);
      $("#offer_attachment").fileinput(fileinput_options);

      $('#schedule_instruction').trigger('click');

    });

     oInstructionTable = $('#assignment-instructions-grid').DataTable({
      "processing": true,
      "serverSide": true,
      "paging": false,
      "bFilter": false,
      "bInfo": false,
      "ordering": false,
      "autoWidth":true,
      "order": [ 0, "desc" ],
      ajax: {
        url: '{!! url("instructions/round",[@$round_id]) !!}',
        type: 'POST',
      },
      "aoColumnDefs": [
      // {'bSortable': false, 'aTargets': [2,3]},
      { "sWidth": "7%", "targets": [0,3] },
      { "sWidth": "25%", "targets": [1] },
      { "sWidth": "30%", "targets": [2] },
      ],
    });

     /* save instruction*/
     $(document).on('click', 'button[name="save_instruction"]', function (e) 
     {

      e.preventDefault();
      var form = $("#assignment_instructions_form");
      var url = form.attr('action');
      var type = "POST";

      var options = {
        target: '',
        url: url,
        type: type,
        success: function(data) {
          $("#assignment_insturctions").modal('hide');
          oInstructionTable.draw(true);
          DisplayMessages(data['message']);
        },
        error: function (jqXHR, exception) {
          var Response = jqXHR.responseText;
          ErrorBlock = $(form).find('.alert');
          Response = $.parseJSON(Response);
          DisplayErrorMessages(Response, ErrorBlock, 'div');
        }
      }
      $(form).ajaxSubmit(options);
    });

     /*  Delete Instruction */
     $(document).on('click', 'button[name="remove_instruction"]', function(e){

      e.preventDefault();
      var $form=$(this).closest('form');
      var $parent_tr = $(this).closest('tr');
      var instruction_id =  $(this).data('id');
      var formData = {id :instruction_id};
      var url = APP_URL+'/assignment-instruction-delete';
      var type = "POST";

      $('#delete_instruction_modal').modal({ backdrop: 'static', keyboard: false }).one('click', '#delete', function() {
        $.ajax({
          type: type,
          url: url,
          data: formData,
          dataType: 'json',
          success: function (data) {
            $parent_tr.remove();
            DisplayMessages(data['message']);
          },
          error: function (data) {
            DisplayMessages('Please try again');
          }
        });
      });
    });


   }); /* /.document.ready over*/


function SetInstructionEdit(element,e)
{
  
  e.preventDefault();
  var Form = $("#assignment_instructions_form");
  var instruction_id = $(element).attr('data-id');
  var APP_URL = $('meta[name="_base_url"]').attr('content');
  var url = APP_URL + '/assignment-instructions/' + instruction_id + '/edit';
  $.ajax({
    type: "POST",
    url: url,
    data: "id=" + instruction_id,
    dataType: "json",
    success: function (res) {
      $('#assignment_insturctions').modal('show');
      SetFormValues(res.inputs, Form);

      var attachment = res.inputs.attachment.file.src;
      var attachment_config = res.inputs.attachment.file.config;
      var offer_attachment = res.inputs.offer_attachment.file.src;
      var offer_attachment_config = res.inputs.offer_attachment.file.config;

      if(attachment.length != 0){
        $("#attachment").fileinput('clear');
        $("#attachment").fileinput('refresh',
        {           
          'initialPreview': attachment,
          'initialPreviewConfig': attachment_config,
          'uploadUrl': APP_URL,
          'overwriteInitial': false,
          'initialPreviewAsData': true,
          'showUpload': false,
          'showRemove': true,
            // 'previewFileIconSettings': {
            //   'docx': '<i class="fa fa-file-word-o text-primary"></i>',
            // },
            // 'showPreview':true,
          });
      }else {
        $("#attachment").fileinput('clear');
        $("#attachment").fileinput('refresh', fileinput_options);
      }                                       
      if(offer_attachment.length != 0){
        $("#offer_attachment").fileinput('clear');
        $("#offer_attachment").fileinput('refresh',
        {
          'initialPreview': offer_attachment,
          'initialPreviewConfig': offer_attachment_config,
          'uploadUrl': APP_URL,
          'overwriteInitial': false,
          'initialPreviewAsData': true,
          'showUpload': false,
          'showRemove': true,
        });
          //$('.fileinput-remove').addClass('hide');
        }else {
          $("#offer_attachment").fileinput('clear');
          $("#offer_attachment").fileinput('refresh', fileinput_options);
        } 
      }
    });
}

</script>
@append