@extends('fieldrep.app')
@section('page-title') | @lang('messages.resources') @stop
@section('content')

<div class="content-wrapper">
	<section class="content">
		<div class="box">
			<div class="box-header with-border">
				<i class="fa fa-folder"></i>
				<h3 class="box-title">@lang('messages.resources')</h3>
				@include('includes.errors')
				@include('includes.success')
			</div><!-- /.box-header -->

			<form id='document_list_form' enctype="multipart/form-data">
				<div class="box-body">
					<div class="documents-list">
						@include('fieldrep.documents._more_document_list_ajax')
					</div>
				</div><!-- /.box-body -->
			</form>
		</div><!-- /.box -->
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

			$(window).bind('popstate', function() {
				var sPathName = location.pathname;

				var aUrlComponents = sPathName.split('/');
				var sUrl = "{{ route('fieldrep.document-list') }}";
				if(aUrlComponents[3] != undefined){
					sUrl += '/' + aUrlComponents[3];
				}

				if(aUrlComponents[4] != undefined){
					sUrl += '/' + aUrlComponents[4];
				}
				listFolder(sUrl);
			});

			$(document).on('click', '.jsdocumentLink', function(e){
				var nIdClient 	= $(this).data('id_client') ? $(this).data('id_client') : undefined;

				var nIdDocument = ($(this).data('id_document') != undefined) ? $(this).data('id_document') : undefined;

				var sUrl = "{{ route('fieldrep.document-list') }}";
				if(nIdClient != undefined){
					sUrl += '/' + nIdClient;
				}

				if(nIdDocument != undefined){
					sUrl += '/' + nIdDocument;
				}

				listFolder(sUrl);
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
						if (sUrl != window.location) {
							window.history.pushState({path: sUrl}, '', sUrl);
						}
						$('.documents-list').html(oResponse.data.sHTML);
						if(oResponse.data.nIdClient != null){
							$('.box-tools').removeClass('hide');
						}else{
							$('.box-tools').addClass('hide');
						}
					},
					error: function (data) {
						if (data.status == 401)
						{
							window.location = siteUrl + '/home';
						}
						console.log('Error:', data);
					}
				});
			}

		</script>

		@stop