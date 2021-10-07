@extends('app')
@section('page-title') | Admin Dashboard @stop
@section('content')
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">

    @if(@$counts['clients']== 0)
    <div class="error-page">
      <div class="error-content">
        <h3><i class="fa fa-warning text-yellow"></i> Oops! No Data Available.</h3>
        <p>
          {{-- @if($entity != 'survey')It looks like you haven't created any {{ strtolower(@$entity) }} yet. @if($url != '') Click {{ Html::linkRoute(@$url,'here') }} to create your first {{ strtolower(@$entity)  }}.@endif @endif --}}
          It looks like you haven't created any client yet. Click <a href="{{url('clients-edit')}}" >here</a> to create your first client.
        </p>
      </div>
    </div>
    @else 

    <div class="row">
      <div class="col-md-9">
        @if($site_geocoding > 0)
        <div class="alert alert-warning alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <i class="icon fa fa-warning"></i><b><i>{{$site_geocoding}}</i></b> Site with Invalid GeoLocations, Please go to  <a href="{{route("show.geolocations.get")}}" class="btn btn-default btn-flat geo_btn">Site Geolocations</a>   and Refresh.                   
        </div>
        @endif
        @if($fieldrep_geocoding > 0)
        <div class="alert alert-warning alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <i class="icon fa fa-warning"></i><b><i>{{$fieldrep_geocoding}}</i></b> Fieldrep with Invalid GeoLocations, Please go to  <a href="{{route("show.fieldrep_geolocations.get")}}" class="btn btn-default btn-flat geo_btn">Fieldrep Geolocations</a> and Refresh.                      
        </div>
        @endif
        <div class="box">
          <div class="box-header with-border">
            <i class="fa fa-dot-circle-o"></i>
            <h3 class="box-title">Active Rounds</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-up"></i></button>
            </div>
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="round-grid">
                <thead>
                  <tr>
                    <th>&nbsp;</th>
                    <th>Round</th>
                    <th>Project</th>
                    <th title="Schedule Assignments">Sched</th>{{-- <th>Assignments</th> --}}
                    <th title="Start Date/Time">Start DT</th>{{-- <th>Start Date/Time</th> --}}
                    <th title="Deadline Date">Deadline DT</th>{{-- <th>Deadline Date</th> --}}
                  </tr>
                </thead>
              </table>
            </div>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div><!-- /.col -->

      <div class="col-md-3">
        @foreach($chains as $chain)
        <div class="info-box admin-info-box">
          <span class="info-box-icon">
            @if($chain->client_logo)         
            <a href={{url("/clients-edit",$chain->client_id) }}>{{ Html::image(AppHelper::CLIENT_LOGO.$chain->client_logo,"",["height"=>"72","width"=>"72","class"=>"clients_dashboard"] )}}</a>
            @else
            <a href={{url("/clients-edit",$chain->client_id) }}><i class="fa fa-cube text text-gray"></i></a>
            @endif
          </span>
          <div class="info-box-content">
            <span class="info-box-text text-primary"><a href={{url("/chains-edit",$chain->chain_id) }}>{{ $chain->chain_name }}</a></span>
            <span class="info-box-number">     
              <span class='text-danger'>{{ $chain->project_count }}</span><small>projects</small> <span class="text-gray"> / </span>  <span class='text-success'>{{ $chain->site_count}}</span><small>sites</small></span>
              <div class="progress">
                <div class="progress-bar" style="width: 4%"></div>
              </div>
              <span class="progress-description">
                <a href={{url("/projects-edit/chain",$chain->chain_id) }}>Add Project</a>
              </span>
            </div><!-- /.info-box-content -->
          </div><!-- /.info-box -->
          @endforeach
        </div><!-- /.col -->
      </div><!-- /.row -->
      @endif
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
  @stop
  @section('custom-script')

  <script type="text/javascript">
    $(document).ready(function () {
     roundTable = $('#round-grid').DataTable({
      "serverSide": true,
      "autoWidth":true,
      "bFilter": true,
      "order": [4 , 'asc'],
      ajax: {
        url: '{!! url('rounds') !!}',
        type: 'POST',
        data: function (d) {
            d.round_status = 1; // active rounds
          },
        },
        columns: [
        { data: 'client_logo',      name: 'c.client_logo',    width:'3%',   className: 'client-td',  orderable:false,searchable :false},
        { data: 'round_name',       name: 'r.round_name',     width:'25%' },
        {data: 'project_name',      name: 'p.project_name',   width:'20%' },
        { data: 'scheduled',        name: 'scheduled',        width:'1%',   className: 'text-right', searchable: false },
        { data: 'round_start',      name: 'round_start',      width:'25%',  className: 'text-right', },
        { data: 'round_end',        name: 'round_end',        width:'25%',  className: 'text-right', width:'14%'},
        ]
      });     

   });


 </script>
 @endsection