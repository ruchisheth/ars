@extends('app')
@section('page-title') | {{  (@$round->id) ? 'Round Edit' : 'Round Add' }} @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        {{ Form::open(
          array(
            'method'  => 'post',
            'enctype'  =>  'multipart/form-data',
            'url'  =>  route('store.round')
            )) 
          }}

          {{  Form::hidden('round_id',@$round->id)  }}
          @if(@$round->project_id != '' || @$project_id != '')
          {{  Form::hidden('project_id',(@$round->project_id) ? @$round->project_id : $project_id)  }}
          @endif 
          {{  Form::hidden('url',URL::previous())  }}
          <div class="box">
            <div class="box-header with-border">
              <i class="fa fa-dot-circle-o"></i>
              <h6 class="box-title">
                {{  (@$round->id) ? 'Round Edit' : 'Round Add' }}
              </h6>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  @include('includes.success')
                  @include('includes.errors')
                </div>
              </div>
              <div class="tls">
                <label>Round Code : {{ format_code(@$nRoundCode ? : @$round->id) }}</label><br>
              @if(@$round->project_id != '' || @$project_id != '')
              
                
                <label>Project Name : {{ @$project->project_name}}
                  @if(@$project->status == '1')
                  <span class="label label-success">Active</span>
                  @elseif(@$project->status == '0')
                  <span class="label label-danger">Inactive</span>
                  @endif
                </label> <br>                             
                <label>Chain Name : {{ @$chain_name}}</label>
                
              
              @endif
              </div>
              <div class="box-header with-border custom-header"></div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('round_name', 'Round Name',['class' => 'mandatory'])}}
                    {{  Form::text(
                      'round_name', @$round->round_name,
                      [
                      'id' => 'round_name',
                      'class' => 'form-control',
                      'autofocus' => true,
                      ])
                    }}
                  </div>
                </div>
              </div>

              @if(@$round->id == '' && @$project_id == '')
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('project', 'Project', ['class' => 'mandatory']) }}
                    {{  Form::select('project_id',@$project_list, '',
                      [
                      'id' => 'project_id',
                      'class' => 'form-control',
                      ])
                    }}

                  </div>
                </div>
              </div>
              @endif

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('survey', 'Survey') }}
                    {{  Form::select('template_id',@$surveys, @$round->template_id,
                      [
                      'id' => 'template_id',
                      'class' => 'form-control',
                      (@$assignment_count > 0) ? 'disabled' : '',
                      ])
                    }}

                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    {{  Form::label('start_date', 'Start Date',['class'=>'mandatory'])}}
                    {{  Form::text(
                      'start_date', (@$round->start_date) ? @$round->start_date : '',
                      [
                      'id' => 'start_date',
                      'class' => 'form-control no_key',                      
                      'data-mask' => '',
                      ])
                    }}
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    {{  Form::label('deadline_date', 'Deadline Date',['class'=>'mandatory'])}}
                    {{  Form::text(
                      'deadline_date', (@$round->deadline_date) ? @$round->deadline_date : '',
                      [
                      'id' => 'deadline_date',
                      'class' => 'form-control no_key',
                      ])
                    }}
                  </div>
                </div>  
                <div class="col-md-4">
                  <div class="form-group">
                    {{  Form::label('schedule_date', 'Schedule Date ',['class'=>'mandatory'])}}
                    {{  Form::text(
                      'schedule_date', (@$round->schedule_date) ? @$round->schedule_date : '',
                      [
                      'id' => 'schedule_date',
                      'class' => 'form-control no_key',
                      ])
                    }}
                  </div>
                </div>              
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="bootstrap-timepicker">
                    <div class="form-group">
                      {{  Form::label('start_time', 'Start Time')}}
                      {{  Form::text(
                        'start_time',(@$round->start_time) ? @$round->start_time : '',
                        [
                        'id' => 'start_time',
                        'class' => 'form-control timepicker',
                        ])
                      }}
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="bootstrap-timepicker">
                    <div class="form-group">
                      {{  Form::label('deadline_time', 'Deadline Time')}}
                      {{  Form::text(
                        'deadline_time',(@$round->deadline_time) ? @$round->deadline_time : '',
                        [
                        'id' => 'deadline_time',
                        'class' => 'form-control timepicker',
                        ])
                      }}
                    </div>
                  </div>
                </div>

              {{-- <div class="col-md-6">
                <div class="bootstrap-timepicker">
                  <div class="form-group">
                    {{  Form::label('estimated_duration', 'Estimated Duration')}}
                    {{  Form::text(
                      'estimated_duration', @$round->estimated_duration,
                      [
                      'id' => 'estimated_duration',
                      'class' => 'form-control timepicker',
                      'placeholder' => 'HH:MM',
                      'data-inputmask' => '"mask": "99:99"',
                      'data-mask' => '',
                      ])
                    }}
                  </div>
                </div>
              </div> --}}
            </div>
            <div class="row">

              <div class="col-md-6">
                <div class="form-group">
                  {{  Form::label('status', 'Status')}}
                  {{  Form::select('status',
                    array(
                      ''   => 'Select Status',
                      '1'   => 'Active',
                      '0'   => 'Inactive',
                      '3'   => 'Pending',
                      ), (@$round->status) ? @$round->status : '2',
                    [
                    'id' => 'status',
                    'class' => 'form-control',
                    ])
                  }}
                </div>
              </div>
            </div>

            <div class="box-header with-border custom-header custom-border">
              <h6 class="box-title">
                <small>DAYS BEFORE START DATE AND AFTER DEADLINE DATE</small>
              </h6>
            </div>

            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  {{  Form::label('survey_entry', 'Survey Entry',['class' => 'lbl center-align'])}}
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  {{  Form::label('survey_entry', 'Before Start')}}
                  {{  Form::text(
                    'survey_entry_before', @$round->survey_entry_before,
                    [
                    'id' => 'round_code',
                    'class' => 'form-control',
                    ])
                  }}
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  {{  Form::label('survey_entry', 'After Deadline')}}
                  {{  Form::text(
                    'survey_entry_after', @$round->survey_entry_after,
                    [
                    'id' => 'round_code',
                    'class' => 'form-control',
                    ])
                  }}
                </div>
              </div>
            </div>

            <div class="box-header with-border custom-header custom-border">
              <h6 class="box-title">
                <small>Alert for FieldRep</small>
              </h6>
            </div>


            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>

                    {{  Form::checkbox(
                      'is_bulletin',1,
                      (@$round->is_bulletin == 1) ? true : false,
                      [
                      'class' => 'minimal custom_radio',
                      'id'  =>  'is_bulletin',
                      ])
                    }}
                    <span class="chk_label">
                      Create a pop up alert for FieldRep upon login
                    </span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row" id="bulletin">
              <div class="col-md-12">
                <div class="form-group">
                  {{  Form::label('experience', 
                    'Enter an alert text'
                    ) }}
                  {{  Form::textarea('bulletin_text',@$round->bulletin_text,
                    [
                    'id' => 'bulletin_text',
                    'class' => 'form-control',
                    'rows' => '3,'
                    ])
                  }}
                </div>
              </div>
            </div>

          </div><!-- /.box-body -->
          <div class="box-footer">

            <div class="pull-right">
              <div class="pull-right">
                {{  Form::submit('Save',
                  [
                  'id' => 'create',
                  'class' => 'btn btn-primary pull-right'
                  ])
                }}

              </div>
              <div class="col-md-1 pull-right">
                <a href='{{ URL::previous() }}' id="cancel" class="btn btn-default pull-right">Cancel</a>
              </div>

            </div>
            @if(@$round->id != '')
            <h6><small>Created {{ @$round->created }} | Last modified {{ @$round->updated }} </small></h6>              
            @endif
          </div>
        </div><!-- /.box -->
        {{ Form::close() }}
      </div><!-- /.col-md-6 -->

      <div class="col-md-6">
        @if(@$round->id != '')
        @include('admin.rounds.round_fieldrep_criteria',['criteria' => @$criteria])
        @include('admin.assignments.assignment_instructions',['sites'=>@$sites,'round_id'=>$round->id])
        @if(@$round->template_id != NULL)
        @include('admin.assignments.round_assignments',['sites'=>@$sites,'round'=>$round, 'fieldreps' => @$fieldreps])
        @endif 
        @endif
      </div>

    </div><!-- /.row -->
  </section>
</div>
@stop

@section('custom-script')

<script type="text/javascript">
  $(document).ready(function () {
    $("[data-mask]").inputmask();    

    $('#start_date').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
    },function(chosen_date) {
      initDates(chosen_date.format('DD MMM YYYY'));
    });
    initDates();

    $('#is_bulletin').on('ifChecked', function(event){
      $('#bulletin').show();
    });
    $('#is_bulletin').on('ifUnchecked', function(event){
      $('#bulletin_text').val('');
      $('#bulletin').hide();
    });

    var checked = $("#is_bulletin").parent('[class*="icheckbox_minimal-blue"]').hasClass("checked");
    if(checked){
     $('#bulletin').show();
   }
   else{
    $('#bulletin_text').val('');
    $('#bulletin').hide();
  }

  $("#start_time, #deadline_time").timepicker({
    showInputs: false,
    showMeridian: true,
    minuteStep: 5,
  });
  
  $('.modal').on('shown.bs.modal', function() {
    $(this).find('[autofocus]').focus();
  });

});

  function initDates(){
    var min_date = arguments.length <= 0 || arguments[0] === undefined ? $('#start_date').val() : arguments[0];
    var max_date = arguments.length <= 1 || arguments[1] === undefined ? $('#deadline_date').val() : arguments[1];

      // min_dates  = new Date(min_date);
      // max_dates  = new Date(max_date);
      // if(min_dates > max_dates){
      //   // if(min_date > max_date){
      //    max_date = min_date;
      //  }
      $('#deadline_date').daterangepicker({
        'singleDatePicker': true,
        "showDropdowns": true,
        "minDate": min_date,
      },function(chosen_date) {
        initScheduleDate(min_date,chosen_date.format('DD MMM YYYY'));
      });
      initScheduleDate(min_date, max_date);

    }

    function initScheduleDate(){
      var min_date = arguments.length <= 0 || arguments[0] === undefined ? $('#start_date').val() : arguments[0];
      var max_date = arguments.length <= 1 || arguments[1] === undefined ? $('#deadline_date').val() : arguments[1];

      min_dates  = new Date(min_date);
      max_dates  = new Date(max_date);

      if(min_dates > max_dates){
       max_date = min_date;
     }

     $('#schedule_date').daterangepicker({
      'singleDatePicker': true,
      "showDropdowns": true,
      "minDate": min_date,
      "maxDate": max_date,

    });
   }
 </script>
 @append