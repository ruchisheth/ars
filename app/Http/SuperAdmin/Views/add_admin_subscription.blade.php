@extends('layouts.superadmin.app')
@section('page-title') | @lang('messages.admin_subscription') @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        {{ Form::open(
          [
            'method' => 'post',
            'url' => route('superadmin.add_subscripition', ['nIdAdmin' => @$oAdmin->id_admin]),
          ]) 
        }}
        {{  Form::hidden('id', @$oAdmin->id_admin)  }}
        <div class="box">
          <div class="box-header with-border">
            <i class="fa fa-user"></i>
            <h6 class="box-title">
              @lang('messages.add_subscription')
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
              <label>@lang('messages.admin_name'): {{ $oAdmin->name }}</label><br>
            </div>
            <div class="box-header with-border custom-header"></div>


            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  {{  Form::label('email', 'Start Date',['class' => 'mandatory']) }}
                  {{  Form::text(
                    'start_date', '',
                    [
                      'id' => 'start_date',
                      'class' => 'form-control',
                      'autocomplete' => 'off'
                    ])
                  }}
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  {{  Form::label('end_date', 'End Date',['class'=>'mandatory']) }}
                  {{  Form::text(
                    'end_date', '',
                    [
                      'id'    => 'end_date',
                      'class' => 'form-control',
                      'autocomplete' => 'off'
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
                <a href="{{ URL::previous() }}" id="cancel" class="btn btn-default pull-right">Cancel</a>  
              </div>
            </div>
          </div>
          {{ Form::close() }}
        </div><!-- /.box -->
      </div>
      <div class="col-md-6">
        <div class="box">
          <div class="box-header with-border">
            <i class="fa fa-user"></i>
            <h3 class="box-title">{{ trans('messages.subscriptions') }}</h3>
          </div>
          <div class="box-body">                           
            <div class="box-header with-border custom-header"></div>
            <div class="table-responsive">
              <table id="subscriptions-grid" class="table table-bordered table-hover" width="100%">
                <thead>
                  <tr>
                    <th>@lang('messages.start_date')</th>
                    <th>@lang('messages.end_date')</th>
                    <th>@lang('messages.status')</th>
                  </tr>
                </thead>                        
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>
@stop

@section('custom-script')

<script type="text/javascript">
  $(document).ready(function () {

    $('#subscriptions-grid').DataTable({
        serverSide: true,
        "paging": false,
        "bFilter": false,
        "bInfo": false,
        "ordering": false,
        ajax: {
          url: "{{ route('superadmin.admin_subscripition', ['nIdAdmin' => @$oAdmin->id_admin ]) }}",
          type: 'POST',
        },
        columns: [
        {data: 'subscription_start_on',   name: 'subscription_start_on'},
        {data: 'subscription_end_on',     name: 'subscription_end_on'},
        {data: 'status',                  name: 'status'},
        ],
    })

    $('#start_date').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
    },function(chosen_date) {
      initDates(chosen_date.format('DD MMM YYYY'));
    });
    initDates();

    function initDates(){
      var min_date = arguments.length <= 0 || arguments[0] === undefined ? $('#start_date').val() : arguments[0];

      $('#end_date').daterangepicker({
        'singleDatePicker': true,
        "showDropdowns": true,
        "minDate": min_date,
      });
    }
  });
</script>

@stop