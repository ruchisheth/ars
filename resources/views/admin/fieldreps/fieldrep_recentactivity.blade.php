<div class="box collapsed-box">
  <div class="box-header with-border">
    <h6 class="box-title">
      Recent Activity
    </h6>        
    <div class="box-tools pull-right">
      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-down"></i></button>
    </div>
  </div>
  <div class="box-body">
   <div class="table-responsive">
    <table id="recent-grid" class="table table-bordered">
      <thead>
        <tr>
          <th>Date</th>
          <th>Project</th>
          <th>Location</th>
          <th>Status</th>                
        </tr>            
      </thead>
    </table>
  </div>
</div>
</div>

@section('custom-script')

<script type="text/javascript">
  $(document).ready(function () {
   recentActivityTable = $('#recent-grid').DataTable({
    "serverSide": true,
    "paging": true,
    "bFilter": false,
    "bInfo": false,
    "autoWidth":true,
    "iDisplayLength": 10,
    "ordering": false,
    ajax: {
      url: '{!! url('recent_activity',[@$fieldrep_id]) !!}',
      type: 'POST',
    },
    columns: [ 
    {data: 'date', name: 'updated_at'},        
    {data: 'project_name', name: 'p.project_name'},  
    {data: 'location', name: 'location'},     
    {data: 'status', name: 'status'},
    ],
    "aoColumnDefs": [
    {'bSortable': false, 'aTargets': [1,2]},
    
    { "sWidth": "7%", "targets": [3] },
    ],
  });

 });

  

</script>
@append