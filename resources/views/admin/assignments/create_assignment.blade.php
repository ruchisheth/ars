@extends('app')
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                {{ Form::open(array('method'=>'post',
                'enctype'  =>  "multipart/form-data")) }}
                {{  Form::hidden('id')  }}
                <div class="box">
                    <div class="box-header with-border">
                       <i class="fa fa-check-square-o"></i>
                        <h6 class="box-title">Assignment Add</h6>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                @if($errors->any())
                                <sapn class="text text-danger">
                                    <strong><i class="fa fa-times-circle-o"></i> 
                                        {{$errors->first()}}
                                    </strong>
                                </sapn>
                                @endif
                            </div>
                        </div>

                        <div class="row">  
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{  Form::label('project_code', 'Project Code')}}
                                    {{  Form::text('project_code', '',
                                    [
                                    'id' => 'client_code',
                                    'disabled' => 'disabled',
                                    'class' => 'form-control',
                                    'placeholder' => 'Project Code',
                                    ])
                                }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {{  Form::label('project_name', 'Project Name')}}
                                {{  Form::text('project_name', '',
                                [
                                'id' => 'project_name',
                                'class' => 'form-control',
                                'placeholder' => 'Enter Name',
                                ])
                            }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{  Form::label('project_type', 'Project Type') }}
                            {{  Form::select('project_type',
                            array(
                            ''   => 'Select Project Type'
                            ), '',
                            [
                            'id' => 'project_type',
                            'class' => 'form-control',
                            ])
                        }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{  Form::label('client', 'Client') }}
                        {{  Form::select('client',
                        array(
                        ''   => 'Select Client'
                        ), '',
                        [
                        'id' => 'client',
                        'class' => 'form-control',
                        ])
                    }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{  Form::label('per_site', 'Max Per Site')}}
                    {{  Form::text('per_site', '',
                    [
                    'id' => 'per_site',
                    'class' => 'form-control',
                    'readonly' => 'true'
                    ])
                }}
                </div>
            </div>

            <div class="col-md-6">
            <div class="form-group">
                {{  Form::label('job_number', 'Job Number')}}
                {{  Form::text('job_number', '',
                [
                'id' => 'job_number',
                'class' => 'form-control'

                ])
            }}
        </div>
    </div>
    </div>
    <div class="row">

    <div class="col-md-6">
        <div class="form-group">
            {{  Form::label('division', 'Division')}}
            {{  Form::select('division',
            array(
            ''   => 'Select Division'
            ), '',
            [
            'id' => 'division',
            'class' => 'form-control',
            ])
        }}
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        {{  Form::label('store_checkin', 'Store Checkin')}}
        {{  Form::select('store_checkin',
        array(
        ''   => 'Select Store Checkin'
        ), '',
        [
        'id' => 'store_checkin',
        'class' => 'form-control',
        ])
    }}
</div>
</div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
        
            {{  Form::label('can_schedule', 'Rep. can Schedule?')}}
        
            <label>
                <input type="checkbox" class="minimal" checked>
            </label>
      </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{  Form::label('can_schedule', 'Status') }}        
            <label>
              <input type="radio" name="status" class="minimal" checked>
              Active
          </label>
          <label>
              <input type="radio" name="status" class="minimal">
              Deactive
          </label>
      </div>
    </div>
</div>
<div class="box-header with-border custom-header">
    <h6 class="box-title">
        <small>CONTACTS</small>
    </h6>
</div>
<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            {{  Form::label('primary_contact', 'Primary Contact')}}
            {{  Form::select('primary_contact',
            array(
            ''   => 'Select Client'
            ), '',
            [
            'id' => 'primary_contact',
            'class' => 'form-control',
            ])
        }}
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        {{  Form::label('secondary_contact', 'Secondary Contact')}}
        {{  Form::select('secondary_contact',
        array(
        ''   => 'Select Client'
        ), '',
        [
        'id' => 'secondary_contact',
        'class' => 'form-control',
        ])
    }}
</div>
</div>
<div class="col-md-4">
    <div class="form-group">
        {{  Form::label('billing_contact', 'Billing Contact')}}
        {{  Form::select('billing_contact',
        array(
        ''   => 'Select Client'
        ), '',
        [
        'id' => 'billing_contact',
        'class' => 'form-control',
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
        {{  Form::submit('Cancel',
        [
        'id' => 'cancel',
        'class' => 'btn btn-default pull-right'
        ])
    }}
</div>

</div>                                                                                                    
</div><!-- /.box -->
{{ Form::close() }}
</div>


</div>
<div class="col-md-6">

    <!-- show rounds -->
    @if(@$id == '')

    @include('rounds.rounds')

    @endif
    <!-- / show rounds -->
</div>



</div>
</section>
</div>
@stop

@section('custom-script')

<script type="text/javascript">
    $(document).ready(function () {

        //Initialize Select2 Elements
        // $(".select2").select2();

        $(".select2").select2();

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
    });

</script>

@append