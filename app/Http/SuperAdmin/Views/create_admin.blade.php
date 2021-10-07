@extends('layouts.superadmin.app')
@section('page-title') | @lang('messages.create_admin') @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        {{ Form::open(
          [
            'method'=>'post',
            'url' => route('create.admin'),
            'enctype'  =>  "multipart/form-data"
          ]) 
        }}

        <div class="box">
          <div class="box-header with-border">
            <i class="fa fa-user"></i>
            <h6 class="box-title">
              @lang('messages.create_admin')
            </h6>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                @include('includes.success')
                @include('includes.errors')
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {{  Form::label('client_name', trans('messages.admin_name'), ['class' => 'mandatory'])}}
                  {{  Form::text(
                    'name', '',
                    [
                      'id' => 'cliedmin_name',
                      'class' => 'form-control',
                    ])
                  }}
                </div>
              </div>
            </div>

            <div class="row">  
              <div class="col-md-12">
                <div class="form-group">
                  {{  Form::label('client_code', trans('messages.client_code'), ['class' => 'mandatory'])}}
                  {{  Form::text(
                    'client_code', '',
                    [
                      'id' => 'client_code',
                      'class' => 'form-control',
                      'style' =>  'text-transform:uppercase',
                    ])
                  }}
                </div>
              </div>
            </div>


            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  {{  Form::label('email', trans('messages.email'), ['class' => 'mandatory'])}}
                  {{  Form::text(
                    'email', '',
                    [
                      'id' => 'email',
                      'class' => 'form-control',              
                    ])
                  }}
                </div>
              </div>

              
              <div class="col-md-6">
                <div class="form-group">
                  {{  Form::label('password', trans('messages.password'), ['class'=>'mandatory']) }}
                  {{  Form::password('password', 
                    [
                      'id'    =>  'password',
                      'class' =>  'form-control',
                    ])
                  }}                  
                </div>                                    
              </div>

            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  {{  Form::label('messages.subscription_start_from', trans('messages.subscription_start_from'), ['class' => 'mandatory']) }}
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
                  {{  Form::label('subscription_end_on', trans('messages.subscription_end_on'), ['class'=>'mandatory']) }}
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

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {{  Form::label('logo', trans('messages.logo')) }}
                  <div class="custom-file-input custom-size">
                    {{  Form::file(
                      'logo',
                      [
                        'id' => 'logo',
                        'class' => 'file-loading',
                        'data-show-upload'  => 'true',
                        'data-allowed-file-extensions'  =>  '["jpg"]',
                        'data-image' => '',
                      ]) 
                    }}
                  </div>
                  <p class="help-block">@lang('messages.upload_your_logo')</p>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {{  Form::label('TimeZone', trans('messages.select_timezone'), ['class'=>'mandatory']) }}
                  {{  Form::select(
                    'timezone', ['' => 'Select TimeZone'] + AppHelper::getTimeZone(), '',
                    [
                      'id'    =>  'timezone',
                      'class' =>  'form-control',
                    ])
                  }}
                  <div id="new_timezone_date"></div>
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

        <!-- show contacts -->
        @if(@$client->id != '')

        @include('admin.contacts.contacts',
          [
            'entity_type'=>$entity_type,  
            'contact_types => $contact_types',
            'reference_id'=>$client->id
            ])

            @endif
            <!-- / show contacts -->
          </div>



        </div>
      </section>
    </div>
    @stop

    @section('custom-script')

    <script type="text/javascript">
      $(document).ready(function () {

        var SelectedImage = $('#logo').attr('data-image');
        var APP_URL = $('meta[name="_base_url"]').attr('content');
        $("#logo").fileinput('destroy');

        if (SelectedImage != null && SelectedImage != '') {
          $("#logo").fileinput('refresh', {
            initialPreview: [
            '<img src="' + SelectedImage + '" class="file-preview-image" style="height:100px;">'
            ],
            initialPreviewAsData: false,
            initialPreviewConfig: [
            {key: 1, showDelete: true}
            ],
            overwriteInitial: true,
            deleteUrl: APP_URL+'/delete-logo/',
            showUpload: false,
            showRemove: false,
            autoReplace: true,
            maxFileCount: 1,
            fileActionSettings: {
              showDrag: false,
              showZoom: false,
              showUpload: false,
            },
          });
          $('.kv-file-remove').prop('disabled', false).removeClass('disabled');
          $('.fileinput-remove').addClass('hide');
        } else {
          $("#logo").fileinput('clear');
          $("#logo").fileinput('refresh', {
            showUpload: false,
            showRemove: false,
            autoReplace: true,
            maxFileCount: 1,
            fileActionSettings: {
              showDrag: false,
              showZoom: false,
              showUpload: false,
            },
          });
        }


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

      });/* . dccument ready over*/




    </script>

    @append