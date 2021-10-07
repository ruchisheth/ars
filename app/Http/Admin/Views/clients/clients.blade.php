@extends('app')
@section('page-title') | @lang('messages.clients') @stop
@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <i class="fa fa-user"></i>
        <h3 class="box-title">@lang('messages.clients')</h3>
        @include('includes.success')
        @include('includes.errors')

        {{-- @if(ARS::canOrNot('add_client')) --}}
        <div class="box-tools">
          <a href="{{url('/clients-edit')}}" class="btn btn-block btn-box-tools btn-sm"><i class="fa fa-plus"></i></a>
        </div>
        {{-- @endif --}}
      </div><!-- /.box-header -->

      <div class="box-body">
        <form class="form-inline section-filter" id="search-form" method="post">
          <div class="form-group">
            <label for="status">@lang('messages.status')</label>
            {{  Form::select('status', [
              ''  =>  trans('messages.select_status'),
              '1'  =>  trans('messages.active'),
              '0'  =>  trans('messages.inactive'),

            ], '',
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
              <th></th>
              <th class='text-right'>{{ trans('messages.code') }}</th>
              <th>{{ trans('messages.name') }}</th>
              <th>{{ trans('messages.email') }}</th>
              <th>{{ trans('messages.location') }}</th>
              <th>{{ trans('messages.chains') }}</th>
              <th>{{ trans('messages.status') }}</th>
              {{-- @if(ARS::canOrNot('delete_client')) --}}
              <th>&nbsp;</th>
              {{-- @endif --}}
            </tr>
          </thead>
        </table>
      </div>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</section>
@include('includes.confirm-modal',['name'   => 'client'])
</div>
@stop

@section('custom-script')
<script type="text/javascript">
  var oClientTable ='';
  var rows_selected = [];
  $(document).ready(function(){
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });

    oClientTable = $('#cleint-grid').DataTable( {
      serverSide: true,
      order: [ 1, "desc" ],
      ajax: {
        url: 'clients',
        type: 'POST',
        data: function (d) {          
          d.client_id = $('select[name=client_id]').val();
          d.status = $('select[name=status]').val();
        }
      },
      columns: [      
      {data: 'client_logo', name: 'c.client_logo',  width: '7%', className: 'client-td', orderable:false, searchable :false},
      {data: 'id',          name: 'c.id',           width:'7%',  className: 'text-right'},
      {data: 'client_name', name: 'c.client_name'},
      {data: 'email',       name: 'u.email'},
      {data: 'location',    name: 'co.city'},
      {data: 'chains',      name: 'ch.chain_name',  orderable: false},
      {data: 'status',      name: 'c.status',       width: '7%', orderable: false},
      {{-- @if(ARS::canOrNot('delete_client')) --}}
      {data: 'action',      name: 'action',         width: '7%', orderable: false, searchable: false}
      {{-- @endif --}}
      ],
    });

  // custom filter for datatable
  $('#search-form').on('submit', function(e) {
    oClientTable.draw();
    e.preventDefault();
  });

  $('#search-form-reset').on('click', function(e) {  
    $('#search-form')[0].reset();
    oClientTable.draw();
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
          oClientTable.draw();
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