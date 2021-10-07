{{-- Breadcrumb --}}
@if($nIdParent != NULL || $nIdClient != NULL)
<ol class="breadcrumb">
	
	@if($nIdParent != NULL)
		{{-- */ $aBreadCrumbs =  array_reverse(App\Libraries\Document::find(@$nIdParent)->ancestorsAndSelf()->pluck('document_name', 'id_document')->toArray(), true) /*--}}
		@foreach($aBreadCrumbs as $nIdDocument => $sDocumentName)
			<li>
				<a 	href="javascript:void(0)" 
					class="jsdocumentLink"
					data-id_client='{{ $nIdClient  }}' 
					data-id_document='{{ $nIdDocument  }}' >
				{{ $sDocumentName }}</a>
			</li>
		@endforeach
	@endif
	@if($nIdClient != NULL)
	{{-- */ $oClient =  App\Client::find($nIdClient) /*--}}
	<li>
		<a 	href="javascript:void(0)"  class="jsdocumentLink" data-id_client='{{ $oClient->id  }}' data-id_document='' >
			{{ $oClient->client_name }}
		</a>
		{{-- <li>
			<a href="javascript:void(0)"  class="jsdocumentLink"></i> {{ trans('messages.clients') }} </a>
		</li> --}}
	</li>
	@endif
</ol>
@endif

<ul class="list-group document-list">
	<input type='hidden' id='doc_type' name='doc_type'>
	<input type="file" name="file[]" id="file_upload" class="hide" multiple/> 
	<input type='hidden' id='id_parent' name='id_parent' value="{{ @$nIdParent }}">
	<input type='hidden' id='id_client' name='id_client' value="{{ @$nIdClient }}">
	@include('layouts.web.loader')
	<div class="document-body">
		@if(!isset($nIdClient) || @$oClients != NULL)
			{{-- <li class="list-group-item document-header">
				<span><i class='fa fa-user '></i></span>
				<label>{{ trans('messages.clients') }}</label>
			</li> --}}

			{{-- list Clients --}}
			@foreach($oClients as $oClient)
				<li class="list-group-item">
					<div class="document-info">
						<span class='document-icon'><i class='fa fa-user text-gray'></i></span>
					</div>
					<div class="document-info document-name">
						<a 	href="javascript:void(0)"  class="jsdocumentLink" data-id_client='{{ $oClient->id  }}' data-id_document='' >
							{{ $oClient->client_name }}
						</a>
					</div>
				</li>
			@endforeach
		@else
			{{-- list folder --}}
			{{-- <li class="list-group-item document-header">
				<span><i class='fa fa-folder'></i></span>
				<label>{{ trans('messages.folder_name') }}</label>
			</li> --}}
			<div class="document-list-parent" data-id_parent_document="{{ $nIdParent }}">
				@if(count($oDocuments))
					@foreach($oDocuments as $oDocument)
						<li class="list-group-item">
							{{-- if document is folder --}}
							@if($oDocument->document_type == config('constants.DOCUMENTTYPEFOLDER'))
								<div class="document-info">
									<span class='document-icon'><i class='fa fa-folder text-gray'></i></span>
								</div>
								{{-- folder name --}}
								<div class="document-info document-name">
									<a 	href="javascript:void(0)" 
									class="jsdocumentLink"
									data-id_client='{{ $oDocument->id_client  }}' 
									data-id_document='{{ $oDocument->id_document  }}' >
									{{ $oDocument->document_name }}</a>
								</div>
								{{-- if document if file --}}
							@elseif($oDocument->document_type == config('constants.DOCUMENTTYPEFILE'))
								<div class="document-info">
									{{--*/ $aFileNameData = explode('.', $oDocument->file_name);  /*--}}
									<span class='document-icon'>
										@if(in_array(end($aFileNameData), array('doc','docx','pages','rtf','txt','wp')))
										{{--*/ $sIcon = 'fa fa-file-word-o' /*--}}
										@elseif (in_array(end($aFileNameData), array('numbers','xls','xlsx')))
										{{--*/ $sIcon = 'fa fa-file-excel-o' /*--}}
										@elseif (in_array(end($aFileNameData), array('key','ppt','pps','pptx')))
										{{--*/ $sIcon = 'fa fa-file-powerpoint-o' /*--}}
										@elseif (in_array(end($aFileNameData), array('zip','rar')))
										{{--*/ $sIcon = 'fa fa-file-archive-o' /*--}}
										@elseif (end($aFileNameData) == 'pdf')
										{{--*/ $sIcon = 'fa fa-file-pdf-o' /*--}}
										@else
										{{--*/ $sIcon = 'fa-file-image-o text-gray' /*--}}
										@endif
										<i class='fa {{ $sIcon }} text-gray'></i>
									</span>
								</div>
								{{-- file name --}}
								<div class="document-info document-name">
									{{-- <a 	target='_blank' href="{{ route('document.file-preview', ['nIdDocument' => $oDocument->id_document]) }}"  --}}
									<a 	target='_blank' href="javascript:void(0)" 
										data-id_client='{{ $oDocument->id_client  }}' 
										data-id_document='{{ $oDocument->id_document  }}' >
										{{ $oDocument->document_name }}
									</a>
								</div>
							@endif				
						</li>
					@endforeach
				@else
					@include('layouts.admin.no_documents')
				@endif
			</div>
		@endif
	</div>
</ul>