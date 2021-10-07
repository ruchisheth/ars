{{ Form::open(array('method'=>'post',
	'enctype'  =>  'multipart/form-data',
	'url' => route('password.reset'),
	'id' => 'change_password_form'
	)) }}
	{{  Form::hidden('id',@$id)  }}
	{{-- {{  Form::hidden('url',URL::previous())  }} --}}
	<div class="box collapsed-box">
		<div class="box-header">
			<h6 class="box-title text-muted">
				Change Password
			</h6>
			<div class="box-tools pull-right">
				<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-down"></i></button>
			</div>
		</div><!-- /.box header -->
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					<div class="alert" style="display: none">
						<sapn class="text text-danger">
						</sapn>
					</div>
				</div>
			</div>					
			{{-- <div class="row">
				<div class="col-md-12">
					<div class="form-group">
						{{  Form::label('current_password', 'Current Password') }}
						{{  Form::password('current_password', 
							[
							'class' => 'form-control',
							'autocomplete' => 'off',
							])
						}}                  
					</div>
				</div>	
			</div> --}}
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						{{  Form::label('password', 'Password') }}
						{{  Form::password('password', 
							[
							'class' => 'form-control',
							'autocomplete' => 'off',
							])
						}}                  
					</div>
				</div>	
			</div>	
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						{{  Form::label('password_confirmation', 'Re-enter Password')}}
						{{  Form::password('password_confirmation', 
							[
							'id' => 'password_confirmation',
							'class' => 'form-control',
							])
						}}
					</div>
				</div>
			</div><!-- 2nd row over -->

		</div>
		<div class="box-footer">
			<div class="pull-right">
				<div class="pull-right">
					{{  Form::submit('Change',
						[
						'id' => 'change_password',
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

	@section('custom-script')

	<script type="text/javascript">
		$(document).on('click', '#change_password', function(e){
			e.preventDefault();
			var form = $('#change_password_form'),
			route = form.attr('action'),
			form_data = form.serialize();
			$.ajax({
				type: "POST",
				url:  route,
				data: form_data,
				data_type: 'json',
				success: function(data) {
					DisplayMessages("Your password has been reset!", 'success');
					form[0].reset();
				},
				error: function (jqXHR, exception) {
					var response = jqXHR.responseText;
					
					var errors = $.parseJSON(response);
					ErrorBlock = $(form).find('.alert');
					DisplayMessages(errors,'error`')
					DisplayErrorMessages(errors, ErrorBlock, 'div');
				}
			});
		});
	</script>
	@append