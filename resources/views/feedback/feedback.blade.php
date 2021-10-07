<!DOCTYPE html>
<html>
<head>
	@include('layouts.admin.head')
	@include('layouts.admin.styles')
	<title>ARS | Feedback </title>
</head>
<body class="hold-transition feedback-page {{ (count($errors) > 0)  ? 'feedback-has-error' : '' }}">
	<div class="form-container">
		<div class="sign-in">
			<div class="description">
				<img src="{{ asset(AppHelper::LOGO_WHITE) }}">
				<p>ARS is a leading application service provider committed to delivering outstanding technology solutions for the Retail Marketing industry.</p><p>ARS collaborates with it's clients to help them maximize performance and manage their businesses better.</p>
			</div>

			{{ Form::open(array(
				'method'  =>  'POST',
				'url' => 	route('send.feedback',['code' => $code, 'client_code' => $client_code]),
				'class'		=>	'form-horizontal',
				)) }}
				<h3 class="login-box-msg">Were you satisfied with the service provided by the FieldRep?</h3>
				

				<div class="row">
					<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
						<label class="col-sm-3">Client Name</label>
						<div class="col-sm-9">
							<label class="">{{ @$client_name }}</label>
							{{-- <input type="text" name="client_name" class="form-control" placeholder="Client Name" value="{{ old('email') }}"> --}}
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group {{ $errors->has('site') ? ' has-error' : '' }}">
						{{  Form::label('chains', 'Select Site',['class'=>'mandatory col-sm-3']) }}
						<div class="col-sm-9">
							{{  Form::select(
								'site', @$sites, '',
								[
								"id" => 'site',
								"class"=>'form-control',
								]) 
							}}
							@if ($errors->has('site'))
							<span class="help-block">
								<strong>{{ $errors->first('site') }}</strong>
							</span>
							@endif
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
						{{  Form::label('name', 'Your Name',['class' => 'mandatory col-sm-3'])}}
						<div class="col-sm-9">
							{{  Form::text(
								'name','',
								[
								'id' => 'name',
								'class' => 'form-control',
								])
							}}
							@if ($errors->has('name'))
							<span class="help-block">
								<strong>{{ $errors->first('name') }}</strong>
							</span>
							@endif
						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group {{ $errors->has('phone_number') ? ' has-error' : '' }}">
						{{  Form::label('phone_number', 'Phone Number', ['class' => 'col-sm-3'])}}
						<div class="col-sm-9">
							{{  Form::text(
								'phone_number','',
								[
								'id' => 'phone_number',
								'class' => 'form-control',
								])
							}}

						</div>
					</div>
				</div>

				<div class="row">
					<div class="form-group {{ $errors->has('feedback_message') ? ' has-error' : '' }}">
						{{  Form::label('feedback_message', 'Message', ['class' => 'mandatory col-sm-3'])}}
						<div class="col-sm-9">
							{{ Form::textarea('feedback_message', '',  ['class' => 'form-control ', 'size' => '30x4']) }}
							@if ($errors->has('name'))
							<span class="help-block">
								<strong>{{ $errors->first('feedback_message') }}</strong>
							</span>
							@endif
						</div>
					</div>
				</div>


				<div class="row">
					<div class="form-group">
						<div class="col-md-4 pull-right">
							<button type="submit" class="btn btn-primary btn-block btn-flat">
								Submit Feedback
							</button>
						</div><!-- /.col -->
					</div>
				</div>
				{{ Form::close() }}

			</div>
		</div>

		<!-- jQuery 2.1.4 -->
		{{ Html::script(AppHelper::ASSETS.'plugins/jQuery/jQuery-2.1.4.min.js') }}

		<!-- Bootstrap 3.3.5 -->
		{{ Html::script(AppHelper::ASSETS.'bootstrap/js/bootstrap.min.js') }}


	</body>
	</html>
