@extends('layouts.web.main_layout')
@section('page-title') | Fill Survey @stop
@section('content')
<section class="content profile_page survey_page">
  <div class="fill-survey container">
    <div class="row">
      {{-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"> --}}
        {{-- <div class="survey-info">
          <div class="info-header">
            <div class="icon-image img-150">
              <img src="https://d1pcf5ua3u1h1w.cloudfront.net/user-media/150_1522912122.jpg">
            </div>
            <div class="entity-name"> 
              {{ @$survey_details->client_name }}
            </div>
          </div>
          <div class="survey-details">
            <strong><i class="fa fa-clock margin-r-5"></i> Schedul Date</strong>
            <p class="text-muted">{{ @$survey_details->schedule }}</p>
          </div>
          <div class="survey-details">
            <strong><i class="fa fa-th margin-r-5"></i> Project</strong>
            <p class="text-muted">{{ @$survey_details->project_name }}</p>
          </div>
          <div class="survey-details">
            <strong><i class="fa fa-dot-circle margin-r-5"></i> Round</strong>
            <p class="text-muted">{{ @$survey_details->round_name }}</p>
          </div>
          <div class="survey-details">
            <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
            <p class="text-muted">{{ @$survey_details->site_location }}</p>
          </div>
        </div> --}}
      {{-- </div> --}}

      <div class="col-md-12 ">
        <div class="box box-solid collapsed-box">
          <div class="box-header with-border">
            <h4 class="box-title">
              {{ trans('messages.survey_details') }}
            </h4>
            @if($survey_template->status != '5')
            <div class="pull-right box-tools">
              <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-angle-down"></i></button>
            </div>
            @endif
          </div>
          <div class="box-body">
            <div class="col-md-10 table-responsive">
              <table class="table no-border">
                <tr>
                  <th>Client</th>
                  <td>{{ @$survey_details->client_name }}</td>
                  <th>Project</th>
                  <td>{{ @$survey_details->project_name }}</td>               
                </tr>
                <tr>
                  <th>Round</th>
                  <td>{{ @$survey_details->round_name }}</td> 
                  <th>Site</th>
                  <td>{{ @$survey_details->site_name }}</td>  
                </tr>
                <tr> 
                  <th>Site Location</th>
                  <td>{{ @$survey_details->site_location }}</td>               
                  <th>FieldRep</th>
                  <td>{{ @$survey_details->fieldrep_name }}</td>                
                </tr>
                <tr>                
                  <th>Schedule Date & Time</th>
                  <td>{{ @$survey_details->schedule }}</td>                
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="box box-solid">
          <div class="box-header with-border">
            <h4 class="box-title">Fill Survey
              <small>
                {{-- {!! @$survey_template->getSurveyStatus($survey_template->status) !!} --}}
                {!! @$survey_template->assignments->getAssignmentStatus() !!}
              </small>
            </h4>
          </div>
          @if(Session::get('success')!='')
          {{ Form::hidden('saved_files',Session::get('files')) }}
          @endif
          {!! Form::open(["id"=>"form-holder","url"=>route('save-survey'),"method"=>"POST","enctype"=>"multipart/form-data"]) !!}
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="alert" style="display: none"></div>
              </div>
            </div>
            {{ Form::hidden('status','') }}
            {{ Form::hidden('id',$id) }}
            {{ Form::hidden('template','') }}
            {{ Form::hidden('filled_surveydata','') }}
            {{ Form::hidden('KeyPairs','') }}
            <div class="controls-holder">

              @if($survey_template->status=='0')
              {!! $survey_template->template !!}
              @elseif($survey_template->status=='1' || $survey_template->status=='2')
              {!! $survey_template->surveydata !!}
              @endif
            </div><!-- controls-holder -->
          </div><!-- box-body -->
          @if($survey_template->status=='0')
          <div class="box-footer">
            <div class="pull-right">

              {{-- {!! Form::button('Save Surveys', ['class' => 'btn btn-primary','onclick'=>'SubmitSurvey(this,"1","'.$id.'")','type'=>'button']) !!} --}}
              <button type="button" class="btn btn-primary" onClick="SubmitSurvey(this,1,{{ $id }})">Save</button>
              <a href={{ url()->previous() }} id="cancel" class="btn btn-default">Cancel</a>
            </div>
          </div>
          @elseif($survey_template->status=='1' || $survey_template->status=='3' || Auth::user()->roles->slug == 'admin')
          <div class="box-footer">
            <div class=" pull-right">
              @if(Auth::user()->roles->slug != 'admin')
              <button type="button" class="btn btn-primary" onClick="SubmitSurvey(this,1,{{ $id }})">Save</button>
              <button type="button" class="btn btn-success sbt-survey" onClick="SubmitSurvey(this,2,{{ $id }})">Submit</button>
              @else 
              <button type="button" class="btn btn-success sbt-survey" onClick="SubmitSurvey(this,2,{{ $id }})">Save</button>
              @endif
              <a href={{ url()->previous() }} id="cancel" class="btn btn-default">Cancel</a>
            </div>
          </div>
          @endif
          {!! Form::close() !!} 
        </div>
      </div>
    </div>
  </section>
</div>
@stop

@section('custom-scripts')


<script type="text/javascript">
  $(document).ready(function(){
    initDatePicker();
    enableInputControl();
  });

  $('.tooltip.fade.top.in').hide();

</script>
<script type="text/javascript" src="{{ asset('public/assets/web/js/builder.js') }}"></script>
{{-- {{ Html::script(AppHelper::ASSETS.'plugins/builder/builder.js') }} --}}
@stop