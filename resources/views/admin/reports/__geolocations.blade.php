@extends('app')
@section('page-title') | GeoLocations @stop
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
      <div class="box-primary">
        <div class="box-header with-border">
          <i class="fa fa-location-arrow"></i>
          <h3 class="box-title">GeoLocations</h3>
          @include('includes.success')
          @include('includes.errors')          
        </div><!-- /.box-header -->
        <div class="box-header with-border "></div>
        <div class="box">
            <div class="box-header">
              <h6 class="box-title">
                Site GeoLocations
              </h6>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-down"></i></button>

              </div>
            </div>
        <div class="box-body">
       
          @if($geocoding > 0)
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    {{-- {{  Form::label('refresh geocoding', 'Refresh Geocoding') }} --}}
                     {{-- {{  Form::submit('Refresh',
                      [
                      'id' => 'refresh_geocoding',
                      'class' => 'btn btn-primary'
                      ])
                    }} --}}
                     <div class="alert alert-warning alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                      <b><i>{{$geocoding}}</i></b> Site with Invalid GeoLocations, Please Refresh
                      <button class="btn btn-default refresh_geocoding " type="submit" name="refresh_geocoding" data-id="" value="delete" title="delete">Refresh</button>
                    </div>

                    {{-- <button class="btn btn-default" type="submit" name="refresh_geocoding" data-id="" value="delete" title="delete">Refresh</button> --}}
                  </div>
                </div>
              </div>
              @endif          
     <div class="box-header with-border custom-header"></div>
     <div class="table-responsive">
       <table id="site-geolocations" class="table table-bordered table-hover" width="100%">
        <thead>
          <tr>
            <th>Code</th>
            <th>Location</th>
            <th>Lat</th>
            <th>Long</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div><!-- /.box-body -->
  </div>
</div><!-- /.box -->
</div>
{{-- <div class="box">
            <div class="box-header with-border">
              <h6 class="box-title">
                Fieldrep GeoLocations
              </h6>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

              </div>
            </div>
        <div class="box-body">       
              @if($geocoding > 0)
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">                   
                     <div class="alert alert-warning alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                      <b><i>{{$geocoding}}</i></b> Site with Invalid GeoLocations, Please Refresh
                      <button class="btn btn-default refresh_geocoding " type="submit" name="refresh_geocoding" data-id="" value="delete" title="delete">Refresh</button>
                    </div>
                  </div>
                </div>
              </div>
              @endif          
     <div class="box-header with-border custom-header"></div>
     <div class="table-responsive">
       <table id="fieldrep-geolocations" class="table table-bordered table-hover" width="100%">
        <thead>
          <tr>
            <th>Code</th>
            <th>Location</th>
            <th>Lat</th>
            <th>Long</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div><!-- /.box-body -->
  </div> --}}
</section>
</div>
@stop

@section('custom-script')

<script type="text/javascript">
  var locationsTable ='';
  $(document).ready(function(){

    locationsTable = $('#site-geolocations').DataTable( {
      "serverSide": true,
      "order": [ 0, "desc" ],
      ajax: {
        url: '{{ route("show.geolocations.post") }}',
        type: 'POST'        
      },
      columns: [
      {data: 'site_code', name: 's.site_code'},
      {data: 'location', name: 's.location'},
      {data: 'lat', name: 's.lat', orderable: false, searchable: false},
      {data: 'long', name: 's.long', orderable: false, searchable: false},
      ], "aoColumnDefs": [          
      { "sWidth": "7%", "targets": [0] },      
      ],
    });

  });

  $(document).on('click', 'button[name="refresh_geocoding"]', function(e){
          e.preventDefault();
          var url = APP_URL + '/refresh-geocodes';
          var form = $("#settting_save");
          var geocode_error = $('#geocode_error');

        $.ajax({
            type: "POST",
            url: url,
            beforeSend: function( xhr ) {
              $('#overlay').removeClass('hide');
            },
            complete: function( xhr ){
              $('#overlay').addClass('hide');
            },
            success: function (data) {
                DisplayMessages(data['message'],data['type']);
                $('.alert-warning').hide().delay(1000).fadeOut('slow');
                //window.location.reload();
            },
            error: function (jqXHR, exception) {
              var Response = jqXHR.responseText;
              console.log(Response);
              // return false;
              //  $.each(Response.message.message, function(index, message){
              //   var HTML = "<li>Invalid address in Site Code"+message+"</li>";
              //   $(geocode_error).append(HTML);
              // });

              ErrorBlock = $(form).find('.alert-danger');
              Response = $.parseJSON(Response);
              DisplayErrorMessages(Response, ErrorBlock, 'div',null,false);
          }
        });
          
        });
</script>

@stop