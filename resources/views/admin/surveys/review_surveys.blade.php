@extends('app_no_header')
@section('page-title') | Review Survey @stop
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
						<div class="pull-right box-tools">
							<button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-angle-down"></i></button>
						</div>

					</div>
					<div class="box-body">
						<div class="col-md-8">
							<table class="table no-border no-row-height">
								<tr>
									<th>Client</th>
									<td>{{ @$survey_details->client_name }}</td>
									<th>Site</th>
									<td>{{ @$survey_details->site_name }}</td>
								</tr>
								<tr>
									<th>Project</th>
									<td>{{ @$survey_details->project_name }}</td>
									<th>FieldRep</th>
									<td>{{ @$survey_details->fieldrep_name }}</td>
								</tr>
								<tr>
									<th>Round</th>
									<td>{{ @$survey_details->round_name }}</td>
									<th>Assignment Code</th>
									<td>{{ @$survey_details->code }}</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="box box-default">
			<div class="box-header with-border">
				<h4 class="box-title">
					Review Survey
					<small>
						{!! @$survey_template->getSurveyStatus($survey_template->status) !!}
					</small>
				</h4>
				<div class="pull-right box-tools">
					@if($survey_template->status != 3 && $survey_template->status != 4)
					<button class="btn btn-danger btn-sm" data-id="{{ $id }}" data-status="3" onclick="changeStatus(this, event)">Mark As {{ trans('messages.assignment_status.rejected') }}</button>
					<button class="btn btn-success btn-sm" data-id="{{ $id }}" data-status="4" onclick="changeStatus(this, event)">{{ trans('messages.assignment_status.approved') }}</button>
					@endif            
					<button class="btn btn-default btn-sm" data-id="{{ $id }}" onclick="exportSurvey(this, event)">Export</button>
					<a href="{{ route('surveys') }}" id="cancel" class="btn btn-default btn-sm">Back</a>
				</div>
			</div>
			@if(Session::get('success')!='')
			{{ Form::hidden('saved_files',Session::get('files')) }}
			@endif
			{!! Form::open(["id"=>"form-holder","method"=>"POST","enctype"=>"multipart/form-data"]) !!}
			<div class="box-body">
				{{ Form::hidden('status',@$survey_template->status) }}
				{{ Form::hidden('id',$id) }}
				{{ Form::hidden('template','') }}
				{{ Form::hidden('KeyPairs','') }}
				<div class="controls-holder">
					@include('admin.surveys.filled_survey',$questions)
				{{-- {{ @$view }} --}}
				{{-- {{ @$survey_template->keypairs }} --}}
				</div><!-- controls-holder -->
			</div><!-- box-body -->
			{!! Form::close() !!} 
		</div>
	</section>
</div>
@stop