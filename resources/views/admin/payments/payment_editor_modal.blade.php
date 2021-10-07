<div class="row"><!-- assignment editor  -->
  <div class="modal fade" id="assignment_payment_editor_modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">
            Assignment Edit
          </h4>
        </div>
        <div class="modal-body">
          {{  Form::open([
          'method'=>'post',
          'id' => 'assignment_edit']) 
        }}

        {{  Form::hidden('assignment_id',@$assignment_id)  }}

        <div class="box">
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="alert" style="display: none"></div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  {{  Form::label('site', 'Site Name')}}
                  {{  Form::text(
                  'site_name','',
                  [
                  'id' => 'site_name',
                  'disabled' => 'disabled',
                  'class' => 'form-control',
                  ])
                }} 
              </div>
            </div>
          </div>
          <div class="row">
            
           <div class="col-md-6">
            <div class="form-group">
              {{  Form::label('fieldrep_id', 'Field Rep')}}
              {{  Form::text(
              'fieldrep_name','',
              [
              'id' => 'fieldrep_name',
              'class' => 'form-control',
              'readonly' => 'true',
              ])
            }}
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            {{  Form::label('schedule_date', 'Schedule Date')}}
            {{  Form::text(
            'schedule_date','',
            [
            'id' => 'assignment_schedule_date',
            'class' => 'form-control',

            ])
          }} 
        </div>
      </div>
    </div><!-- /.row -->

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          {{  Form::label('round_start_date', 'Actual Start Date')}}
          {{  Form::text(
          'round_start_date','',
          [
          'id' => 'round_start_date',
          'class' => 'form-control',
          'disabled' => 'true',
          ])
        }} 
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
       {{  Form::label('start_date', 'Override Start Date')}}
       {{  Form::text(
       'start_date','',
       [
       'id' => 'assignment_start_date',
       'class' => 'form-control',

       ])
     }} 
   </div>
 </div>
</div><!-- /.row -->
<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      {{  Form::label('round_deadline_date', 'Actual Deadline Date')}}
      {{  Form::text(
      'round_deadline_date','',
      [
      'id' => 'round_start_date',
      'class' => 'form-control',
      'disabled' => 'true',
      ])
    }} 
  </div>
</div>
<div class="col-md-6">
  <div class="form-group">
   {{  Form::label('dealine_date', 'Override Deadline Date')}}
   {{  Form::text(
   'deadline_date','',
   [
   'id' => 'assignment_deadline_date',
   'class' => 'form-control',

   ])
 }} 
</div>
</div>
</div><!-- /.row -->

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      {{  Form::label('round_start_time', 'Actual Start Time')}}
      {{  Form::text(
      'round_start_time','',
      [
      'id' => 'round_start_time',
      'class' => 'form-control',
      'disabled' => 'true',
      ])
    }} 
  </div>
</div>
<div class="col-md-6">
  <div class="bootstrap-timepicker">
    <div class="form-group">
      <label>Start Time</label>
      {{  Form::text(
      'start_time','',
      [
      'id' => 'assignment_start_time',
      'class' => 'form-control timepicker',
      
      ])
    }} 
  </div><!-- /.form group -->
</div>
</div>
</div><!-- /.row -->

<div class="row">
  <div class="col-md-6">
    <div class="form-group">
      {{  Form::label('round_estimated_duration', 'Actual Estimated Duration')}}
      {{  Form::text(
      'round_estimated_duration','',
      [
      'id' => 'round_estimated_duration',
      'class' => 'form-control',
      'disabled' => 'true',
      ])
    }} 
  </div>
</div>
<div class="col-md-6">
  <div class="form-group">
   {{  Form::label('estimated_duration', 'Estimated Duration')}}
   {{  Form::text(
   'estimated_duration','',
   [
   'id' => 'assignment_estimated_duration',
   'class' => 'form-control',
   
   ])
 }} 
</div>
</div>
</div><!-- /.row -->
</div>
<div class="box-footer">

  <div class="pull-right">
    <div class="pull-right">
      <button type="button" class="btn btn-primary" id="save_assignment" name="save_assignment">Save</button>
    </div>
    <div class="col-md-1 pull-right">
      <button type="button" data-dismiss="modal" id="cancel" class="btn btn-default pull-right">Cancel</button>
    </div>
  </div>                                                                                                    
</div><!-- /.box-footer -->
</div><!-- /.box -->

{{ Form::close() }}
</div><!-- /.modal-body -->
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div><!-- /row -->
