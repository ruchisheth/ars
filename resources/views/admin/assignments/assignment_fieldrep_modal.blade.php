<div class="row"><!-- assignment editor  -->
  <!-- modal -->
  <div class="modal fade" id="fieldrep_schedule_modal">
    <div class="modal-dialog  modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">
            FieldReps
          </h4>
        </div>
        <div class="modal-body">
          {{  Form::open([
            'method'=>'post',
            'id' => 'assignment_fieldrep']) 
          }}

          <div class="box">
            <div class="box-body">
            <div class="table-responsive">
              <table id="fieldrep-grid" class="table table-bordered table-hover" width="100%">
                <thead>
                  <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Distance (miles)</th>
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

          {{ Form::close() }}
        </div><!-- /.modal-body -->
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</div><!-- /row -->

@include('admin.assignments.assignment_schedule_modal',['fieldreps' => @$fieldreps])<!-- schedule modal -->
@include('admin.assignments.assignment_offer_modal',['fieldreps' => @$fieldreps])<!-- offer modal -->

@section('custom-script')

<script type="text/javascript">

  var ofieldrepTable ='';
  var AssignmentId = SelectedAssignment = null;
  var SelectedFieldRep = null;
  $(document).ready(function () {

    $('#fieldrep_schedule_modal').on('hidden.bs.modal', function (event) {
      SelectedAssignment = null;
    });
    $('#fieldrep_schedule_modal').on('shown.bs.modal', function (event) 
    {
      var assignment_id = AssignmentId  = SelectedAssignment = $(event.relatedTarget).data('id');
      ofieldrepTable.column( '3' ).order( 'asc' ).draw();
    });

    $(document).on('click', '.schedule', function (e) {
      e.preventDefault();
      var form = $("#assignment_schedule");
      var assignment_id = $(this).data('assignment-id');
      var fieldrep_id = $(this).data('fieldrep-id');
      var url = '{{ route("edit.schedule.assignment") }}';
      $.ajax({
        type: "POST",
        url: url,
        data: { assignment_id: SelectedAssignment, fieldrep_id : fieldrep_id},
        dataType: "json",
        success: function (res) {
          SetFormValues(res.inputs, form);
          initAssignmentSchedule(res.minDate,res.maxDate);
        }
      });

    });

    ofieldrepTable = $('#fieldrep-grid').DataTable( {
      "processing": true,
      "serverSide": true,
      "order": [ 0, "asc" ],
      ajax: {
        url: APP_URL + '/fieldreps',
        type: 'POST',
        data: function (d) {
          d.set_criteria = true;
          d.assignment_id = AssignmentId;
          d.is_pending = true;
        }
      },
      columns: 
      [
      {data: 'fieldrep_code', name: 'f.fieldrep_code'},
      {data: 'full_name', name: 'f.first_name'},
      {data: 'location', name: 'co.city'},
      {data: 'distance', name: 'distance', searchable: false},
      {data: 'status', name: 'f.status', orderable: false},
      {data: 'schedule', name: 'schedule', orderable: false, searchable: false},
      ],
      "aoColumnDefs": 
      [
      { "sWidth": "7%", "targets": [0,3,4] }
      ],
    });

    $('#fieldrep_schedule_modal').on('hidden.bs.modal', function () {
      $('.alert').hide();
      ofieldrepTable.search('');
    });

  });/* ready over*/

  
</script>
@append