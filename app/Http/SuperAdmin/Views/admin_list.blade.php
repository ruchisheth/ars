@extends('layouts.superadmin.app')
@section('page-title') | {{ trans('messages.admins') }} @stop
@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <i class="fa fa-user"></i>
        <h3 class="box-title">{{ trans('messages.admins') }}</h3>
        @include('includes.success')
        @include('includes.errors')
        <div class="box-tools pull-right">
          <a href="{{route('create.admin')}}" class="btn btn-block btn-box-tools btn-sm"><i class="fa fa-plus"></i></a>
        </div>
      </div>

      <div class="box-body">                           
        <div class="box-header with-border custom-header"></div>
        <div class="table-responsive">
          <table id="clients-grid" class="table table-bordered table-hover" width="100%">
            <thead>
              <tr>
                <th>&nbsp;</th>
                <th>@lang('messages.code')</th>
                <th>@lang('messages.client_code')</th>
                <th>@lang('messages.name')</th>
                <th>@lang('messages.database')</th>
                <th>@lang('messages.email')</th>
                <th>@lang('messages.subscription_start_on')</th>
                <th>@lang('messages.subscription_end_on')</th>
                <th>@lang('messages.status')</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
              </tr>
            </thead>                        
          </table>
        </div>
      </div>
    </div>
  </section>

  <div class="row">
    <div class="modal fade" id="confirm_send_email">
     <div class="modal-dialog" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
           <h4 class="modal-title">Send SYI Invitation</h4>
         </div>
         <div class="modal-body">
           <p>This will send invitation email to User. Are you sure to send invitation?</p>
         </div>
         <div class="modal-footer">
           <button type="button" data-dismiss="modal" class="btn btn-primary" id="send">Yes</button>
           <button type="button" data-dismiss="modal" class="btn">Cancel</button>
         </div>
       </div>
     </div>
   </div>
 </div>
</div>


<div class="modal fade" id="confirm_active_subscription_modal">
 <div class="modal-dialog" role="document">
   <div class="modal-content">
     <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span>
       </button>
       <h4 class="modal-title">@lang('messages.active_subscription')</h4>
     </div>
     <div class="modal-body">
       <p>@lang('messages.confirm_subscription_active')</p>
     </div>
     <div class="modal-footer">
       <button type="button" data-dismiss="modal" class="btn btn-primary" id="yes"> @lang('messages.yes') </button>
       <button type="button" data-dismiss="modal" class="btn">@lang('messages.cancel')</button>
     </div>
   </div><!-- /.modal-content -->
 </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="confirm_in_active_subscription_modal">
 <div class="modal-dialog" role="document">
   <div class="modal-content">
     <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span>
       </button>
       <h4 class="modal-title">@lang('messages.inactive_subscription')</h4>
     </div>
     <div class="modal-body">
       <p>@lang('messages.confirm_subscription_inactive')</p>
     </div>
     <div class="modal-footer">
       <button type="button" data-dismiss="modal" class="btn btn-primary" id="yes"> @lang('messages.yes') </button>
       <button type="button" data-dismiss="modal" class="btn">@lang('messages.cancel')</button>
     </div>
   </div><!-- /.modal-content -->
 </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script id="details-template" type="text/x-handlebars-template">
  <div class="label label-primary">Admin Detail</div>
  <div class="table-responsive">
    <table class="table table-bordered table-hover details-table" id="admin_detail_@{{id}}">
      <thead>
        <tr>
          <th>#Client</th>
          <th>#Site</th>
          <th>#Fieldrep</th>
          <th>#Pending Assignments</th>
          <th>#Offered Assignments</th>
          <th>#Scheduled Assignments (Surveys)</th>
          <th>#Late Assignments</th>
          <th>#Reported Assignments</th>
          <th>#Not Approved Assignments</th>
          <th>#Completed Assignments</th>
        </tr>
      </thead>
    </table>
  </div>
</script>

@stop

@section('custom-script')

{{ Html::script(AppHelper::ASSETS.'dist/js/handlebars.js') }}
<script type="text/javascript">

  <!--  Handlebars  -->

  var oAdminTable ='';
  $(document).ready(function(){
    $('.modal').on('hidden.bs.modal',function(){    
      $('.modal').find("#yes").unbind( "click" );
      $('.alert-danger').hide();
    })
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });

    oAdminTable = $('#clients-grid').DataTable( {
      "serverSide": true,
      "order": [ 1, "desc" ],
      ajax: {
        url: '{{ route('admin_list') }}',
        type: 'POST',
      },
      columns: [
        // {data: 'logo',        name: 'a.logo',orderable:false,searchable :false,className: "client-td"},
        {data: 'expand',      name: 'expand', sWidth: '5%', className:'details-control text-center', orderable: false, searchable:false},
        {data: 'id_admin',    name: 'a.id_admin'},
        {data: 'client_code', name: 'client_code'},
        {data: 'name',        name: 'name'},
        {data: 'database',    name: 'db_version', sWidth: '5%', searchable: false},
        {data: 'email',       name: 'email'},
        {data: 'subscription_start_on',     name: 'subscription_start_on'},
        {data: 'subscription_end_on',       name: 'subscription_end_on'},
        {data: 'status',       name: 'status'},
        {data: 'invite',      name: 'invite', orderable:false,searchable :false},
        {data: 'action',      name: 'action', orderable:false,searchable :false},
        {data: 'id',          name: 'a.id', visible: false},
        ],  
      });

    $('#confirm_send_email').on('hidden.bs.modal',function(){
      $('.modal').find( "#send" ).unbind( "click" );
      $('.alert-danger').hide();
    })

    $(document).on('change', '.change_activation', function(e){
      var bIsChecked = $(this).is(':checked');
      var nIdAdmin = $(this).data('id_admin');
      if(bIsChecked){
        var sIdModal = '#confirm_active_subscription_modal';
        var sURL  = "{{ route('superadmin.active-subscription') }}";
        var bActiveSubScription = true;
      }else{
        var sIdModal = '#confirm_in_active_subscription_modal';
        var sURL  = "{{ route('superadmin.inactive-subscription') }}";
        var bActiveSubScription = false;
      }
      $(sIdModal).modal('show');
      $(sIdModal).find('#yes').bind('click', function() {      
        $.ajax({
          type: 'POST',
          url: sURL,
          data: {id_admin: nIdAdmin},
          dataType: 'json',
          success: function (oResponse) {
            oAdminTable.draw();
            DisplayMessages(oResponse.message);
          },
          error: function (jqXHR, exception) {
          }
        });
      });
    });

    $(document).on('click', 'button[name="send_invite"]', function(e){

      e.preventDefault();
      var id = $(this).data('id');
      var formData = {user_id :id};
      var url = APP_URL+'/email/send-invitation/payer';
      var type = "POST";
      $('#confirm_send_email').modal({ backdrop: 'static', keyboard: false });
      $('#confirm_send_email').find('#send').bind('click', function() {
        $.ajax({
          type: type,
          url: url,
          data: formData,
          dataType: 'json',
          success: function (res) {
            oAdminTable.draw();
            DisplayMessages(res.message,'success');
          },
          error: function (jqXHR, exception) {
            var Response = jqXHR.responseText;
            Response = $.parseJSON(Response);
            DisplayMessages(Response.message,'error');
          }
        });
      });
    });

    var template = Handlebars.compile($("#details-template").html());

    $('#clients-grid tbody').on('click', 'td.details-control', function () 
    {
      var tr = $(this).closest('tr');

      var row = oAdminTable.row(tr);        
      var tableId = 'admin_detail_'+row.data().id;

      if (row.child.isShown()) {
          // This row is already open - close it
          row.child.hide();
          tr.removeClass('shown');
        } else {
          // Open this row
          row.child(template(row.data())).show();
          initTable(tableId, row.data());
          tr.addClass('shown');
          tr.next().addClass('child_datatable');
        }
      }); /* onClick details rounds */

  });/* .ready overe*/


function initTable(tableId, data) {
  console.log(data.details_url);
      // $('#admin_detail_1').DataTable({
        $('#' + tableId).DataTable({
          serverSide: true,
          "paging": false,
          "bFilter": false,
          "bInfo": false,
          "ordering": false,
          ajax: {
            url: data.details_url,
            type: 'POST',
          },
          columns: [
          { data: 'client',                 name: 'client'},
          { data: 'site',                   name: 'site'},
          { data: 'fieldrep',               name: 'fieldrep'},
          { data: 'pending_assignment',     name: 'pending_assignment'},
          { data: 'offered_assignment',     name: 'offered_assignment'},
          { data: 'scheduled_assignment',   name: 'scheduled_assignment'},
          { data: 'late_assignment',        name: 'scheduled_assignment'},
          { data: 'reported_assignment',    name: 'scheduled_assignment'},
          { data: 'notapproved_assignment', name: 'scheduled_assignment'},
          { data: 'completed_assignment',   name: 'scheduled_assignment'},
          ],
        });
      }


    </script>


    @stop