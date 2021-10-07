
{{ Form::open(
	[
		'method'=>'post',
		'enctype'  =>  'multipart/form-data',
		'url' => route('admin.profile'),
	]) 
}}
{{  Form::hidden('id', @$oProfile->id)  }}
<div class="box">
	<div class="box-header">
		<i class="fa fa-user"></i>
		<h6 class="box-title text-muted">
			{{ trans('messages.profile_edit') }}
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
					{{  Form::text('email', @$oUser->email,
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
					{{  Form::label('name', 'Name',['class' => 'mandatory'])}}
					{{  Form::text(
						'name',@$oProfile->name,
						[
							'id'	=> 'client_name',
							'class' => 'form-control',

						])
					}}
				</div>
			</div>						
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					{{  Form::label('profile pic', 'Profile Picture') }}
					<div class="custom-file-input custom-size">
						{{  Form::file(
							'profile_pic',
							[
								'id' => 'profile_pic',
								'data-image'	=> (@$oProfile->profile_pic != '') ? asset('public'.config('constants.USERIMAGEFOLDER')).'/'.@$oProfile->profile_pic : '',
							]) 
						}}
						{!! Form::hidden('profile_pic_name',"",['id'=>'profile_pic_name']) !!}
					</div>

					<p class="help-block">{{ trans('messages.upload_your_profile_picture') }}</p>
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

@section('custom-script')

<script type="text/javascript">
	$(document).ready(function(e){
		var profile_pic_src = "";
		var profile_pic_image = $('#profile_pic').data('image');
		if(profile_pic_image != ""){
			profile_pic_src = profile_pic_image;
		}

		var fileinput_options = getFileInputOptions();
		$.extend( fileinput_options, {
			allowedFileExtensions: ["jpg",'png','jpeg'],
			defaultPreviewContent: '<img src="'+getDefaultProfileImage()+'" alt="Your Avatar" style="width:160px">',
			initialPreview: profile_pic_src, 
			initialPreviewConfig: 
			[ 
			{caption: "{{ @$oProfile->profile_pic }}", filename: 'abc', showDelete: false} ,
			]
		});

		$('#profile_pic').fileinput(fileinput_options)
		.on('filecleared', function(event) {
			var fileinput_options = getFileInputOptions();
			$.extend( fileinput_options, {
				allowedFileExtensions: ["jpg",'png','jpeg'],
				defaultPreviewContent: '<img src="'+getDefaultProfileImage()+'" alt="Your Avatar" style="width:160px">',
			});
          		// $('#file-preview-image').show();
          		$('#profile_pic').fileinput('destroy');
          		$('#profile_pic').fileinput(fileinput_options);
          		$('#profile_pic_name').val('user-thumbnail.png');
          	});

	})
</script>


@endsection