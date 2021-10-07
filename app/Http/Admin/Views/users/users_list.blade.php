@extends('app')
@section('page-title') | @lang('messages.users') @stop
@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <i class="fa fa-user"></i>
        <h3 class="box-title">@lang('messages.users')</h3>
        @include('includes.success')
        @include('includes.errors')
        <div class="box-tools">{{--  box-tools-common pull-right --}}
          <a href="javascript:void(0)" data-toggle="modal" data-target="#roles_modal" class="btn btn-box-tool show-tooltip" title="Roles"><i class="fa fa-graduation-cap"></i></a>
          <a href="javascript:void(0)" data-toggle="modal" data-target="#permissions_modal" class="btn btn-box-tool show-tooltip" title="Permissions" id="btn-permissions"><i class="fa fa-check-square-o"></i></a>
          <a href="#" class="btn btn-box-tool show-tooltip" data-toggle="modal" data-target="#user_modal" title="Add User"><i class="fa fa-plus"></i>
          </a>
        </div>
      </div><!-- /.box-header -->

      <div class="box-body">
        <form class="form-inline section-filter" id="search-form" method="post">
          <div class="form-group">
            <label for="status">@lang('messages.status')</label>
            {{  Form::select('status', [
              ''  =>  'Select Status',
              '1'  => 'Active',
              '0'  => 'Inactive',
            ], '',
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
        <table id="user-grid" class="table table-bordered table-hover" width="100%">
          <thead>
            <tr>
              <th></th>
              <th>@lang('messages.name')</th>
              <th>@lang('messages.email')</th>
              <th>@lang('messages.role')</th>
              <th>@lang('messages.status')</th>
              {{-- <th>&nbsp;</th> --}}
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
  @include('AdminView::users.create')
  @include('AdminView::roles.roles_modal')
  {{-- @include('admin.permission.permissions_modal') --}}
  @include('includes.confirm-modal',['name'   => 'user'])
  {{-- @include('common.modal.delete_confirm_modal',[
    'entity' =>  'user'
    ]) --}}
  </section>
</div>
@stop

@section('custom-script')
<script type="text/javascript">
  $(document).ready(function(e){
    oUserTable = $('#user-grid').DataTable( {
      serverSide: true,
      order: [ 0, "desc" ],
      ajax: {
        url: "{{ route('users.list') }}",
        type: 'POST',
        data: function (d) {
          d.status = $('select[name=status]').val();
        }
      },
      columns: [      
      {data: 'id',      name: 'u.id', width:'7%',  className: 'text-right', visible:false},
      {data: 'name',    name: 'p.name'},
      {data: 'email',   name: 'u.email'},
      {data: 'role_name',    name: 'r.name'},
      {data: 'status',  name: 'u.status', width: '7%', orderable: false},
      ],
    });

    $('#search-form').on('submit', function(e) {
      oUserTable.draw();
      e.preventDefault();
    });

    $('#search-form-reset').on('click', function(e) {  
      $('#search-form')[0].reset();
      oUserTable.draw();
    });
  });

</script>

@append