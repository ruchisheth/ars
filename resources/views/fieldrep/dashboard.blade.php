@extends('fieldrep.app')
@section('page-title') | Fieldrep Dashboard @stop
@section('custome-style')
{{ Html::style(AppHelper::ASSETS.'plugins/easyWizard/easyWizard.css') }}
@stop
@section('content')
<div class="content-wrapper">  
  <section class="content">
    <div class="row">
      <div class="col-md-9">
        <div class="box">
          <div class="box-header with-border">
            <i class="fa fa-check-square-o"></i>
            <h3 class="box-title">Current Assignments</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-up"></i></button>
            </div>
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <table class="table no-margin" id="active_assignement_grid">
                <thead>
                  <tr>
                    <th class='text-right'>Site Code</th>
                    <th>Project</th>
                    <th>Round</th>
                    <th>Location</th>
                    <th title="Scheduled Date/Time">Scheduled DT</th>{{-- <th>Scheduled Date/Time</th> --}}
                    <th title="Deadline Date">Deadline DT</th>
                    <th>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
          </div><!-- /.box-body -->
        </div><!-- /.box -->

        @if($acknos != null)
        {{ Form::open(
          [
          'method'  =>  'post',
          'id'      =>  'acknowledge_form',
          'url'     =>  route('edit.acknowledge')
          ]) 
        }}
        <button type="button" class="btn btn-primary hide" data-toggle="modal" data-target="#myModal" id="btn-acknowledgement"> Launch </button>
        @if($acknos != null)
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Acknowledgements</h4>
              </div>
              <div class="modal-body wizard-content">
                {{-- 'url'  =>  route('edit.acknowledge') --}}
                
                @foreach($acknos as $key => $ackno)
                {{-- @if(@$ackno->rounds->is_bulletin) --}}
                <div class="wizard-step" data-title="{{ @$ackno->rounds->round_name }}"> 
                  <div class="row">
                    <div class="col-md-12">
                      <h4>Acknowledgement of Round <b>{{ @$ackno->rounds->round_name }}</b></h4>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        {{ @$ackno->rounds->bulletin_text }} 
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>
                          {{  Form::checkbox(
                            'is_acknowledged['.@$ackno->rounds->id.']',1,false,
                            [
                            'class' => 'minimal custom_radio',
                            'id'  =>  'is_acknowledged',
                            ])
                          }}
                          <span class="chk_label">
                            I acknowledge this message
                          </span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                {{-- @endif --}}
                @endforeach
              </div>
              <div class="modal-footer wizard-buttons"> 
              </div>
            </div>
          </div>
        </div>
        @endif
        {{ Form::close() }}
        @endif

        @include('fieldrep.calendar_modal')
        @include('fieldrep.offers_page')
      </div><!-- /.col -->

      <div class="col-md-3">
        <!-- Info Boxes Style 2 -->
        <div class="info-box bg-green">
          <span class="info-box-icon">
            <i class="fa fa-check-square-o"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">Active Assignments</span>
            <span class="info-box-number">{{ @$assignment_count }}</span>
            <div class="progress">
              <div class="progress-bar" style="width: 100%"></div>
            </div>
            <span class="progress-description">

            </span>
          </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
        <div class="info-box bg-teal">
          <span class="info-box-icon"><i class="fa fa-check-square-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">New Offers</span>
            <span class="info-box-number">{{ @$offer_count }}</span>
            <div class="progress">
              <div class="progress-bar" style="width: 100%"></div>
            </div>
            <span class="progress-description">

            </span>
          </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->

      </div><!-- /.col -->
    </div><!-- /.row -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@include('fieldrep.instruction')
@stop

@section('custom-script')

{{ Html::script(AppHelper::ASSETS.'plugins/easyWizard/easyWizard.js') }}

<script type="text/javascript">
  var oAssignmentTable ='';
  var oOfferTable ='';
  var offer_status = 'pending';
  $(document).ready(function(){


    $('#btn-acknowledgement').trigger('click');

    $("#myModal").wizard({
      exit: 'Exit',
      back: 'Previous',      
      next: 'Next',
      finish: 'Finish',
      onfinish:function(){
        var form = $("#acknowledge_form");
        var formData = form.serialize();
        var url = '{{ route("edit.acknowledge") }}';
        $.ajax({
          type: "POST",
          url: url,
          data: formData,
          dataType: "json",
          success: function (res) {
            //DisplayMessages(res.message);
          }
        });
      }
    });

    oAssignmentTable = $('#active_assignement_grid').DataTable( {
      "serverSide": true,
      "iDisplayLength": 5,
      "bFilter": true,
      "paging": true,
      "ordering": true,
      "order": [ 4, "asc" ],
      ajax: {
        type: 'POST',
        url: '{!! route("fieldrep.show.assignments.post") !!}',
        data: function (d) {
            //d.status = [1,3]; // assignment_status
            d.status = 'pending'; // assignment_status
            d.project_status = 1; // active projects
            d.round_status = 1; // active rounds
          },
        },
        columns: [
        {data: 'site_code',             name: 's.site_code',            width:'14%', className: 'text-right'},
        {data: 'project_name',          name: 'p.project_name'},
        {data: 'round_name',            name: 'r.round_name'},
        {data: 'city',                  name: 's.city',                 width:'20%'},
        {data: 'assignment_scheduled',  name: 'assignment_scheduled' ,  width:'21%'},
        {data: 'assignment_end',        name: 'assignment_end',         width:'19%'},
        {data: 'survey',                name: 'survey',                 width:'7%',   orderable: false, searchable :false},
        ]

        // {data: 'deadline_date', name: 'assignment_deadline_date', 'width':'15%'},
        // {data: 'round_starts', name: 'assignment_scheduled' , 'width':'25%'},
      });
  });/* .ready overe*/
  function SetAssignmentDetails(element,e)
  {
    e.preventDefault();
    table = $('#detail-grid');
    var Id = $(element).attr('data-id');
    var APP_URL = $('meta[name="_base_url"]').attr('content');
    $.ajax({
      type: 'POST',
      url: APP_URL+'/fieldrep/assignment-details',
      data: { assignment_id :Id },
      success: function (res) {
        details = res.details;
        table.find('#assignment_id').text(details.site_code);
        table.find('#start_date').text(details.start_date);
        table.find('#deadline_date').text(details.deadline_date);
        table.find('#schedule_date').text(details.schedule_date);
        table.find('#round_name').text(details.round_name);
        table.find('#project_name').text(details.project_name);
        table.find('#client_name').text(details.client_name);
        table.find('#location').text(details.location);
        $('#client_logo').attr('src',APP_URL+'/{{ AppHelper::CLIENT_LOGO }}'+details.client_logo);
      },
    });
    $('#calendarModal').modal('show');
  }

</script>
{{ Html::script(AppHelper::ASSETS.'dist/js/pages/fieldrep_dashboard.js') }}
@stop

