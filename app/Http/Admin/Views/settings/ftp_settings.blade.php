{{ Form::open(array('method'=>'post',
	'enctype'  =>  'multipart/form-data',
	'url' => route('setting.ftp'),
	'id' => 'ftp_setting_form'
	)) }}
	<div class="box">
		<div class="box-header">
			<i class="fa fa-lock"></i>
			<h6 class="box-title text-muted">
				@lang('messages.ftp_settings')
			</h6>
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
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						{{  Form::label('current_password', trans('messages.ftp_host')) }}
						{{  Form::text('ftp_host', @$aAdminSettings['ftp_host'],
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
						{{  Form::label('ftp_username', trans('messages.ftp_username')) }}
						{{  Form::text('ftp_username', @$aAdminSettings['ftp_username'],
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
						{{  Form::label('ftp_password', trans('messages.ftp_password'))}}
						{{  Form::input('password', 'ftp_password', @$aAdminSettings['ftp_password'],
							[
								'id' => 'ftp_password',
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
						{{  Form::label('ftp_port', trans('messages.ftp_port'))}}
						{{  Form::text('ftp_port', @$aAdminSettings['ftp_port'],
							[
								'id' => 'ftp_port',
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
						{{  Form::label('ftp_directory', trans('messages.ftp_directory'))}}
						{{  Form::text('ftp_directory', @$aAdminSettings['ftp_directory'],
							[
								'id' => 'ftp_directory',
								'class' => 'form-control',
								'autocomplete' => 'off',
							])
						}}
					</div>
				</div>
			</div>

		</div>
		<div class="box-footer">
			<div class="pull-right">
				<div class="pull-right">
					{{  Form::button(trans('messages.save'),
						[
							'id' => 'save_ftp_btn',
							'class' => 'btn btn-primary pull-right'
						])
					}}
				</div>
				<div class="col-md-1 pull-right">
					<a href="{{ URL::previous() }}" id="cancel" class="btn btn-default pull-right">@lang('messages.cancel')</a> 
				</div>
			</div>                     
		</div><!-- /.box -->
		<div class="toggle" data-toggle-on="true" data-toggle-height="20" data-toggle-width="60"></div>
	</div>
	{{	Form::close() }}

	@section('custom-script')

	<script type="text/javascript">
		$(document).on('click', '#save_ftp_btn', function(e){
			e.preventDefault();
			var oForm = $('#ftp_setting_form');
			var sUrl = oForm.attr('action');
			var oFormData = oForm.serialize();
			$.ajax({
				type: "POST",
				url:  sUrl,
				data: oFormData,
				data_type: 'json',
				success: function(data) {
					DisplayMessages("{{trans('messages.ftp_details_saved_successfully')}}", 'success');
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