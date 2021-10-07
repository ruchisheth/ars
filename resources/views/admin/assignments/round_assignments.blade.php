@section('custome-style')

<!-- Dual Listbox -->
{{ Html::style(AppHelper::ASSETS.'plugins/dual-listbox/bootstrap-duallistbox.css') }}

@append

<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">
      {{ trans('messages.assignments') }}
    </h3>
    <div class="box-tools pull-right">
      @if($round->isDeadlinePast())
      {{  Form::button('<i class="fa fa-plus"></i>',
        [
        'id' => 'create_assignment',
        'class' => 'btn btn-box-tool ',
        ])
      }}
      @else
      {{  Form::button('<i class="fa fa-plus"></i>',
        [
        'id' => 'create_assignments',
        'class' => 'btn btn-box-tool ',
        'data-toggle' => 'modal',
        'data-target' => '#assignments'
        ])
      }}
      @endif
    </div>
    <div class="col-md-6" style="float:right">
      <div class="alert" style="display: none"></div>
    </div>
  </div>
  <div class="box-body">
    <div class="table-responsive">
      <table id="assignments-grid" class="table table-bordered">
        <thead>
          <tr>
            <th></th>
            <th>{{ trans('messages.site_code') }}</th>
            <th>{{ trans('messages.location') }}</th>
            <th>{{ trans('messages.schedule_to') }}</th>
            <th>{{ trans('messages.status') }}</th>
            <th>
              <button class="btn btn-box-tool" type="button" name="remove_assignment"  data-round_id="{{ $round->id }}" value="delete" title="delete all"><span class="fa fa-trash"></span></button>
            </th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div><!-- /.box-body -->
</div><!-- /.box -->


@include('admin.assignments.assignment_generate_modal')<!-- assignment generate modal -->
@include('admin.assignments.assignment_editor_modal')<!-- assignment edit modal -->
{{-- @include('admin.assignments.assignment_payment_modal',['payment_types'   => @$payment_types])<!-- assignment payment modal --> --}}
@include('admin.assignments.assignment_fieldrep_modal')<!-- Schedule FieldReps -->
{{-- @include('includes.confirm-modal',['name'   => 'assignment'])<!-- delete assignment --> --}}
@include('includes.confirm_delete_modal',
  [
  'id'    =>  'assignments',
  'name'  =>  'Assignments',
  'msg'   =>  trans('messages.all_assignment_delete_confirm_message')
  ])
  
@include('includes.confirm_delete_modal',
  [
  'id'    =>  'assignment',
  'name'  =>  'Assignment',
  'msg'   =>  trans('messages.assignment_delete_confirm')
  ])



@section('custom-script')

<script type="text/javascript">

  $(document).ready(function () {

    initSelect();

    oAssignmentTable = $('#assignments-grid').DataTable({
      "processing": true,
      "serverSide": true,
      "paging": true,
      "bFilter": false,
      "bInfo": false,
      "order": [ 1, "desc" ],
      ajax: {
        type: 'POST',
        url: '{!! url('assignments',[@$round->id]) !!}',
      },
      columns: [
      {data: 'id', name: 'a.id', visible: false},
      {data: 'site_code', name: 's.site_code', width: '20%'},
      {data: 'city', name: 's.city'},
      {data: 'schedule_to', name: 'schedule_to', width: '23%'},
      {data: 'status', name: 'a.stutus', orderable: false, searchable: false, width: '7%'},
      {data: 'action', name: 'action', orderable: false, searchable: false, width: '13%'}
      ]
    });/* oAssignmentTable */

    $(document).on('showError', function(e, msg){
     toastr.error(msg);
   });

    $(document).on('click', '#ded_past ', function(e){
      msg = 'Can not schedule Assignment to FieldRep as Deadline Date is Passed';
      $(this).trigger('showError', msg);
    });

    $(document).on('click', '#create_assignment', function(e){
      msg = 'Can not generate Assignment as Deadline Date of Round is Passed';
      $(this).trigger('showError', msg);
    });
    
  }); /* /.document.ready over*/

</script>

@append




