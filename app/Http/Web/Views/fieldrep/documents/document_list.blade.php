@extends('layouts.web.main_layout')
@section('page-title') | @lang('messages.documents') @stop
@section('content')
<section class="content">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="box box-solid">
				<div class="cards-panel">
					<div class="tab-content">
						<ul class="nav nav-tabs">
							<li class="active jsdocumentLink">
								{{-- <a href="#scheduled_assignments" data-toggle="tab">{{ trans('messages.assignment_status.scheduled') }} | {{ $nScheduledAssignmentCount }}</a> --}}
								<a href="javascript:void(0)"> {{ trans('messages.clients') }} </a>
							</li>
						</ul>
						<div class="card-body">
							<div class="card-list">
									{{-- <div class="input-group margin">
										<input type="text" class="form-control" placeholder="Search...">
										<span class="input-group-btn">
											<button type="button" class="btn btn-default"><i class="fa fa-search"></i></button>
										</span>
									</div> --}}

									<div class="documents-list">
										@include('WebView::fieldrep.documents._more_document_list_ajax')
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	{{-- </div> --}}
	<!--Tab Content-->
{{-- </section> --}}
@stop

@section('custom-scripts')
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
		alert
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
				// $('#documents-list').show('solw', function );
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
