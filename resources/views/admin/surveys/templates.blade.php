@extends('app')
@section('page-title') | Survey Templates @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <i class="fa fa-newspaper-o"></i>
        <h3 class="box-title">Survey Templates</h3>
        <div class="alert" style="display: none"></div>
        <div class="box-tools">
          <a href="{{url('/survey-template-edit')}}" class="btn btn-block btn-box-tools btn-sm"><i class="fa fa-plus"></i></a>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <table id="survey-grid" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th class='text-right'>Code</th>
                <th>Name</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
          </table>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </section>
  @include('includes.confirm-modal',['name'   => ' Survey Template'])
</div>
@stop

@section('custom-script')

<script type="text/javascript">

  var oSurveyTable ='';
  $(document).ready(function(){

    oSurveyTable = $('#survey-grid').DataTable( {
      "serverSide": true,
      "order": [ 0, "desc" ],
      ajax: {
        url: APP_URL+'/templates',
        type: 'POST',
            // data: function (d) {
            //   d.client_id = $('select[name=client_id]').val();
            //   d.status = $('select[name=status]').val();

            // }
          },
          columns: [
          {data: 'id', name: 'id', className: 'text-right'},
          {data: 'template_name', name: 'template_name'},
          {data: 'action', name: 'action', orderable: false, searchable: false}
          ],
          "aoColumnDefs": [
          {'bSortable': false, 'aTargets': [2]},
          { "sWidth": "7%", "targets": [0,2] },

          ],
        });

    $(document).on('click', 'button[name="remove_template"]', function(e){

      e.preventDefault();

    //var $form=$(this).closest('form');
    var $parent_tr = $(this).closest('tr');
    var template_id =  $(this).data('id');
    var formData = {id :template_id};
    var url = APP_URL+'/templates-delete';
    var type = "POST";
    $('#confirm').modal({ backdrop: 'static', keyboard: false })
    .one('click', '#delete', function() {
      $.ajax({
        type: type,
        url: url,
        data: formData,
        dataType: 'json',
        success: function (data) {
          $parent_tr.remove();
          oSurveyTable.draw();
          DisplayMessages(data.message);
        },
        error: function (jqXHR, exception) {
          var Response = jqXHR.responseText;          
          Response = $.parseJSON(Response);
          DisplayMessages(Response.message,'error');
    }
  });
    });
  });

  });/* .ready overe*/

</script>

@stop

