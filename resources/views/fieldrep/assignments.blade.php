@extends('fieldrep.app')
@section('page-title') | Assignments @stop
@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-primary">
        <div class="box-header with-border">
          <i class="fa fa-check-square-o"></i>
          <h3 class="box-title">Assignments </h3>
          @include('includes.success')
          @include('includes.errors')
        </div><!-- /.box-header -->

        <div class="box-body">
          <div class="table-responsive">
           <table id="assignments-grid" class="table table-bordered table-hover" width="100%">
            <thead>
              <tr>
               <th>Logo</th>
               <th>Site Code</th>                     
               <th>Project</th>
               <th>Round</th>
               <th>Location</th>                       
               <th>Schedule DT</th> 
               <th>Deadline DT</th>
               <th>Status</th>
               <th>&nbsp;</th>
               <th>&nbsp;</th>
             </tr>
           </thead>
         </table>
       </div>
     </div><!-- /.box-body -->

   </div><!-- /.box -->
 </div>
</section>

</div>
@include('fieldrep.instruction')
@include('fieldrep.calendar_modal')
@stop

@section('custom-script')
{{ Html::script(AppHelper::ASSETS.'dist/js/pages/fieldrep_dashboard.js') }}
<script type="text/javascript">

  var oAssignmentTable ='';
  $(document).ready(function(){

    oAssignmentTable = $('#assignments-grid').DataTable( {
      "serverSide": true,
      "order": [ 1, "desc" ],
      // "bFilter": false,
      ajax: {
        url: '{!! route("fieldrep.show.assignments.post") !!}',
        type: 'POST',
        data: function (d) {
          d.project_status = 1;
          d.round_status = 1;
        },
      },
      columns: [
      {data: 'client_logo',           name: 'c.client_logo',  orderable: false, searchable: false,  'width': '7%'},
      {data: 'site_code',             name: 's.site_code', 'width':'10%'},
      {data: 'project_name',          name: 'p.project_name'},
      {data: 'round_name',            name: 'r.round_name'},
      {data: 'city',                  name: 's.city',               width:'18%'},
      {data: 'assignment_scheduled',  name: 'assignment_scheduled', width:'15%'},
      {data: 'assignment_end',        name: 'assignment_end',       width:'15%'},
      {data: 'status',                name: 'a.status',             width: '7%',orderable: false,  searchable: false},
      {data: 'survey',                name: 'survey',   orderable: false,  searchable: false},
      {data: 'action',                name: 'action',       width: '7%', orderable: false,  searchable: false},
      ],
      aoColumnDefs: [
      {className: "client-td", "targets": [0] }
      ],
    });

  });/* .ready over*/

  function SetAssignmentDetails(element,e)
  {
    e.preventDefault();
    table = $('#detail-grid');
    var Id = $(element).attr('data-id');
    var APP_URL = $('meta[name="_base_url"]').attr('content');
    $.ajax({
      type: 'POST',
      url: APP_URL+'/fieldrep/assignment-details',
      data: { assignment_id :Id },
      success: function (res) {
        details = res.details;
        table.find('#assignment_id').text(details.site_code);
        table.find('#start_date').text(details.start_date);
        table.find('#deadline_date').text(details.deadline_date);
        table.find('#schedule_date').text(details.schedule_date);
        table.find('#round_name').text(details.round_name);
        table.find('#project_name').text(details.project_name);
        table.find('#client_name').text(details.client_name);
        table.find('#location').text(details.location);
        $('#client_logo').attr('src',APP_URL+'/{{ AppHelper::CLIENT_LOGO }}'+details.client_logo);
      },
    });
    $('#calendarModal').modal('show');
  }

</script>

@append