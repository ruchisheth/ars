@extends('app')
@section('page-title') | Sites @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-primary">
        <div class="box-header with-border">
          <!-- <h6 class="box-title"></h6> -->
          <i class="fa fa-cubes"></i>
          <h3 class="box-title">Sites</h3>{{@$site1}}
          @include('includes.success')
          @include('includes.errors')
          <div class="box-tools pull-right">
            <a href="{{url('/sites-edit')}}" class="btn btn-block btn-box-tools btn-sm showtooltip" ><i class="fa fa-plus"></i></a>
          </div>
        </div><!-- /.box-header -->

        <div class="box-body">
         <form class="form-inline section-filter" id="search-form" method="post">
          <div class="form-group">
            <label for="chain" class=" control-label">Chain</label>
            {{  Form::select(
              'chain_id',
              @$chain_list,
              @$chain_filter,
              [
              'id' => 'chain_id',
              'class'=>'form-control',
              'data-placeholder'=>'Select Chain'
              ])
            }}
          </div>
          <div class="form-group">
            <label for="state" class="control-label">State</label>
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
            <label for="inputEmail3" class="control-label">City</label>
            {{  Form::select(
              'city', 
              @$city,'',
              [
              'id' => 'city',
              'class'=>'form-control',
              'data-placeholder'=>'Select City'                  
              ]) 
            }}
          </div>
          <div class="form-group">
           <label for="status">Status</label>
           {{  Form::select('status', array(
            ''  =>  'Select Status',
            '1'  => 'Open',
            '0'  => 'Close',                                
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
      <table id="site-grid" class="table table-bordered table-hover" width="100%">
        <thead>
          <tr>
            <th>Client</th>
            <th class='text-right'>Site Code</th>
            <th>Name</th>
            <th>Chain</th>
            <th>Location</th>
            <th>FieldRep</th>
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

@include('includes.confirm-modal',['name'   => 'site'])

</div>
@stop

@section('custom-script')

<script type="text/javascript">
  $("#city").val($("#city option:first").val());
  var oTable ='';

  $(document).ready(function(){

    oTable = $('#site-grid').DataTable( {
      "serverSide": true,
      "order": [ 1, "asc" ],
      ajax: {
        url: '{{ route("show.sites.post") }}',
        type: 'POST',
        data: function (d) {
          d.chain_id = $('select[name=chain_id]').val();
          d.state = $('select[name=state]').val();
          d.city = $('select[name=city]').val();
          d.status = $('select[name=status]').val();
        }
      },
      columns: [
      {data: 'client_logo', name: 'c.client_logo', orderable:false, searchable :false},
      {data: 'site_code',   name: 's.site_code', sWidth: '10%', className: 'text-right'},
      {data: 'site_name',   name: 's.site_name'},
      {data: 'chain_name',  name: 'ch.chain_name'},
      {data: 'city',        name: 's.city'},
      {data: 'full_name',   name: 'full_name'},
      {data: 'status',      name: 's.status', sWidth: '7%', orderable: false},
      {data: 'action',      name: 'action', orderable: false, searchable: false, sWidth: '7%'}
      ],
      "aoColumnDefs": [          
      { className: "client-td", "targets": [0] }
      ],
    });
    /* custom filter for datatable */
    $('#search-form').on('submit', function(e) {
      oTable.draw();
      e.preventDefault();
    });
    $('#search-form-reset').on('click', function(e) {  
      $('#search-form')[0].reset();
      $('select option:first-child').attr("selected", "selected");
      $('#target option:first').prop('selected', false);
      $('select').val('')
      oTable.draw();
    });

  });
  $(document).on('click', 'button[name="remove_site"]', function(e){
    e.preventDefault();
    site_id = "";
    formData = {};
    var $form=$(this).closest('form');
    var site_id =  $(this).data('id');
    var formData = {id :site_id};
    var url = APP_URL+'/sites-delete';
    var type = "POST";
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

</script>

@stop