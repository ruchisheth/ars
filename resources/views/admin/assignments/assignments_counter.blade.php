<div class="bx-1 as_status" data-status="completed">
  <label class="label label-success">
    <span class="badge-desc">{{ trans('messages.assignment_status.completed') }}</span>
    <span class="badge bg-white" id="completed_count">
      {{-- {{ @$count['completed_count'] }} --}}
      </span>
  </label>
</div>
<div class="bx-1 as_status" data-status="partial">
  <label class="label label-danger">
    <span class="badge-desc">{{ trans('messages.assignment_status.rejected') }}</span>
    <span class="badge bg-white" id="partial_count">
    {{-- {{ @$count['partial_count'] }} --}}
    </span>
  </label>
</div>
<div class="bx-1 as_status" data-status="reported">
  <label class="label label-reported">
    <span class="badge-desc">{{ trans('messages.assignment_status.reported') }}</span>
    <span class="badge bg-white" id="reported_count">
    {{-- {{ @$count['reported_count'] }} --}}
    </span>
  </label>
</div>
<div class="bx-1 as_status" data-status="late">
  <label class="label label-danger">
    <span class="badge-desc">{{ trans('messages.assignment_status.late') }}</span>
    <span class="badge bg-white" id="late_count">
    {{-- {{ @$count['late_count'] }} --}}
    </span>
  </label>
</div>
<div class="bx-1 as_status" data-status="scheduled">
  <label class="label label-primary">
    <span class="badge-desc">{{ trans('messages.assignment_status.scheduled') }}</span>
    <span class="badge bg-white" id="scheduled_count">
    {{-- {{ @$count['scheduled_count'] }} --}}
    </span>
  </label>
</div>
<div class="bx-1 as_status" data-status="offered">
  <label class="label label-offered">
    <span class="badge-desc">{{ trans('messages.assignment_status.offered') }}</span>
    <span class="badge bg-white" id="offered_count">
    </span>
  </label>
</div>
<div class="bx-1 as_status" data-status="pending">
  <label class="label label-default">
    <span class="badge-desc">{{ trans('messages.assignment_status.pending') }}</span>
    <span class="badge bg-white" id="pending_count">
    {{-- {{ @$count['pending_count'] }} --}}
    </span>
  </label>
</div>