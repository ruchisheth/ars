@extends('app')
@section('page-title') | Rounds @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <i class="fa fa-dot-circle-o"></i>
        <h3 class="box-title">Rounds</h3>
        @include('includes.success')
        @include('includes.errors')
        <div class="box-tools">
         <a href="{{url('/rounds-edit')}}" class="btn btn-block btn-box-tools btn-sm"><i class="fa fa-plus"></i></a>
       </div>
     </div><!-- /.box-header -->
     <div class="box-body">
       <form class="form-inline section-filter" id="search-form" method="post">
        <div class="form-group">
          <label for="pwd">Project</label>
          {{  Form::select(
           'project_id', @$project_list,
           @$project_list,
           [
           'id' => 'project_id',
           'class'=>'form-control',
           'data-placeholder'=>'Select Project'               
           ]) 
         }}
       </div>
        <div class="form-group">
          <label for="status">Status</label>
          {{  Form::select('status', array(
            ''  =>  'Select Status',
            '1'  => 'Active',
            '0'  => 'Inactive',
            '3' =>  'Pending',                          
            ), @$status_filter,
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
    <table id="round-grid" class="table table-bordered">
      <thead>
        <tr>
          <th>&nbsp;</th>
          <th class='text-right'>Code</th>
          <th>Round Name</th>
          <th>Start DT</th> {{-- <th>Start Date/Time</th> --}}
          <th>Deadline DT</th> {{-- <th>Deadline Date/Time</th> --}}
          {{-- <th>Deadline DT</th> --}}{{-- <th>Deadline Date</th> --}}
          <th>Schedule DT</th>{{-- <th>Schedule Date</th> --}}
          <th title="Total Assignments">#Tot</th>{{-- <th># Assignments</th> --}}
          <th title="Scheduled Assignments">#Sched</th>{{-- <th>#Scheduled</th> --}}
          <th title="Reported Assignments">#Rpt</th>
          <th>Status</th>
          <th>&nbsp;</th>
        </tr>
      </thead>            
    </table>
  </div>
</div><!-- /.box-body -->
</div><!-- /.box -->
</section>
@include('includes.confirm-modal',
  [
  'name'   => 'Round',
  'id'  => 'confirm_delete_round',
  ])
</div>
@stop
@section('custom-script')

<script type="text/javascript">

  var roundTable ='';
  $(document).ready(function(){

    var project_id = getUrlVars()['project_id'] != '' ? getUrlVars()['project_id'] : '';
    $('select[name=project_id]').val(project_id);

    roundTable = $('#round-grid').DataTable( {
      "serverSide": true,
      "order": [ 1, "desc" ],
      ajax: {
        url: 'rounds',
        type: 'POST',
        data: function (d) {
          d.project_id = $('select[name=project_id]').val();
          d.status = $('select[name=status]').val();              
        }
      },
      columns: [
      { data: 'client_logo',      name: 'c.client_logo',    className:'client-td',  orderable:false,searchable :false},
      { data: 'id',               name: 'r.id',             className: 'text-right' },
      { data: 'round_name',       name: 'r.round_name',     width:'18%' },
      { data: 'round_start',      name: 'round_start',      className:'text-right', width:'14%'},
      { data: 'round_end',        name: 'round_end',        className:'text-right', width:'14%'},
      { data: 'schedule_date',    name: 'r.schedule_date',  className:'text-right', width: '12%'},
      { data: 'assignment_count', name: 'assignment_count', className:'text-right', searchable: false },
      { data: 'scheduled',        name: 'scheduled',        className:'text-right', searchable: false },
      { data: 'reported',         name: 'reported',         className:'text-right', searchable: false },
      { data: 'status',           name: 'r.status',         orderable: false },
      { data: 'action',           name: 'action',           orderable: false, searchable: false}
      ]
    });

    /* custom filter for datatable */
    $('#search-form').on('submit', function(e) {    
      roundTable.draw();
      e.preventDefault();
      
    });
    $('#search-form-reset').on('click', function(e) {           
      $('#search-form')[0].reset();
      roundTable.draw();           
    });

    $(document).on('click', 'button[name="remove_round"]', function(e){
      e.preventDefault();
      var $form=$(this).closest('form');
      var $parent_tr = $(this).closest('tr');
      var round_id =  $(this).data('id');
      var formData = {id :round_id};
      var url = APP_URL+'/rounds-delete';
      var type = "POST";

      $('#confirm_delete_round').modal({keyboard: false });
      $('#confirm_delete_round').find('#delete').bind('click', function() {      
       $.ajax({
        type: type,
        url: url,
        data: formData,
        dataType: 'json',
        success: function (data) {
          DisplayMessages(data['message']);
          roundTable.draw();
        },
        error: function (jqXHR, exception) {
         var Response = jqXHR.responseText;
         Response = $.parseJSON(Response);
         DisplayMessages(Response.message,'error');
       }
     });
     });
    }); /*  DELETE Round over */

  });/* .ready overe*/

</script>

@stop



