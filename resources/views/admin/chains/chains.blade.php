@extends('app')
@section('page-title') | Chains @stop
@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-primary">
        <div class="box-header with-border">
          <i class="fa fa-cube"></i>
          <h3 class="box-title">Chains</h3>
          @include('includes.success')
          @include('includes.errors')
          <div class="box-tools">
            <a href="{{url('/chains-edit')}}" class="btn btn-block btn-box-tools btn-sm"><i class="fa fa-plus"></i></a>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form class="form-inline section-filter" id="search-form" method="post">
            <div class="form-group">
              <label for="client" class="">Client</label>
              {{  Form::select(
               'client_id', @$client_list,
               @$client_list,
               [
               'id' => 'client_id',
               'class'=>'form-control',
               'data-placeholder'=>'Select Client'
               ]) 
             }}
           </div> 
           <div class="form-group">
            <label for="status" class="">Status</label>
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
      <table id="chains-grid" class="table table-bordered table-hover" width="100%">
        <thead>
          <tr>
            <th>&nbsp;</th>{{-- <th>Client</th> --}}
            <th class='text-right'>Code</th>
            <th>Name</th>
            <th>Client</th>
            <th>Location</th>
            <th class="text-right">#Sites</th>
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

@include('includes.confirm-modal',['name'   => 'chain'])

</div>

@stop

@section('custom-script')

<script type="text/javascript">

  var oTable ='';
  $(document).ready(function(){
    var client_id = getUrlVars()['client_id'] != '' ? getUrlVars()['client_id'] : '';
    $('select[name=client_id]').val(client_id);

    oTable = $('#chains-grid').DataTable( {
      "serverSide": true,
      "order": [ 1, "desc" ],
      ajax: {
        url: '{{ route("show.chains.post") }}',
        type: 'POST',
        data: function (d) {
          d.client_id = $('select[name=client_id]').val();
          d.status = $('select[name=status]').val();

        }
      },
      columns: [
      {data: 'client_logo', name: 'c.client_logo', orderable:false, searchable :false, className: 'client-td'},
      {data: 'id', name: 'ch.id', className: 'text-right'},
      {data: 'chain_name', name: 'ch.chain_name'},
      {data: 'client_name', name: 'c.client_name'},
      {data: 'location', name: 'co.city'},
      {data: 'site_count', name: 'site_count', className: 'text-right', orderable: false, searchable: false},
      {data: 'status', name: 'ch.status', orderable: false},
      {data: 'action', name: 'action', orderable: false, searchable: false}
      ],
      "aoColumnDefs": [
      { "sWidth": "7%", "targets": [0,1,5,6,7] },            
      ],
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

  $(document).on('click', 'button[name="remove_chain"]', function(e){
    e.preventDefault();
    var $form=$(this).closest('form');
    var chain_id =  $(this).data('id');
    var formData = {id :chain_id};
    var url =  APP_URL+'/chains-delete';
    var type = "POST";    
    $('#confirm').modal({keyboard: false });
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