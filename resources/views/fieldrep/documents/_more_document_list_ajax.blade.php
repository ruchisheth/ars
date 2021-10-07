{{-- fileName: _more_document_list.ajax.blade.php --}}
<ol class="breadcrumb">
	@if($nIdParent != NULL)
	{{--*/ $aBreadCrumbs =  array_reverse(App\Libraries\Document::find($nIdParent)->ancestorsAndSelf()->pluck('document_name', 'id_document')->toArray(), true) /*--}}
	@foreach($aBreadCrumbs as $nIdDocument => $sDocumentName)
	<li>
		<a 	href="javascript:void(0)" class="jsdocumentLink" data-id_client='{{ $nIdClient  }}' data-id_document='{{ $nIdDocument  }}' >
			{{ $sDocumentName }}
		</a>
	</li>
	@endforeach
	@endif
	@if($nIdClient != NULL)
	{{-- */ $oClient =  App\Client::find($nIdClient) /*--}}
	<li>
		<a 	href="javascript:void(0)"  class="jsdocumentLink" data-id_client='{{ $oClient->id  }}' data-id_document='' >
			{{ $oClient->client_name }}
		</a>
	</li>
	@endif
	<li>
		<a href="javascript:void(0)"  class="jsdocumentLink"> {{ trans('messages.clients') }} </a>
		<i class='fa fa-user'></i>
	</li>
</ol>

<div class="table-responsive">
	<table class="table table-bordered">
		@if(!isset($nIdClient) || @$oClients != NULL)
		<thead>
			<tr>
				<th style="width: 4%" class="text-center"></th>
				<th>@lang('messages.clients')</th>
			</tr>
		</thead>
		<tbody>
			@foreach($oClients as $oClient)
			<tr>
				<td class="text-center">
					<i class='fa fa-user text-gray'></i>
				</td>
				<td>
					<a 	href="javascript:void(0)"  class="jsdocumentLink" data-id_client='{{ $oClient->id  }}' data-id_document='' >
						{{ $oClient->client_name }}
					</a>

				</td>
			</tr>
			@endforeach
		</tbody>
		@else
		@if(count($oDocuments))
		<thead>
			<tr>
				<th style="width: 4%" class="text-center"></th>
				<th>@lang('messages.folder_name')</th>
				<th style="width: 4%" class="text-center">&nbsp;</th>
			</tr>
		</thead>
		<tbody class="document-list-parent">
			@foreach($oDocuments as $oDocument)
			<tr>
				@if($oDocument->document_type == config('constants.DOCUMENTTYPEFOLDER'))
				<td class="text-center">
					<i class='fa fa-folder text-gray'></i>
				</td>
				<td>
					<a 	href="javascript:void(0)" 
					class="jsdocumentLink"
					data-id_client='{{ $oDocument->id_client  }}' 
					data-id_document='{{ $oDocument->id_document  }}' >
					{{ $oDocument->document_name.(count($oDocument->children) ? ' ...' : '') }}</a>
				</td>
				<td>&nbsp;</td>
				@elseif($oDocument->document_type == config('constants.DOCUMENTTYPEFILE'))
				<td class="text-center">
					{{--*/ $aFileNameData = explode('.', $oDocument->file_name);  /*--}}
					
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
				</td>
				<td>
					<a 	target='_blank' href="{{ route('document.file-preview', ['nIdDocument' => $oDocument->id_document, 'sDocumentName' => $oDocument->document_name]) }}" 
						data-id_client='{{ $oDocument->id_client  }}' 
						data-id_document='{{ $oDocument->id_document  }}' >
						{{ $oDocument->document_name }}
					</a>
				</td>
				<td>
					<div class="box-tools">
						@if($oDocument->document_type == config('constants.DOCUMENTTYPEFILE'))
						<a href="{{ route('document.download-file', ['nIdDocument' => $oDocument->id_document, 'sDocumentName' => $oDocument->document_name]) }}" class="btn btn-box-tool"><i class="fa fa-download"></i></a>
						@endif
					</div>
				</td>
				@endif
			</tr>
			@endforeach
		</tbody>
		@else
			@include('layouts.admin.no_documents')
		@endif
		@endif
	</table>
</div>




