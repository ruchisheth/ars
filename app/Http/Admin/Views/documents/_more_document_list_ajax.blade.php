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


<input type='hidden' id='doc_type' name='doc_type'>
<input type="file" name="file[]" id="file_upload" class="hide" multiple/> 
<input type='hidden' id='id_parent' name='id_parent' value="{{ @$nIdParent }}">
<input type='hidden' id='id_client' name='id_client' value="{{ @$nIdClient }}">
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
		@elseif(count($oDocuments))
		<thead>
			<tr>
				<th style="width: 4%" class="text-center"></th>
				<th >@lang('messages.name')</th>
				<th style="width: 9%">&nbsp;</th>
			</tr>
		</thead>
		<tbody class="document-list-parent">
			@foreach($oDocuments as $oDocument)
			<tr data-id_client='{{ $oDocument->id_client  }}' data-id_document='{{ $oDocument->id_document  }}'>
				@if($oDocument->document_type == config('constants.DOCUMENTTYPEFOLDER'))
				<td class="text-center">
					<i class='fa fa-folder text-gray'></i>
				</td>
				<td >
					<a 	href="javascript:void(0)" 
					class="jsdocumentLink document-name"
					data-id_client='{{ $oDocument->id_client  }}' 
					data-id_document='{{ $oDocument->id_document  }}' >
					{{ $oDocument->document_name. (count($oDocument->children) ? ' ...' : '') }}</a>
				</td>
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
					<a 	target='_blank' class="document-name" href="{{ route('document.file-preview', ['nIdDocument' => $oDocument->id_document, 'sDocumentName' => $oDocument->document_name]) }}" 
						data-id_client='{{ $oDocument->id_client  }}' 
						data-id_document='{{ $oDocument->id_document  }}' >
						{{ $oDocument->document_name }}
					</a>
					<span class="file-name hide">{{ basename($oDocument->document_name, '.'.pathinfo($oDocument->document_name, PATHINFO_EXTENSION)) }}</span>
				</td>
				@endif
				<td>
					<div class="box-tools">
						<button class="btn btn-box-tool" type="button" name="rename_document" data-id_document="{{ $oDocument->id_document }}" value="" title="rename document">
							<span class="fa fa-edit"></span>
						</button>
						<button class="btn btn-box-tool" type="button" name="remove_document" data-id_document="{{ $oDocument->id_document }}" value="delete" title="delete document">
							<span class="fa fa-trash"></span>
						</button>
						@if($oDocument->document_type == config('constants.DOCUMENTTYPEFILE'))
						<a href="{{ route('document.download-file', ['nIdDocument' => $oDocument->id_document, 'sDocumentName' => $oDocument->document_name]) }}" class="btn btn-box-tool"><i class="fa fa-download"></i></a>
						@endif
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
		@else
		<thead>
			<tr>
				<th style="width: 4%" class="text-center"></th>
				<th colspan="2">@lang('messages.name')</th>
			</tr>
		</thead>
		<tbody class="document-list-parent">
			<tr>
				<td colspan="3">@include('layouts.admin.no_documents')</td>
			</tr>
		</tbody>
		@endif
	</table>
</div>




