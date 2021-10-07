@extends('app')
@section('page-title') | FieldReps @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-primary">
        <div class="box-header with-border">
          <i class="fa fa-group"></i>
          <h3 class="box-title">Field Reps</h3>
          @include('includes.success')
          @include('includes.errors')
          <div class="box-tools">
            <a href="{{url('/fieldreps-edit')}}" class="btn btn-block btn-box-tools btn-sm"><i class="fa fa-plus"></i></a>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form class="form-inline section-filter" id="search-form" method="post">
            <div class="form-group">
              <label for="classification">Classification</label>
              {{  Form::select(
                'classification', 
                [
                '' => 'Select Classification',
                1  => 'Independent Contractor',
                2  => 'Employee'
                ],
                (@$states) ? @$state : '' ,
                [
                'id' => 'state',
                'class'=>'form-control',
                'data-placeholder'=>'Select State'                  
                ]) 
              }}
            </div>
            <div class="form-group">
              <label for="state">State</label>
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
                ''          =>  'Select Status',
                'pending'   =>  'Pending',
                'approved'  =>  'Approved',
                'rejected'  =>  'Rejected',
                '1'         =>  'Active',
                '0'         =>  'Inactive',
                '2'         =>  'Hold',
                '3'         =>  'Terminated',

                ), '',
              [
              'id' => 'status',
              'class' => 'form-control ',
              ])
            }}
          </div>
          <div class="action-btns">
            <input type="submit"  id="search" class="btn btn-default" value="Search">
            <input type="reset"   id="search-form-reset" class="btn btn-default" value="Reset">
          </div>
        </form>
        <div class="box-header with-border custom-header"></div>
        <div class="table-responsive">
         <table id="fieldrep-grid" class="table table-bordered table-hover" width="100%">
          <thead>
            <tr>
              <th></th>
              <th>Code</th>
              <th>Name</th>
              <th>Location</th>
              <th>Email</th>
              <th>Approved</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
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
     </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
 </div><!-- /.modal -->
</div><!-- /row -->

<div class="row">
  <div class="modal fade" id="confirm_approve">
   <div class="modal-dialog" role="document">
     <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
         <h4 class="modal-title">Approve Application</h4>
       </div>
       <div class="modal-body">
         <p>Are you sure you want to Approve this application?</p>
       </div>
       <div class="modal-footer">
         <button type="button" data-dismiss="modal" class="btn btn-success" data-response="approve" id="response">Approve</button>
         {{-- <button type="button" data-dismiss="modal" class="btn btn-danger" data-response="reject" id="reject">Reject</button> --}}
         <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
       </div>
     </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
 </div><!-- /.modal -->
</div><!-- /row -->

<div class="row">
  <div class="modal fade" id="confirm_reject">
   <div class="modal-dialog" role="document">
     <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
         <h4 class="modal-title">Reject Application</h4>
       </div>
       <div class="modal-body">
         <p>Are you sure you want to Reject this application?</p>
       </div>
       <div class="modal-footer">
         {{-- <button type="button" data-dismiss="modal" class="btn btn-success" data-response="approve" id="approve">Approve</button> --}}
         <button type="button" data-dismiss="modal" class="btn btn-danger" data-response="reject" id="response">Reject</button>
         <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
       </div>
     </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
 </div><!-- /.modal -->
</div><!-- /row -->


@include('includes.confirm-modal',  ['name'   => 'FieldRep'])
</div>
@stop

@section('custom-script')

<script type="text/javascript">
  var oTable ='';
  $(document).ready(function(){
    oTable = $('#fieldrep-grid').DataTable( {
      "serverSide": true,
      "order": [ 1, "desc" ],
      ajax: {
        url: '{{ route("show.fieldreps.post") }}',
        type: 'POST',
        data: function (d) {         
          d.status = $('select[name=status]').val();
          d.state = $('select[name=state]').val();        
          d.classification = $('select[name=classification]').val();        
        }
      },
      columns: [
      {data: 'id',                name: 'f.id', visible: false},
      {data: 'fieldrep_code',     name: 'f.fieldrep_code', 'width':'7%'},
      {data: 'full_name',         name: 'f.first_name'},
      {data: 'location',          name: 'co.city'},
      {data: 'email',             name: 'u.email'},
      {data: 'approved_for_work', name: 'f.approved_for_work', 'width': '7%'},
      {data: 'status',            name: 'f.initial_status', 'width': '7%'},
      {data: 'action',            name: 'action', orderable: false, searchable: false, 'width': '8%'}
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

    $('#confirm_send_email').on('hidden.bs.modal',function(){
      $('.modal').find( "#send" ).unbind( "click" );
      $('.alert-danger').hide();
    });
  });

  $(document).on('click', 'button[name="send_invite"]', function(e){

    e.preventDefault();
    console.log($(this).data('id'));
    var id = $(this).data('id');
    var formData = {user_id :id};
    var url = APP_URL+'/email/send-invitation/payee';
    var type = "POST";
    $('#confirm_send_email').modal({ backdrop: 'static', keyboard: false });
    $('#confirm_send_email').find('#send').bind('click', function() {
      $.ajax({
        type: type,
        url: url,
        data: formData,
        dataType: 'json',
        success: function (res) {
          oTable.draw();
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

  $(document).on('click', '.app_respond_btns', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    var url = APP_URL+'/respond-to-application';
    var type = "POST";

    $('.app_respond_btns#approve').data('response', 1);
    $('.app_respond_btns#reject').data('response', 0);

    var response = $(this).data('response');
    var modal = $(this).data('response') == 1 ? '#confirm_approve' : '#confirm_reject';

    $(modal).modal({ backdrop: 'static', keyboard: false });
    $(modal+' #response').bind('click', function() {
      //var response = $(this).data('response');
      $.ajax({
        type: type,
        url: url,
        async: false,
        data: {id :id, is_approved: response},
        dataType: 'json',
        success: function (res) {
          oTable.draw();
          DisplayMessages(res.message,'success');
        },
        error: function (jqXHR, exception) {
          var Response = jqXHR.responseText;
          Response = $.parseJSON(Response);
          DisplayMessages(Response.message,'error');
        }
      });
      // $(modal+' #response').unbind('click');
    });
  });

  $('#confirm_approve, #confirm_reject').on('hide.bs.modal',function(e){
    $(this).find('#response').unbind('click');
  });

  $(document).on('click', 'button[name="remove_fieldrep"]', function(e){
    e.preventDefault();  
    var $form=$(this).closest('form');
    var fieldrep_id =  $(this).data('id');
    var formData = {id :fieldrep_id};
    var url = APP_URL+'/fieldreps-delete';
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