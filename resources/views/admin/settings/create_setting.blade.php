@extends('app')
@section('page-title') | Settings @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="box box-default">
          <div class="box-header with-border">
            <i class="fa fa-wrench"></i>
            <h3 class="box-title">General Settings</h3>
            <div class="box-tools pull-right">
            </div>
          </div><!-- /.box-header -->
          <div class="box-body">
            {{  Form::open(
              array('method'=>'post',
                'url' => route('store.setting'),
                'id' => 'settting_save',
                'enctype'  =>  "multipart/form-data"
                )) 
              }}
              {{  Form::hidden('id',@$setting->id)  }}
              <div class="row">

              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('title', 'Site Title') }}
                    {{  Form::text('title',@$setting->title,
                      [
                      'id' => 'title',
                      'class' => 'form-control',
                      ])
                    }}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">

                  <div class="form-group">
                    {{  Form::label('logo', 'Logo') }}
                    {{  Form::file(
                      'logo',
                      [
                      'id' => 'logo',
                      'class' => 'file-loading',
                      'data-show-upload'  => 'true',
                      'data-allowed-file-extensions'  =>  '["gif", "jpg"]',
                      'data-image' => (@$setting->logo) ? @$setting->logo : '',
                      ]) 
                    }}
                    <p class="help-block">Upload your logo.</p>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                  {{  Form::label('syi_api_key', 'API Key for Submit Your Invoice (SYI)') }}
                    {{  Form::text('syi_api_key',@$setting->syi_api_key,
                      [
                      'id' => 'title',
                      'class' => 'form-control',
                      ])
                    }}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('invitaton_link', 'Invitaton Link for Submit Your Invoice (SYI)') }}
                    {{  Form::text('invitaton_link',@$setting->invitaton_link,
                      [
                      'id' => 'title',
                      'class' => 'form-control',
                      ])
                    }}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('logo', 'Theme Color') }}
                    <br>
                    <div class="btn-group skin-button" data-toggle="buttons">
                      <label class="btn {{ (@$clients_settings->theme_color == 'blue') ? 'btn-active' : ''}} active">
                        {{  Form::radio(
                          'theme_color','blue')
                        }}
                        <a href='javascript:void(0);' data-skin='skin-blue' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>
                          <div><span style='display:block; width: 20%; float: left; height: 7px; background: #367fa9;'></span><span class='bg-light-blue' style='display:block; width: 80%; float: left; height: 7px;'></span></div>
                          <div><span style='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>
                        </a>
                      </label>
                    </div>
                    <div class="btn-group skin-button" data-toggle="buttons">
                      <label class="btn {{ (@$clients_settings->theme_color == 'black') ? 'btn-active' : ''}} active">
                        {{  Form::radio(
                          'theme_color','black')
                        }}
                        <a href='javascript:void(0);' data-skin='skin-black' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>
                          <div style='box-shadow: 0 0 2px rgba(0,0,0,0.1)' class='clearfix'><span style='display:block; width: 20%; float: left; height: 7px; background: #fefefe;'></span><span style='display:block; width: 80%; float: left; height: 7px; background: #fefefe;'></span></div>
                          <div><span style='display:block; width: 20%; float: left; height: 20px; background: #222;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>
                        </a>
                      </label>
                    </div>
                    <div class="btn-group skin-button" data-toggle="buttons">
                      <label class="btn {{ (@$clients_settings->theme_color == 'purple') ? 'btn-active' : ''}} active">
                        {{  Form::radio(
                          'theme_color','purple')
                        }}
                        <a href='javascript:void(0);' data-skin='skin-purple' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>
                          <div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-purple-active'></span><span class='bg-purple' style='display:block; width: 80%; float: left; height: 7px;'></span></div>
                          <div><span style='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>
                        </a>
                      </label>
                    </div>
                    <div class="btn-group skin-button" data-toggle="buttons">
                      <label class="btn {{ (@$clients_settings->theme_color == 'green') ? 'btn-active' : ''}} active">
                        {{  Form::radio(
                          'theme_color','green')
                        }}
                        <a href='javascript:void(0);' data-skin='skin-green' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>
                          <div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-green-active'></span><span class='bg-green' style='display:block; width: 80%; float: left; height: 7px;'></span></div>
                          <div><span style='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>
                        </a>
                      </label>
                    </div>
                    <div class="btn-group skin-button" data-toggle="buttons">
                      <label class="btn {{ (@$clients_settings->theme_color == 'red') ? 'btn-active' : ''}} active">
                        {{  Form::radio(
                          'theme_color','red')
                        }}
                        <a href='javascript:void(0);' data-skin='skin-red' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>
                          <div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-red-active'></span><span class='bg-red' style='display:block; width: 80%; float: left; height: 7px;'></span></div>
                          <div><span style='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>
                        </a>
                      </label>
                    </div>
                    <div class="btn-group skin-button" data-toggle="buttons">
                      <label class="btn {{ (@$clients_settings->theme_color == 'yellow') ? 'btn-active' : ''}} active">
                        {{  Form::radio(
                          'theme_color','yellow')
                        }}
                        <a href='javascript:void(0);' data-skin='skin-yellow' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>
                          <div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-yellow-active'></span><span class='bg-yellow' style='display:block; width: 80%; float: left; height: 7px;'></span></div>
                          <div><span style='display:block; width: 20%; float: left; height: 20px; background: #222d32;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>
                        </a>
                      </label>
                    </div>
                  </div>    
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="btn-group skin-button" data-toggle="buttons">
                      <label class="btn {{ (@$clients_settings->theme_color == 'blue-light') ? 'btn-active' : ''}} active">
                        {{  Form::radio(
                          'theme_color','blue-light')
                        }}
                        <a href='javascript:void(0);' data-skin='skin-blue-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>
                          <div><span style='display:block; width: 20%; float: left; height: 7px; background: #367fa9;'></span><span class='bg-light-blue' style='display:block; width: 80%; float: left; height: 7px;'></span></div>
                          <div><span style='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>
                        </a>
                      </label>
                    </div>
                    <div class="btn-group skin-button" data-toggle="buttons">
                      <label class="btn {{ (@$clients_settings->theme_color == 'black-light') ? 'btn-active' : ''}} active">
                        {{  Form::radio(
                          'theme_color','black-light')
                        }}
                        <a href='javascript:void(0);' data-skin='skin-black-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>
                          <div style='box-shadow: 0 0 2px rgba(0,0,0,0.1)' class='clearfix'><span style='display:block; width: 20%; float: left; height: 7px; background: #fefefe;'></span><span style='display:block; width: 80%; float: left; height: 7px; background: #fefefe;'></span></div>
                          <div><span style='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>
                        </a>
                      </label>
                    </div>
                    <div class="btn-group skin-button" data-toggle="buttons">
                      <label class="btn {{ (@$clients_settings->theme_color == 'purple-light') ? 'btn-active' : ''}} active">
                        {{  Form::radio(
                          'theme_color','purple-light')
                        }}
                        <a href='javascript:void(0);' data-skin='skin-purple-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>
                          <div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-purple-active'></span><span class='bg-purple' style='display:block; width: 80%; float: left; height: 7px;'></span></div>
                          <div><span style='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>
                        </a>
                      </label>
                    </div>
                    <div class="btn-group skin-button" data-toggle="buttons">
                      <label class="btn {{ (@$clients_settings->theme_color == 'green-light') ? 'btn-active' : ''}} active">
                        {{  Form::radio(
                          'theme_color','green-light')
                        }}
                        <a href='javascript:void(0);' data-skin='skin-green-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>
                          <div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-green-active'></span><span class='bg-green' style='display:block; width: 80%; float: left; height: 7px;'></span></div>
                          <div><span style='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>
                        </a>
                      </label>
                    </div>
                    <div class="btn-group skin-button" data-toggle="buttons">
                      <label class="btn {{ (@$clients_settings->theme_color == 'red-light') ? 'btn-active' : ''}} active">
                        {{  Form::radio(
                          'theme_color','red-light')
                        }}
                        <a href='javascript:void(0);' data-skin='skin-red-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>
                          <div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-red-active'></span><span class='bg-red' style='display:block; width: 80%; float: left; height: 7px;'></span></div>
                          <div><span style='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>
                        </a>
                      </label>
                    </div>
                    <div class="btn-group skin-button" data-toggle="buttons">
                      <label class="btn {{ (@$clients_settings->theme_color == 'yellow-light') ? 'btn-active' : ''}} active">
                        {{  Form::radio(
                          'theme_color','yellow-light')
                        }}
                        <a href='javascript:void(0);' data-skin='skin-yellow-light' style='display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)' class='clearfix full-opacity-hover'>
                          <div><span style='display:block; width: 20%; float: left; height: 7px;' class='bg-yellow-active'></span><span class='bg-yellow' style='display:block; width: 80%; float: left; height: 7px;'></span></div>
                          <div><span style='display:block; width: 20%; float: left; height: 20px; background: #f9fafc;'></span><span style='display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;'></span></div>
                        </a>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

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
                    <a href="#" id="cancel" class="btn btn-default pull-right">Cancel</a>
                  </div>

                </div>                                                                        
                @if(@$setting->id != '')
                <h6><small>Created {{ @$setting->created }} | Last modified {{ @$setting->updated }} </small></h6>
                @endif                            
              </div><!-- /.box -->
              {{ Form::close() }}
            </div><!-- /.box-body -->

          </div><!-- /.box -->


        </div><!-- /.box -->
        @include('admin.settings.lists')
      </div>
    </section>
  </div>
  @stop
  @section('custom-script')

  <script type="text/javascript">
    $(document).ready(function () {
      //$('input:text[value=""]:visible:enabled:first').focus();
      $("#title").focus();
      /* Input Mask */
      $("[data-mask]").inputmask();

        // Initialize Select2 Elements
        $(".select2").select2();

        var SelectedImage = $('#logo').attr('data-image');
        var APP_URL = $('meta[name="_base_url"]').attr('content');
        $("#logo").fileinput('destroy');

        if (SelectedImage != null && SelectedImage != '') {
          $("#logo").fileinput('refresh', {
            'initialPreview': [
            '<img src="' + SelectedImage + '" class="file-preview-image" style="height:100px;">'
            ],
            'initialPreviewAsData': false,
            initialPreviewConfig: [
            {key: 1, showDelete: true}
            ],
            'overwriteInitial': true,
                // 'deleteUrl': APP_URL+'/delete-logo/',
                'deleteUrl': '{!! url('delete-setting-logo',@$setting->id) !!}',
                'showUpload': false,
                'showRemove': false,
                'autoReplace': true,
                'maxFileCount': 1,
                'data-show-upload': false,
              });
          $('.kv-file-remove').prop('disabled', false).removeClass('disabled');
          $('.fileinput-remove').addClass('hide');
        } else {
          $("#logo").fileinput('clear');
          $("#logo").fileinput('refresh', {
            showUpload: false,
            showRemove: false,
            'autoReplace': true,
            'maxFileCount': 1,
            'data-show-upload': false,
          });
        }

        $(".skin-button :input").change(function(e) {
          console.log($(".skin-button").find('.btn'));

          $(".skin-button").find('.btn').removeClass('btn-active');
          $(".skin-button").find('.btn').removeClass('focus');
          $(this).closest('.btn').addClass('btn-active');
        });


        
      });/* . dccument ready over*/
    </script>

    @append