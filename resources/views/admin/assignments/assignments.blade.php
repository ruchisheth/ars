@extends('app')
@section('page-title') | Assignments @stop
@section('content')

<div class="content-wrapper">
  <section class="content">
   <div class="box">
    <div class="box-primary">
      <div class="box-header with-border">
        <i class="fa fa-check-square-o"></i>
        <h3 class="box-title">Assignments </h3>
        <div class="box-tools custom-box-tools">
          @include('admin.assignments.assignments_counter')
        </div>
        @include('includes.success')
        @include('includes.errors')
      </div><!-- /.box-header -->

      <div class="box-body">
        <form class="form-inline section-filter" id="search-form" method="post">
          {{-- <div class="row"> --}}
          {{-- <div class="col-md-10"> --}}
          <div class="form-group">
            <label for="project">Project</label>
            {{  Form::select(
              'project_id', @$project_list,
              @$project_list,
              [
              'id' => 'project_id',
              'class'=>'form-control',
              'data-placeholder'=>'Select Project',
              ]) 
            }}
            <input type="hidden" id="all_round" name="all_round" value='all'>
          </div>
          <div class="form-group">
            <label for="round">Round</label>
            {{  Form::select(
              'round_id',@$round_list,
              @$round_filter,
              [
              'id' => 'round_id',
              'class'=>'form-control',
              'data-placeholder'=>'Select Round',
              ]) 
            }}
          </div>  
          <div class="form-group">
            <label for="status" class="">Status</label>
            {{  Form::select('status', array(
              ''  =>  'Select Status',
              'pending'  => trans('messages.assignment_status.pending'),
              'offered'  => trans('messages.assignment_status.offered'),
              'scheduled'  => trans('messages.assignment_status.scheduled'),
              'late'  => trans('messages.assignment_status.late'),
              'reported'  => trans('messages.assignment_status.reported'),
              'partial'  => trans('messages.assignment_status.rejected'),
              'completed'  => trans('messages.assignment_status.completed'),
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
          <button type="button" class="btn btn-default hide" data-toggle="modal" data-target="#delete_assignments" name="remove_assignment">Delete</button>
        </div>
      </form>
      <div class="box-header with-border custom-header"></div>
      <div class="table-responsive individual_search">
        <table id="assignments-grid" class="table table-bordered table-hover select" width="100%">
          <thead>
            <tr>
              <th><input name="select_all" type="checkbox"  class="minimal" id="bulk_delete" data-scope="#assignments-grid" /> {{-- <button id="deleteTriger">Delete</button> </th> --}}
              <th>&nbsp;</th>{{-- <th>Client</th> --}}
              <th>&nbsp;</th>
              <th>Site Code</th>                     
              <th>Project Name</th>
              <th>Round Name</th>
              <th>Location</th>                       
              <th>Schedule DT</th>
              <th>Deadline DT</th>
              <th>Schedule To</th>
              <th>Status</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <td class="non_searchable"></td>
              <td class="non_searchable"></td>
              <td class=""></td>
              <td class=""></td>
              <td class=""></td>
              <td class=""></td>
              <td class=""></td>
              <td class=""></td>
              <td class=""></td>
              <td class=""></td>
              <td class="non_searchable"></td>
              <td class="non_searchable"></td>              
            </tr>
          </tfoot>
        </table>
      </div>
    </div><!-- /.box-body -->

  </div><!-- /.box -->
</div>
</section>

<!-- assignment edit modal -->
@include('admin.assignments.assignment_editor_modal')

<!-- fieldrep -->
@include('admin.assignments.assignment_fieldrep_modal', ['fieldreps' => @$fieldreps])

<!-- confirm modal -->
{{-- @include('includes.confirm-modal',['name' => 'Assignment']) --}}

@include('includes.confirm_delete_modal',
  [
  'id'    =>  'assignment',
  'name'  =>  'Assignment',
  'msg'   =>  trans('messages.assignment_delete_confirm')
  ])



</div>
@stop

@section('custom-script')

<script type="text/javascript">

  var oAssignmentTable ='';
  var oTable ='';
  
  $(document).ready(function(){

    $("#round_id").depdrop({
      url: "{!! url('api/dropdown/rounds') !!}",
      depends: ['project_id'],
      params: ['all_round'],
    });

    $('#round_id').on('depdrop.beforeChange', function(event, id, value) {
      if(value == ''){
        value = 'all';
      }
    });

    // var rows_selected = [];
    oAssignmentTable = $('#assignments-grid').DataTable( {
      "serverSide": true,
      "order": [ 2, "desc" ],
      ajax: {
        url: '{!! url("assignments") !!}',
        type: 'POST',
        data: function (d) {
          d.project_id = $('select[name=project_id]').val();
          d.round_id = $('select[name=round_id]').val();
          d.status = $('select[name=status]').val();
        }
      },
      columns: [
      {data: 'bulk_delete',           name: 'bulk_delete',    'width': '2%', orderable:false, searchable :false},
      {data: 'client_logo',           name: 'c.client_logo',  'className': "client-td", 'width': '5%', orderable:false,searchable :false},
      {data: 'id',                    name: 'a.id',           visible:false},
      {data: 'site_code',             name: 's.site_code'},           
      {data: 'project_name',          name: 'p.project_name'},
      {data: 'round_name',            name: 'r.round_name'},
      {data: 'city',                  name: 's.city',               'width': '15%'},
      {data: 'assignment_scheduled',  name: 'assignment_scheduled', className: 'text-right'},
      {data: 'assignment_end',        name: 'assignment_end',       className: 'text-right'},
      {data: 'schedule_to',           name: 'schedule_to'},
      {data: 'status',                name: 'a.status', orderable: false, searchable: false},
      {data: 'action',                name: 'action', orderable: false, searchable: false},
      ],
      'rowCallback': function(row, data, dataIndex){
        var rowId = data['id']; // Get row ID

        // If row ID is in the list of selected row IDs
        if($.inArray(rowId, rows_selected) !== -1){
          $(row).find('.entity_chkbox').prop('checked', true);
          $(row).addClass('selected');
        }
      },
      drawCallback: function() {
        getAssignmentCounts();
        initCheck('.entity_chkbox');
      },
      initComplete: function () {
        this.api().columns().every(function () {
          var column = this;
          var input = document.createElement("input");
          var columnClass = column.footer().className;
          var timeoutId = 0;
          if (columnClass.indexOf("non_searchable") == -1){
            // $(input).appendTo($(column.header())).addClass('form-control').attr('tabindex', 1)
            $(input).appendTo($(column.footer()).empty()).addClass('form-control').attr('tabindex', 1)
            .on('change', function(e){
                column.search($(this).val(), false, false, true).draw();
            });
          }
        });
      }
    });
    initSelectAll('#assignments-grid', oAssignmentTable);

    oAssignmentTable.on('search.dt', function () {
      console.log('search');
      deselect_all();
    });

    $(document).on('showError', function(e, msg){
      toastr.error(msg);
    });

    $(document).on('click', '#ded_past ', function(e){
      msg = 'Can not schedule Assignment to FieldRep as Deadline Date is Passed';
      $(this).trigger('showError', msg);
    });

    /* custom filter for datatable */
    $('#search-form').on('submit', function(e) {      
      e.preventDefault();
      deselect_all();            
      oAssignmentTable.draw();
    });

    $('#search-form-reset').on('click', function(e) {
      $('#search-form')[0].reset();
      deselect_all();
      oAssignmentTable.draw();           
    });

    $(document).on('click', '.as_status', function(e){  

      status = $(this).data('status');
      $('select[name=status]').val(status);
      $('#search-form').trigger('submit');
    });

  });/* .ready over*/

  function getAssignmentCounts()
  {
    var project_id = $('select[name=project_id]').val();
    var round_id = $('select[name=round_id]').val();
    $.ajax({
      type: 'POST',
      url: APP_URL+'/assignments-counts',
      data: {project_id: project_id, round_id: round_id},
      dataType: 'json',
      success: function (res) {
        $('#pending_count').html(res.counts.pending_count);
        $('#offered_count').html(res.counts.offered_count);
        $('#scheduled_count').html(res.counts.scheduled_count);
        $('#late_count').html(res.counts.late_count);
        $('#reported_count').html(res.counts.reported_count);
        $('#partial_count').html(res.counts.partial_count);
        $('#completed_count').html(res.counts.completed_count);
      },
      error: function (data) {
      }
    });
  }
</script>

@append