@extends('app')
@section('page-title') | Projects @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-primary">
        <div class="box-header with-border">
          <i class="fa fa-th"></i>
          <h3 class="box-title">Projects</h3>
          @include('includes.success')
          @include('includes.errors')
          <div class="box-tools">
            <a href="{{url('/projects-edit')}}" class="btn btn-block btn-box-tools btn-sm" ><i class="fa fa-plus"></i></a>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">         
          <form class="form-inline section-filter" id="search-form" method="post">
            <div class="form-group">
              <label for="pwd">Chain</label>
              {{  Form::select(
               'chain_id', @$chain_list,
               @$chain_list,
               [
               'id' => 'chain_id',
               'class'=>'form-control',
               'data-placeholder'=>'Select Chain'
               
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
       <table id="project-grid" class="table table-bordered table-hover ">
        <thead>
          <tr>
            <th>Client Logo</th>
            {{-- <th></th> --}}
            <th>Code</th>                
            <th>Project</th>
            <th>Client</th>
            <th>Rounds</th>
            <th># Assignments</th>               
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
<script id="details-template" type="text/x-handlebars-template">
  <div class="label label-primary">Rounds</div>
  <!-- <table class="table details-table" id="posts-@{{id}}"> -->
  <div class="table-responsive">
    <table class="table table-bordered table-hover details-table" id="rounds-@{{project_code}}">
      <thead>
        <tr>
          <th>Code</th>
          <th>Name</th>
          <th>Start Date/Time</th>
          <th>Deadline Date</th>
          <th>Schedule Date</th>
          <th># Scheduled</th>
          <th># Reported</th>
          <th># Assignments</th>
          <th>Status</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
    </table>
  </div>
</script>
</section>
@include('includes.confirm-modal',['name'   => 'project'])

@include('includes.confirm-modal',
  [
  'name'   => 'Round',
  'id'  => 'confirm_delete_round',
  ])
</div>
@stop

@section('custom-script')

<!--  Handlebars  -->
{{ Html::script(AppHelper::ASSETS.'dist/js/handlebars.js') }}

<script type="text/javascript">


  var projectTable ='';
  $(document).ready(function(){

    var chain_id = getUrlVars()['chain_id'] != '' ? getUrlVars()['chain_id'] : '';
    $('select[name=chain_id]').val(chain_id);
    
    projectTable = $('#project-grid').DataTable( {
      "serverSide": true,
      "order": [ 1, "desc" ],
      ajax: {
        url: 'projects',
        type: 'POST',
        data: function (d) {
          d.chain_id = $('select[name=chain_id]').val();
          d.status = $('select[name=status]').val();              
        }
      },
      columns: [
      {data: 'client_logo', name: 'client_logo', className:'client-td', orderable:false, searchable :false, 'width': '7%'},
      {data: 'id', name: 'p.id', 'width': '7%'},        
      {data: 'project_name', name: 'project_name'}, 
      {data: 'client_name', name: 'client_name', 'width': '15%'}, 
      {data: 'round_count', name: 'round_count', orderable: false, searchable: false, className:'details-control', 'width': '7%' },
      {data: 'assignment_count', name: 'assignment_count', searchable: false, 'width': '7%'},
      {data: 'status', name: 'p.status', orderable: false, 'width': '7%'},
      {data: 'action', name: 'action', orderable: false, searchable: false, 'width': '7%'}
      ],
    });

    /* custom filter for datatable */
    $('#search-form').on('submit', function(e) {    
      projectTable.draw();
      e.preventDefault();
      
    });
    $('#search-form-reset').on('click', function(e) {           
      $('#search-form')[0].reset();
      projectTable.draw();           
    });

    var template = Handlebars.compile($("#details-template").html());

      // Event listener for opening and closing details
      $('#project-grid tbody').on('click', 'td.details-control #round_count', function () 
      {
        var tr = $(this).closest('tr');

        var row = projectTable.row(tr);        
        var tableId = 'rounds-'+row.data().project_code;

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
          tr.next().find('td').addClass('no-padding');
        }
      }); /* onClick details rounds */

      //DELETE Project
      $(document).on('click', 'button[name="remove_project"]', function(e){
        e.preventDefault();

        var $form=$(this).closest('form');
        var $parent_tr = $(this).closest('tr');
        var project_id =  $(this).data('id');
        var formData = {id :project_id};
        var url = APP_URL+'/projects-delete';
        var type = "POST";
        $('#confirm').modal({keyboard: false });
        $('#confirm').find('#delete').bind('click', function() {      
         $.ajax({
          type: type,
          url: url,
          data: formData,
          dataType: 'json',
          success: function (data) {
            projectTable.draw();
            DisplayMessages(data['message']);
          },
          error: function (jqXHR, exception) {
            var Response = jqXHR.responseText;
            Response = $.parseJSON(Response);
            DisplayMessages(Response.message,'error');
          }
        });
       });
      }); /*  DELETE Project over */

      //DELETE Project
      $(document).on('click', 'button[name="remove_round"]', function(e){
        e.preventDefault();
        var $form=$(this).closest('form');
        var $parent_tr = $(this).closest('tr');
        var round_id =  $(this).data('id');
        var formData = {id :round_id};
        var url = APP_URL+'/rounds-delete';
        var type = "POST";
        deleteRecord('#confirm_delete_round', type, url, formData,$parent_tr);
      }); /*  DELETE Project over */

    });   /* /document.ready*/

function initTable(tableId, data) {

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
    { data: 'id', name: 'id' },
    { data: 'round_name', name: 'r.round_name' },
    { data: 'start_date', name: 'r.start_date' },
    { data: 'deadline_date', name: 'r.deadline_date' },
    { data: 'schedule_date', name: 'r.schedule_date' },
    { data: 'scheduled', name: 'scheduled', orderable: false, searchable: false },
    { data: 'reported', name: 'reported', orderable: false, searchable: false },
    { data: 'assignment_count', name: 'assignment_count', orderable: false, searchable: false },
    { data: 'status', name: 'r.status', orderable: false },
    { data: 'action', name: 'action', orderable: false },

    ],
    "aoColumnDefs": [
    { "sWidth": "7%", "targets": [0] },
    { "sWidth": "2%", "targets": [8,9] },

    ],
  })
}



</script>

@stop