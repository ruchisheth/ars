<div class="row"><!-- assignment generator -->
  <!-- modal -->
  <div class="modal fade" id="apply_to_assignment_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">
            Assignments
          </h4>
        </div>
        <div class="modal-body">
          {{  Form::open([
          'method'=>'post', 
          'url' => route('apply.instruction'),
          'id' => 'apply_instruction']) 
          
        }}

        {{  Form::hidden('instruction_id')  }}

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
                  {{  Form::select('available_sites[]', [],'',
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
              <button type="button" class="btn btn-primary" id="apply_instruction" name="apply_instruction">Apply</button>
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

    $(".select2").select2();

    var dualListbox = $('select[name="available_sites[]"]').bootstrapDualListbox({
     nonSelectedListLabel: 'Available Assignment',
     selectedListLabel: 'Selected Assignment',
     preserveSelectionOnMove : false,
     moveOnSelect: false,
   });

    $('#apply_to_assignment_modal').on('show.bs.modal', function (event) {
        var instruction_id = $(event.relatedTarget).data('id');
        $(event.currentTarget).find('input[name="instruction_id"]').val(instruction_id);
    });


   $(document).on('click', '.apply_to_assignment_link', function(e){

      e.preventDefault();
      $('select[name="available_sites[]"]').empty();

      var round_id =  $('body').find('input[name="round_id"]').val();
      var instruction_id =  $(this).data('id');
      var type = "POST";
      var url = APP_URL+'/get-assignments';
      var formData = {round_id :round_id,'instruction_id': instruction_id};
      var dataType = 'json';

      $.ajax({
       type: type,
       url: url,
       data: formData,
       dataType: dataType,
       success: function (data) {
        $.each(data.available_sites, function(key, value) {
          $('select[name="available_sites[]"]')
          .append($("<option></option>")
            .attr("value",key)
            .text('Assignment'+key+"-"+value));
        });
        $.each(data.selected_sites, function(key, value) {
          $('select[name="available_sites[]"]')
          .append($("<option selected='selected'></option>")
            .attr("value",key)
            .text('Assignment'+key+"-"+value));
        });
        $('select[name="available_sites[]"]').bootstrapDualListbox('refresh',true);
      }
    });
    });

    $(document).on('click', 'button[name="apply_instruction"]', function (e) {

      e.preventDefault();
      $('select[name="available_sites[]"]').bootstrapDualListbox('refresh',true);
      //$('select[name="available_sites[]_helper1"]').val([]);
      $('select[name="available_sites[]_helper1"]').val([]);

      var form = $("#apply_instruction");
      var type = "POST";
      var url = form.attr('action');
      var formData = form.serialize();
      var dataType = 'json';
      var selected_assignment = $('[name="available_sites[]"]').val();

      // if(selected_assignment == null){
      //   ErrorBlock = $(form).find('.alert');
      //   DisplayErrorMessages(['You must select assignment!'], ErrorBlock, 'div');
      //   return;
      // }

      $.ajax({
        type: type,
        url: url,
        data: formData,
        dataType: 'json',
        success: function (data) {
          $("#apply_to_assignment_modal").modal('hide');
          oInstructionTable.draw(true);
          if(data['message'] != ''){
            DisplayMessages(data['message']);  
          }
          
        },
        error: function (jqXHR, exception) {
          var Response = jqXHR.responseText;
          ErrorBlock = $(form).find('.alert');
          Response = $.parseJSON(Response);
          DisplayErrorMessages(Response, ErrorBlock, 'div');
        }
      });
    });/* generate_assignments*/

  }); /* /.document.ready over*/

</script>
@append