@extends('app')
@section('page-title') | @lang('messages.surveys') @stop
@section('content')

<div class="content-wrapper">
	<section class="content">
		<div class="box">
			<div class="box-header with-border">
				<i class="fa fa-folder"></i>
				<h3 class="box-title">@lang('messages.surveys')</h3>
				@include('includes.errors')
				@include('includes.success')
			</div><!-- /.box-header -->

			<form id='document_list_form' enctype="multipart/form-data">
				<div class="box-body">
					<div class="documents-list">
						@include('AdminView::surveys._more_exported_survey_list_ajax')
					</div>
				</div>
			</form>
		</div>
	</section>
	@include('includes.confirm_delete_modal',
		[
			'id'    =>  'document',
			'name'  =>  'Document',
			'msg'   =>  trans('messages.document_delete_confirm')
			])
		</div>
		@stop

		@section('custom-script')
		<script type="text/javascript">

			flagDoc = 0;

			$(window).bind('popstate', function() {
				var sPathName = location.pathname;

				var aUrlComponents = sPathName.split('/');
				var sUrl = APP_URL + '/' + aUrlComponents[1];
				if(aUrlComponents[2] != undefined){
					sUrl += '/' + aUrlComponents[2];
				}

				if(aUrlComponents[3] != undefined){
					sUrl += '/' + aUrlComponents[3];
				}
				listFolder(sUrl);
			});

			$(document).on('click', '.jsdocumentLink', function(e){
				var sDateDirName 	= $(this).data('date_dir') ? $(this).data('date_dir') : undefined;

				var sHourDirName 	= $(this).data('hour_dir') ? $(this).data('hour_dir') : undefined;

				var sDirName = $(this).data('dir_name') ? $(this).data('dir_name') : undefined;

				var sUrl = APP_URL+'/exported-survey';

				if(sDateDirName != undefined){
					sUrl += '/' + sDateDirName;
				}else{
					sUrl += '/' + sDirName;
				}

				if(sHourDirName != undefined){
					sUrl += '/' + sHourDirName;
				}else{
					if(sDateDirName != undefined){
						sUrl += '/' + sDirName;
					}
				}
				listFolder(sUrl);
			});

			$(document).on('submit', '#document_list_form',function(e){
				e.preventDefault();
				return false;
			});

			$(document).on('click', 'button[name="remove_document"]', function(e){
				e.preventDefault();
				oElement = $(this);
				var nIdDocument =  oElement.data('id_document');
				var oFormData = {id_document: nIdDocument};
				var sUrl = "{{ route('exported.survey.delete') }}";

				$('#delete_document').modal('show');
				$('#delete_document').find('#delete').bind('click', function() {      
					$.ajax({
						type: 'POST',
						url: sUrl,
						data: oFormData,
						dataType: 'json',
						success: function (oResponse) {
							$('.documents-list').html(oResponse.data.sHTML);
						},
						error: function (jqXHR, oException) {
						}
					});
				});
			});

			function listFolder(sUrl){
				$.ajax({
					url: sUrl,
					processData: false,
					contentType: false,
					beforeSend: function() {
						$(".loader").show();
						$('.document-body').hide();
					},complete: function() {
						$(".loader").hide();
					},
					success: function (oResponse) {
						console.log(oResponse);
						if (sUrl != window.location) {
							window.history.pushState({path: sUrl}, '', sUrl);
						}
						$('.documents-list').html(oResponse.data.sHTML);
					},
					error: function (data) {
						if (data.status == 401)
						{
							window.location = siteUrl + '/home';
						}
					}
				});
			}
		</script>

		@stop