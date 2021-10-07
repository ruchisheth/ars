@extends('fieldrep.app')

@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-primary">
        <div class="box-header with-border">
          <i class="fa fa-check-square-o"></i>
          <h3 class="box-title">Assignments History </h3>
          @include('includes.success')
          @include('includes.errors')
        </div><!-- /.box-header -->

        <div class="box-body">
        <div class="table-responsive">
         <table id="assignments-grid" class="table table-bordered table-hover" width="100%">
          <thead>
            <tr>
             <th></th>
             <th>Code</th>                     
             <th>Project Name</th>
             <th>Location</th>                       
             <th>Start Date/Time</th>                                              
             <th>Status</th>
           </tr>
         </thead>
       </table>
       </div>

     </div><!-- /.box-body -->

   </div><!-- /.box -->
 </div>
</section>

</div>
@stop

@section('custom-script')

<script type="text/javascript">

  var oAssignmentTable ='';
  $(document).ready(function(){

    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });

    oAssignmentTable = $('#assignments-grid').DataTable( {
      "serverSide": true,
      "order": [ 1, "desc" ],
      ajax: {
        url: '{!! route("fieldrep.show.assignments-history.post") !!}',
        type: 'POST',
        data: function (d) {
          d.status = [2];
        },
      },
      columns: [
      {data: 'client_logo', name: 'c.client_logo', orderable:false,searchable :false},
      {data: 'id', name: 'a.id'},           
      {data: 'project_name', name: 'p.project_name'},
      {data: 'city', name: 's.city'},
      {data: 'round_starts', name: 'a.assignment_starts', orderable: false, searchable: false},
      {data: 'status', name: 'a.status', orderable: false},
      ],
      "aoColumnDefs": [
      { "sWidth": "7%", "targets": [0] },
      {className: "client-td", "targets": [0] }
      ],
    });

  });/* .ready over*/

</script>

@append