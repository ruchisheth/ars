@extends('app')
@section('page-title') | Fieldrep Organizations @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-primary">
        <div class="box-header with-border">
          <i class="fa fa-institution"></i>
          <h3 class="box-title">FieldRep Orgs</h3>
          @include('includes.success')
          @include('includes.errors')
          <div class="box-tools">
            <a href="{{url('/fieldreporgs-edit')}}" class="btn btn-block btn-box-tools btn-sm"><i class="fa fa-plus"></i></a>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
         <form class="form-inline section-filter" id="search-form" method="post">
           <div class="form-group">
            <label for="state" class=" control-label">State</label>
            {{  Form::select(
              'state', 
              @$states,
              (@$states) ? @$state : '' ,
              [
              'id' => 'state',
              'class'=>'form-control',
              'data-placeholder'=>'Select State'

              ]) 
            }}
          </div>
          <div class="form-group">
           <label for="status">Status</label>
           {{  Form::select('status', array(
            ''  =>  'Select Status',
            '1'  => 'Active',
            '0'  => 'Inactive',

            ), '',
           [
           'id' => 'status',
           'class' => 'form-control',
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
       <table id="fieldreporgs-grid" class="table table-bordered table-hover" width="100%">
        <thead>
          <tr>
            <th class='text-right'>Code</th>
            <th>Name</th>
            <th class='text-right'>#Fieldreps</th>
            <th>Location</th>
            <th>Status</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div><!-- /.box-body -->
</div><!-- /.box -->
</section>

@include('includes.confirm-modal',['name'   => 'fieldrep org'])

</div>
@stop

@section('custom-script')

<script type="text/javascript">

  var oTable ='';
  $(document).ready(function(){
    oTable = $('#fieldreporgs-grid').DataTable( {
      "serverSide": true,
      "order": [ 0, "desc" ],
      ajax: {
        url: '{{ route("show.fieldreporgs.post") }}',
        type: 'POST',
        data: function (d) {
          d.status = $('select[name=status]').val();
          d.state = $('select[name=state]').val();
        }
      },
      columns: [
      {data: 'id', name: 'f_org.id', 'width': '7%', className: 'text-right'},
      {data: 'fieldrep_org_name', name: 'f_org.fieldrep_org_name'},
      {data: 'rep_count', name: 'rep_count', searchable:false, className: 'text-right'},
      {data: 'location', name: 'co.city'},     
      {data: 'status', name: 'f_org.status', 'width': '7%', orderable: false},
      {data: 'action', name: 'action', 'width': '7%', orderable: false, searchable:false}
      ]
    });
    /* custom filter for datatable */
    $('#search-form').on('submit', function(e) {
      oTable.draw();
      e.preventDefault();
    });
    $('#search-form-reset').on('click', function(e) {  
      $('#search-form')[0].reset();
      oTable.draw();
    });

  });
</script>

@stop