@extends('app')
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

				<div class="box-tools {{ ($nIdClient) ? '' : 'hide' }}">
					<a href="javascript:void(0)" class="btn btn-box-tools btn-sm add-new-folder" title="{{ trans('messages.create_folder') }}"><i class="fa fa-plus"></i></a>
					<a href="javascript:void(0)" class="btn btn-box-tools btn-sm add-new-file" title="{{ trans('messages.upload_file') }}"><i class="fa fa-file"></i></a>
				</div>
			</div><!-- /.box-header -->

			<form id='document_list_form' enctype="multipart/form-data">
				<div class="box-body">
					<div class="documents-list">
						@include('AdminView::documents._more_document_list_ajax')
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

			var newFolderHTML = "<tr class='mk-new-folder'>"
			+ "<td class='text-center'>" 
			+ "<i class='fa fa-folder text-gray'></i>"
			+ "</td>"
			+ "<td colspan='2'>"
			+ "<input type='text' name='folder_name' class='new-folder-name form-control' placeholder='@lang("messages.folder_name")' autofocus>"
			+ "</td>"
			+ "</tr>";

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

			$(".add-new-file").click(function(){
				$('#file_upload').click();
			});

			$(".add-new-folder").click(function()
			{	
				if($(".document-list-parent").find('.mk-new-folder').length > 0){
					return false;
				}
				$(".document-list-parent").prepend(newFolderHTML);
				$('.new-folder-name').focus();
				$( ".new-folder-name" ).select();
			});

			$(document).on('click', '.jsdocumentLink', function(e){
				var nIdClient 	= $(this).data('id_client') ? $(this).data('id_client') : undefined;

				var nIdDocument = ($(this).data('id_document') != undefined) ? $(this).data('id_document') : undefined;

				var sUrl = APP_URL+'/document-list';
				if(nIdClient != undefined){
					sUrl += '/' + nIdClient;
				}

				if(nIdDocument != undefined){
					sUrl += '/' + nIdDocument;
				}

				listFolder(sUrl);
			});

			$(document).on('focusout','.new-folder-name',function(e){ 
				if(e.type == "focusout"){
					if(!flagDoc){
						CreateFolder(this,e);
					}
					else{
						flagDoc = 0;
					}
				}
			});

			$(document).on('keyup','.new-folder-name',function(e){
				if (e.keyCode == 13) {
					flagDoc = 1;
					CreateFolder(this,e);
				} 
				if(e.which == 27){
					cancelCreateFolder();
				}
			});

			$(document).on('focusout','.rename-folder-name',function(e){ 
				if(e.type == "focusout"){
					if(!flagDoc){
						cancelRenameFolder($(this))
						// CreateFolder(this,e);
					}
					else{
						flagDoc = 0;
					}
				}
			});

			$(document).on('change','.rename-folder-name',function(e){ 
				if(e.type == "change"){
					console.log('change');
					if(!flagDoc){
						CreateFolder(this,e);
					}
					else{
						flagDoc = 0;
					}
				}
			});

			$(document).on('keyup','.rename-folder-name',function(e){
				if (e.keyCode == 13) {
					flagDoc = 1;
					CreateFolder(this,e);
				} 
				if(e.which == 27){
					cancelRenameFolder($(this));
				}
			});


			$(document).on('submit', '#document_list_form',function(e){
				e.preventDefault();
				return false;
			});

			$(document).on('change', '#file_upload', function(){
				alert = function() {};
				$('#doc_type').val('F');
				var oFormData = new FormData($('#document_list_form')[0]);
				console.log(oFormData);
				callCreateFolderOrFile(oFormData);
			});

			$(document).on('click', 'button[name="remove_document"]', function(e){
				e.preventDefault();
				oElement = $(this);
				var nIdDocument =  oElement.data('id_document');
				var oFormData = {id_document: nIdDocument};
				var sUrl = "{{ route('document.delete') }}";

				$('#delete_document').modal('show');
				$('#delete_document').find('#delete').bind('click', function() {      
					$.ajax({
						type: 'POST',
						url: sUrl,
						data: oFormData,
						dataType: 'json',
						success: function (oResponse) {
							$('.documents-list').html(oResponse.data.sHTML);
							// oElement.parents('.list-group-item').fadeOut('slow', function(){ oElement.parents('.list-group-item').remove(); });
							// oElement.parents('tr').fadeOut('slow', function(){ oElement.parents('tr').remove(); });
						},
						error: function (jqXHR, oException) {
						}
					});
				});
			});

			$(document).on('click', 'button[name="rename_document"]', function(){
				// var sFolderName = htmlSpecialChars($.trim($(this).parent().parent().parent().siblings('li').children('a').text()));

				var sFolderName = htmlSpecialChars($.trim($(this).parents('tr').find('.file-name').text()));
				var nIdClient = $(this).parents('tr').data('id_client');
				var nIdDocument = $(this).parents('tr').data('id_document');

				var sRenameFolderHTML = "<input type='text' name='folder_name' class='rename-folder-name form-control' placeholder='@lang("messages.folder_name")' value='"+sFolderName+"' autofocus>"
				+ "<input type='hidden' id='id_document' name='id_document' value='"+nIdDocument+"'>";

				var sFolderName = $(this).parents('tr').find('.document-name').hide().after(sRenameFolderHTML);

				$('.rename-folder-name').focus().select();

				$('input[name="id_document"]').val(nIdDocument);
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
					}
				});
			}

			function CreateFolder(oElement,  oEvent){
				var sFolderName = htmlSpecialChars($(oElement).val());
				if(sFolderName != '')
				{
					// $(oElement).closest('li').removeClass('mk-new-folder');	
					$('#doc_type').val('FO');
					// $('#folder_name').val(sFolderName);
					oEvent.preventDefault();
					oEvent.stopImmediatePropagation();
					var oFormData = new FormData($('#document_list_form')[0]);
					callCreateFolderOrFile(oFormData)
				}else{
					cancelCreateFolder();
					cancelRenameFolder($oElement);
				}
			}

			function callCreateFolderOrFile(oFormData){
				var sUrl = "{{ route('document.create_folder') }}";
				$.ajax({
					type: "POST",
					url: sUrl,
					data: oFormData,
					contentType: false,
					processData: false,
					success: function (oResponse) {
						$('.documents-list').html(oResponse.data.sHTML);
						$('input[name="id_document"]').val();
					},
					error: function (data) {
					}
				});
			}

			function cancelCreateFolder(){
				$('.mk-new-folder').remove()
			}

			function cancelRenameFolder(oElement){
				oElement.parents('tr').find('.document-name').show();
				oElement.remove();
			}
		</script>

		@stop