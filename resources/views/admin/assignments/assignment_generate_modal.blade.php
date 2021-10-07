<div class="row"><!-- assignment generator -->
  <!-- modal -->
  <div class="modal fade" id="assignments">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">
            Create / Edit Assignments
          </h4>
        </div>
        <div class="modal-body">
          {{  Form::open([
            'method'=>'post',
            'url' => route('generate.assignments'), 
            'id' => 'generate_assignments']) 
          
          }}

          {{  Form::hidden('round_id',@$round->id)  }}

          <!-- 'id' => 'assignments_save'])  -->
          <div class="box">
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="alert" style="display: none"></div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::select('available_store[]', @$sites,'',
                      [
                      'id' => 'available_store',
                      'class' => 'form-control',
                      'size' =>   '30',
                      'multiple' => 'true'
                      ])
                    }}
                  </div>
                </div>
              </div>
            </div>
            <div class="box-footer">

              <div class="pull-right">
                <div class="pull-right">
                  <button type="button" class="btn btn-primary" id="create_assignments" name="create_assignments">Create</button>
                </div>
                <div class="col-md-1 pull-right">
                  <button type="button" data-dismiss="modal" id="cancel" class="btn btn-default pull-right">Cancel</button>
                </div>
              </div>                                                                                                    
            </div><!-- /.box-footer -->
          </div><!-- /.box -->

          {{ Form::close() }}
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</div><!-- /row -->

@section('custom-script')

<!-- Dual List Box -->
{{ Html::script(AppHelper::ASSETS.'plugins/dual-listbox/jquery.bootstrap-duallistbox.min.js') }}

<script type="text/javascript">
  $(document).ready(function () {
    var dualListbox = $('select[name="available_store[]"]').bootstrapDualListbox({
     nonSelectedListLabel: 'Available Site',
     selectedListLabel: 'Selected Site',
     preserveSelectionOnMove : 'moved',
     moveOnSelect: false,
   });

    $(document).on('click', 'button[name="create_assignments"]', function (e) {
      e.preventDefault();
      $('select[name="available_store[]"]').bootstrapDualListbox('refresh',true);
      $('select[name="available_store[]_helper1"]').val([]);
      var form = $("#generate_assignments");
      var formData = $("#generate_assignments").serialize();
      var url = $('#generate_assignments').attr('action');
      var type = "POST";
      var selected_store = $('[name="available_store[]"]').val();
      
      if(selected_store == null){
        ErrorBlock = $(form).find('.alert');
        DisplayErrorMessages(['Select Atleast One Store!'], ErrorBlock, 'div');
        return;
      }

      $.ajax({
        type: "POST",
        url: url,
        data: formData,
        dataType: 'json',
        beforeSend: function( xhr ) {
          $('#overlay').removeClass('hide');
        },
        complete: function( xhr ){
          $('#overlay').addClass('hide');
        },
        success: function (data) {
          $("#assignments").modal('hide');
          oAssignmentTable.draw(true);
          oInstructionTable.draw(true);

          /* remove already selected sites and add reamain to list box*/
          $('select[name="available_store[]"]').empty();
          $.each(data.sites, function(key, value) {
            $('select[name="available_store[]"]')
            .append($("<option></option>")
              .attr("value",key)
              .text(value));
          });
          $('select[name="available_store[]"]').bootstrapDualListbox('refresh',true);

          $('select[name="template_id"]').attr('disabled', true); //if round has assignment user can not change survey template.
          DisplayMessages(data['message']);

        },
        error: function (jqXHR, exception) {
          var Response = jqXHR.responseText;
          ErrorBlock = $(form).find('.alert');
          Response = $.parseJSON(Response);
          DisplayErrorMessages(Response, ErrorBlock, 'div');
        }
      });
    });/* generate_assignments*/

    $('#assignments').on('hidden.bs.modal', function () {
      $('.alert').hide();
      var form = $("#generate_assignments");
      form[0].reset();
      $('.removeall').trigger('click');
    });

    $('#assignments').on('shown.bs.modal', function (e) {
      $('select[name="available_store[]"]').bootstrapDualListbox('refresh',true);
    });


  });/* document-ready over */
</script>

@append