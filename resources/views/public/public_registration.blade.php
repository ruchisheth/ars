<!DOCTYPE html>
<html>
<head>
	@include('includes.head')
	@include('includes.styles')
	
	<title>ARS | Register </title>
</head>
<body class="hold-transition register-page">

	<div class="form-container">
		<div class="col-md-8 registration-box">

			{{ Form::open([
				'method'  =>  'post',
				'id' => 'fieldrep_registration_form'
				]) }}
				<h3 class="box-title">REGISTER</h3>
				<div id="rootwizard" class="modal-header">
					<ul class="col-md-10">
						<li><a href="#tab1" data-toggle="tab">Personal Details</a></li>
						<li><a href="#tab2" data-toggle="tab">Contact Details</a></li>
						<li><a href="#tab3" data-toggle="tab">Other Details</a></li>
						<li><a href="#tab4" data-toggle="tab">Project Types</a></li>
						<li><a href="#tab5" data-toggle="tab">General Availability</a></li>
					</ul>
					
					<ul class="pager wizard col-md-2 top navbar-right">

						<li class="previous"><a href="javascript:void(0)"><i class="fa fa-arrow-left"></i></a></li>
						<li class="next"><a href="javascript:void(0)"><i class="fa fa-arrow-right"></i></a></li>
						<li class="finish"><a href="javascript:;" class="bg-blue"><i class="fa fa-flag-checkered"></i></a></li>
					</ul>
				{{-- </div>
				<div class="modal-body"> --}}
					<div class="tab-content">
						<input type="hidden" id="cc" value="{{ @$cc }}">
						<div class="tab-pane" id="tab1">
							<div class="alerts"></div>
							@include('public.personal_detail')
						</div>
						<div class="tab-pane" id="tab2">
							<div class="alerts"></div>
							@include('public.contact_detail')
						</div>
						<div class="tab-pane" id="tab3">
							<div class="alerts"></div>
							@include('public.other_detail')
						</div>
						<div class="tab-pane" id="tab4">
							<div class="alerts"></div>
							@include('public.project_detail')
						</div>
						<div class="tab-pane" id="tab5">
							<div class="alerts"></div>
							@include('public.availability_detail')
						</div>
						@include('public.register_confirm_modal')
						<hr>
					{{-- </div>
					<div class="modal-footer"> --}}
						<ul class="pager wizard bottom">
							<li class="previous"><a href="javascript:void(0)">Previous</a></li>
							<li class="next"><a href="javascript:void(0)">Next</a></li>
							<li class="finish"><a href="javascript:void(0);" class="bg-blue">Submit</a></li>
						</ul>
					</div>
				</div>
				{{ Form::close() }}
			</div>
		</div>

		@include('includes.scripts')

		<script>
			$(document).ready(function () { 
				form = $('#fieldrep_registration_form');
				cc = $('#cc').val();
				$('#rootwizard').bootstrapWizard({
					'tabClass': 'nav nav-pills',
					'onNext': function(tab, navigation, index) {
						is_error = false;
						$.ajax({
							type:'POST',
							async: false,
							url: APP_URL+'/fieldrep/'+cc+'/validate/'+index,
							dataType: 'json',
							data: $('#fieldrep_registration_form').serialize(),							
							error: function (jqXHR, exception) {
								var response = jqXHR.responseText;
								var errors = $.parseJSON(response);
								ErrorBlock = $(form).find('#tab'+index+' .alerts');
								DisplayErrorMessages(errors, ErrorBlock);
								is_error = true;								
							}
						});	
						//return true;					
						if(is_error) {
							return false;
						}else{
							return true;
						}

					},

					'onFinish': function(tab, navigation, index) {
						$('#register_confirm_modal').modal('show');
					},
					onTabClick: function(tab, navigation, index) {
						return false;
					},
				});
			});

			$("[data-mask]").inputmask();

			$('#cities').tagsinput({
				confirmKeys: [13], //32-space, 13-enter
				tagClass: 'label label-primary',
				allowDuplicates: false,
				maxTags: 3,
			});

			$('#is_employed_yes').on('ifChanged', function(event){
				var checked = event.currentTarget.checked;
				if(checked){
					$('#occupations').slideDown('slow');
				}else{
					$('#occupations').slideUp('slow');
				}
			});

			$('#as_merchndiser_yes').on('ifChanged', function(event){
				var checked = event.currentTarget.checked;
				if(checked){
					$('#experience').slideDown('slow');
				}else{
					$('#experience').slideUp('slow');
				}
			});

			$(document).on('click', '#confirm_register', function(e){
				$.ajax({
					type:'POST',
					async: false,
					url: APP_URL+'/fieldrep/'+cc+'/register',
					dataType: 'json',
					data: $('#fieldrep_registration_form').serialize(),
					success: function (res) {	
						$('#register_confirm_modal').modal('hide');
						DisplayMessages(res.message,'success');
						location.href  = "{{ AppHelper::APP_URL }}";
					},						
					error: function (jqXHR, exception) {
						var response = jqXHR.responseText;
						var errors = $.parseJSON(response);
						ErrorBlock = $(form).find('#tab'+index+' .alerts');
						DisplayErrorMessages(errors, ErrorBlock);
						is_error = true;							
					}
				});
			});

				</script>
			</body>
			</html>
