@extends('app')
@section('page-title') | Clients @stop
@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <i class="fa fa-user"></i>
        <h3 class="box-title">Clients</h3>
        @include('includes.success')
        @include('includes.errors')
        <div class="box-tools">
         <a href="{{url('/clients-edit')}}" class="btn btn-block btn-box-tools btn-sm"><i class="fa fa-plus"></i></a>
       </div>
     </div><!-- /.box-header -->

     <div class="box-body">
      <form class="form-inline section-filter" id="search-form" method="post">
        <div class="form-group">
          <label for="status">Status</label>
          {{  Form::select('status', array(
            ''  =>  'Select Status',
            '1'  => 'Active',
            '0'  => 'Inactive',

            ), '',
          [
          'id' => 'status',
          'class' => 'form-control ',
          ])
        }}
      </div>
      <div class="action-btns">
        <input type="submit" id="search" class="btn btn-default" value="Search">
        <input type="reset" id="search-form-reset" class="btn btn-default" value="Reset">
      </div>

    </form>
    <div class="box-header with-border custom-header"></div>
    <div class="table-responsive">
      <table id="cleint-grid" class="table table-bordered table-hover" width="100%">
        <thead>
          <tr>
            <th>Logo</th>
            <th class='text-right'>Code</th>
            <th>Name</th>
            <th>Location</th>
            <th>Chains</th>
            <th>Status</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
      </table>
    </div>
  </div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</section>
@include('includes.confirm-modal',['name'   => 'client'])
</div>
@stop

@section('custom-script')
<script type="text/javascript">
  var oTable ='';
  $(document).ready(function(){
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });

    oTable = $('#cleint-grid').DataTable( {
      "serverSide": true,
      "order": [ 1, "desc" ],
      ajax: {
        url: 'clients',
        type: 'POST',
        data: function (d) {
          d.client_id = $('select[name=client_id]').val();
          d.status = $('select[name=status]').val();
        }
      },
      columns: [
      {data: 'client_logo', name: 'c.client_logo', orderable:false,searchable :false},
      {data: 'id', name: 'c.id', sWidth:'7%', className: 'text-right'},
      {data: 'client_name', name: 'c.client_name'},
      {data: 'location', name: 'co.city'},
      {data: 'chains', name: 'ch.chain_name', orderable: false},
      {data: 'status', name: 'c.status', orderable: false},
      {data: 'action', name: 'action', orderable: false, searchable: false}
      ],
      "aoColumnDefs": [
      { "sWidth": "7%", "targets": [0,1,5,6] },
      { className: "client-td", "targets": [0] }

      ],
    });

  // custom filter for datatable
  $('#search-form').on('submit', function(e) {
    oTable.draw();
    e.preventDefault();
  });

  $('#search-form-reset').on('click', function(e) {  
    $('#search-form')[0].reset();
    oTable.draw();
  });

  $(document).on('click', 'button[name="remove_client"]', function(e){
   e.preventDefault();
   var $form=$(this).closest('form');
   var $parent_tr = $(this).closest('tr');
   var client_id =  $(this).data('id');
   var formData = {id :client_id};
   var url = APP_URL+'/clients-delete';
   var type = "POST";
    //deleteRecord('#confirm', type, url, formData,$parent_tr);
    $('#confirm').modal('show');
    $('#confirm').find('#delete').bind('click', function() {      
      $.ajax({
        type: type,
        url: url,
        data: formData,
        dataType: 'json',
        success: function (data) {
          oTable.draw();
          DisplayMessages(data['message']);
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