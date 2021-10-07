
{{ Form::open(array('method'=>'post',
	'enctype'  =>  'multipart/form-data',
	'url' => route('save.profile'),
	)) }}
	{{  Form::hidden('id',@$profile['id'])  }}
	<div class="box">
		<div class="box-header">
			<i class="fa fa-user"></i>
			<h6 class="box-title text-muted">
				Profile Edit 
			</h6>
		</div><!-- /.box header -->
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
						{{  Form::label('email', 'Email',['class' => 'mandatory']) }}
						{{  Form::text('email', @$email,
							[
							'id' => 'email',
							'class' => 'form-control',
							])
						}}
					</div>
				</div>						
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						{{  Form::label('client_name', 'Name',['class' => 'mandatory'])}}
						{{  Form::text(
							'name',@$name,
							[
							'id' => 'client_name',
							'class' => 'form-control',
							])
						}}
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
					<a href="{{ URL::previous() }}" id="cancel" class="btn btn-default pull-right">Cancel</a> 
				</div>
			</div>                     
		</div><!-- /.box -->
		<div class="toggle" data-toggle-on="true" data-toggle-height="20" data-toggle-width="60"></div>
	</div>
	{{	Form::close() }}