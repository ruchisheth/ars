{{ Form::open(['method'=>'post', 'id' =>  'offer']) }}
<div class="box box-default">
  <div class="box-header with-border">
    <i class="fa fa-check-square-o"></i>
    <h3 class="box-title">New Offers</h3>
    <div class="box-tools pull-right">
      <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-up"></i></button>
    </div>
    @include('includes.success')
    @include('includes.errors')
  </div><!-- /.box-header -->

  <div class="box-body">
    <div class="table-responsive">
     <table id="offer_grid" class="table no-margin">
      <thead>
        <tr>
          <th>Project</th>
          <th>Round</th>
          <th>Site</th>
          <th>Scheduled DT</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
    </table>
  </div>
</div><!-- /.box-body -->
{{-- @if($offer_count > 0) --}}
<div class="box-footer clearfix hide">
  <div class="pull-right">
    <div class="pull-right">
      <button type="button" class="btn btn-sm btn-danger offer" value="0">Reject</button>
    </div>
    <div class="col-md-1 pull-right">
      <button type="button" class="btn btn-sm btn-success pull-right offer" value="1">Accept</button>
    </div>
  </div> 
</div> 
{{-- @endif --}}
</div><!-- /.box -->
{{ Form::close() }}
@include('includes.confirm-modal',
  [
  'action' => 'Accept',
  'name'   => 'Offer',
  'id'  => 'accept_offer',
  'msg' => 'Are you sure you want to accept these Offers?',
  'btn' => ['Yes','No'],
  ])

@include('includes.confirm-modal',
  [
  'action' => 'Reject',
  'name'   => 'Offer',
  'id'  => 'reject_offer',
  'msg' => 'Are you sure you want to reject these Offers?',
  'btn' => ['Yes','No'],
  ])

@include('fieldrep.includes.reject_offer_reason')