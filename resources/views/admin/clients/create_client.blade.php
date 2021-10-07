@extends('app')
@section('page-title') | {{  (@$client->id) ? 'Client Edit' : 'Client Add' }} @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        {{ Form::open(
          [
          'method'=>'post',
          'url' => route('store.client'), 
          'enctype'  =>  "multipart/form-data"]) }}

          {{  Form::hidden('id',@$client->id)  }}
          {{  Form::hidden('url',URL::previous())  }}
          <div class="box">
            <div class="box-header with-border">
              <i class="fa fa-user"></i>
              <h6 class="box-title">
                {{  (@$client->id) ? 'Client Edit' : 'Client Add' }}
              </h6>
            </div>

            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  @include('includes.success')
                  @include('includes.errors')
                </div>
              </div>
              @if(@$contact_count <= 0 && @$client->id)
              <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                You must add at least one client contact before you can create any project for this client.                      
              </div> 
              @endif 
              <div class="row">  
                <div class="col-md-6">
                  <div class="form-group">
                    {{  Form::label('client_code', 'Client Code')}}
                    {{  Form::text(
                      'client_code',(@$client->id) ?  format_code(@$client->id) : format_code(@$client_id),
                      [
                      'id' => 'client_code',
                      'disabled' => 'disabled',
                      'class' => 'form-control',
                      ])
                    }}
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    {{  Form::label('client_abbrev', 'Abbrev')}}
                    {{  Form::text(
                      'client_abbrev',@$client->client_abbrev,
                      [
                      'id' => 'client_abbrev',
                      'class' => 'form-control',
                      ])
                    }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('client_name', 'Client Name',['class' => 'mandatory'])}}
                    {!!  Form::text(
                      'client_name',@$client->client_name,
                      [
                      'id' => 'client_name',
                      'class' => 'form-control',              
                      ])
                      !!}
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      {{  Form::label('logo', 'Logo') }}
                      {{  Form::file(
                        'client_logo',
                        [
                        'id' => 'client_logo',
                        'class' => 'file-loading',
                        //'data-allowed-file-extensions'  =>  '["gif", "jpg"]',
                        'data-image' => (@$client->client_logo) ? @$client->client_logo : '',
                        ]) 
                      }}
                      {!! Form::hidden('client_logo_name',"",['id'=>'client_logo_name']) !!}
                      <p class="help-block">Upload your logo. (Only jpeg, jpg, png files are allowed.)</p>
                      {{-- <p class="help-block">Upload your logo.</p> --}}
                    </div>
                  </div>
                </div>

                @if(@$client->id)
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      {{  Form::label('status', 'Status') }}
                      {{  Form::select('status', array(
                        '1'  => 'Active',
                        '0'  => 'Inactive',

                        ), @$client->status,
                      [
                      'id' => 'status',
                      'class' => 'form-control',
                      ])
                    }}
                  </div>
                </div>
              </div>
              @endif

              <div class="clear-both"></div>
              <div class="row">

                <div class="col-md-12">
                  <div class="form-group">
                    {{  Form::label('notes', 'Notes') }}
                    {{  Form::textarea('notes',@$client->notes,
                      [
                      'id' => 'notes',
                      'class' => 'form-control',
                      'rows' => 3,
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
              @if(@$client->id != '')
              <h6><small>Created {{@$client->created }} | Last modified {{ @$client->updated }} </small></h6>  
              @endif
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

    // changeServiceLevel();

    // $('#ship_via').on('change',function(){
    //   var ship_via = $(this).val();
    //   changeServiceLevel(ship_via);
    // });

    //var SelectedImage = $('#client_logo').attr('data-image');
    var client_logo_src = $('#client_logo').attr('data-image');
    if(client_logo_src == ""){
      client_logo_src = getDefaultClientLogoImage();
    }
    //$("#client_logo").fileinput('destroy');

    var fileinput_options = getFileInputOptions();
    $.extend( fileinput_options, {
      allowedFileExtensions: ["jpg",'png','jpeg'],
      initialPreview: client_logo_src, 
      initialPreviewConfig: 
      [ 
      {caption: "{{ @$client->name }}", filename: client_logo_src, showDelete: false} ,
      ]
    });
    $('#client_logo').fileinput(fileinput_options);

    $('#client_logo').on('filecleared', function(event) {
      var client_logo_src = getDefaultClientLogoImage();
      var fileinput_options = getFileInputOptions();
      $.extend( fileinput_options, {
        allowedFileExtensions: ["jpg",'png','jpeg'],
        initialPreview: client_logo_src, 
        initialPreviewConfig: 
        [ 
        {caption: "", filename: client_logo_src, showDelete: false} ,
        ]
      });
      $('#client_logo').fileinput('refresh',fileinput_options);
      $('#client_logo_name').val('user-thumbnail.png');
    });

    $(this).find('[autofocus]').focus();


  });/* . dccument ready over*/

      // function changeServiceLevel(){
      //   ship_via = $('#ship_via').val();
      //   if(ship_via == 1){
      //     $('#service_level').prop('disabled', false);
      //     $('#shipping_acc_number').prop('disabled', false);
      //   }else{
      //     $('#service_level').prop('disabled', true);
      //     $('#shipping_acc_number').prop('disabled', true);
      //   }
      // }

    </script>

    @append