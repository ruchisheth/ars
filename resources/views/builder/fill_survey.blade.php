@extends('app_no_header')
@section('page-title') | Fill Survey @stop
@section('content')
<div class="content-wrapper fill-survey">
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default collapsed-box">
					<div class="box-header with-border">
						<h4 class="box-title">
							Survey Details
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
				<div class="box box-default">
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
						<input type="hidden" name="MAX_FILE_SIZE" value="20971520">
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
							<a href={{route('fieldrep.home')}} id="cancel" class="btn btn-default">Cancel</a>
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
							<a href={{route('fieldrep.home')}} id="cancel" class="btn btn-default">Cancel</a>
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

@section('custom-script')


<script type="text/javascript">
	$(document).ready(function(){
		initDatePicker();
		enableInputControl();
	});

	$('.tooltip.fade.top.in').hide();

</script>

{{ Html::script(AppHelper::ASSETS.'plugins/builder/builder.js') }}
@stop

