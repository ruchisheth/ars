@extends('app')
@section('page-title') | Users @stop
@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <i class="fa fa-user"></i>
        <h3 class="box-title">Users</h3>
        @include('includes.success')
        @include('includes.errors')
        <div class="box-tools">
         <a href="{{url('/users-edit')}}" class="btn btn-block btn-box-tools btn-sm"><i class="fa fa-plus"></i></a>
       </div>
     </div><!-- /.box-header -->

     <div class="box-body">
      <form class="form-horizontal" id="search-form" method="post">
        <div class="row">
          <div class="col-md-10">
            <div class="form-group pull-right">
              <label for="inputEmail3" class="col-md-2 control-label">
                Status
              </label>
              <div class="col-md-9 pull-right">

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
          </div>

    </div>
    <div class="col-md-2 pull-right">
     <div class="pull-right">
      <input type="reset" id="search-form-reset" class="btn btn-default pull-right" value="Reset">
    </div>
    <div class="col-md-1 pull-right">
     <input type="submit" id="search" class="btn btn-default pull-right" value="Search">
   </div>
 </div>
</div>
</form>
<div class="box-header with-border custom-header"></div>

<table id="cleint-grid" class="table table-bordered table-hover" width="100%">
  <thead>
    <tr>
      <th>Name</th>
      <th>User Type</th>
      <th>Security Level</th>
      <th>Owner</th>
      <th>Status</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Data1</td>
      <td>Data2</td>
      <td>Data3</td>
      <td>Data4</td>
      <td>Data5</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
</div><!-- /.box-body -->
</div><!-- /.box -->
</div>
</section>
@include('includes.confirm-modal',['name'   => 'user'])
</div>
@stop

@section('custom-script')
<script type="text/javascript">
  var oTable ='';
  $(document).ready(function(){

    oUserTable = $('#user-grid').DataTable( {
      "serverSide": true,
      "order": [ 1, "desc" ],
      ajax: {
        url: 'users',
        type: 'POST',
        data: function (d) {
          // d.client_id = $('select[name=client_id]').val();
          // d.status = $('select[name=status]').val();
        }
      },
      columns: [
      {data: 'name', name: 'user_name', sWidth:'7%'},
      {data: 'user_type', name: 'user_type'},
      {data: 'role', name: 'role'},
      {data: 'owner', name: 'owner', orderable: false},
      {data: 'status', name: 'status', orderable: false},
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

});/* .ready overe*/

</script>

@stop