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
          <a href="{{route('create.clients')}}" class="btn btn-block btn-box-tools btn-sm"><i class="fa fa-plus"></i></a>
        </div>
      </div>

      <div class="box-body">                           
        <div class="box-header with-border custom-header"></div>
        <div class="table-responsive">
          <table id="clients-grid" class="table table-bordered table-hover" width="100%">
            <thead>
              <tr>
                <th>&nbsp;</th>
                <th>Code</th>
                <th>Client Code</th>
                <th>Name</th>
                <th>Database</th>
                <th>Email</th>
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
@stop

@section('custom-script')

<script type="text/javascript">

  var clientTable ='';
  $(document).ready(function(){
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });

    clientTable = $('#clients-grid').DataTable( {
      "serverSide": true,
      "order": [ 1, "desc" ],
      ajax: {
        url: '{{ route('show_admins') }}',
        type: 'POST',
      },
      columns: [
      {data: 'logo',        name: 'a.logo',orderable:false,searchable :false,className: "client-td"},
      {data: 'id_admin',    name: 'a.id_admin', sWidth: '7%'},
      {data: 'client_code', name: 'client_code'},
      {data: 'name',        name: 'name'},
      {data: 'database',    name: 'db_version', searchable: false},
      {data: 'email',       name: 'email'},
      {data: 'invite',      name: 'invite',orderable:false,searchable :false,},
      ],
      "aoColumnDefs": [
      { "sWidth": "7%", "targets": [0,1,6] },
      { className: "client-td", "targets": [0] }
      ],
    });

    $('#confirm_send_email').on('hidden.bs.modal',function(){
      $('.modal').find( "#send" ).unbind( "click" );
      $('.alert-danger').hide();
    })

    $(document).on('click', 'button[name="send_invite"]', function(e){

      e.preventDefault();
      console.log($(this).data('id'));
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
            clientTable.draw();
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
  });/* .ready overe*/ 
</script>

@stop

