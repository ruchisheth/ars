{{-- fileName: _more_exported_survey_list_ajax.blade.php --}}
<input type='hidden' id='doc_type' name='doc_type'>
<input type="file" name="file[]" id="file_upload" class="hide" multiple/> 
<input type='hidden' id='id_parent' name='id_parent' value="{{ @$sDateDir }}">
<input type='hidden' id='id_client' name='id_client' value="{{ @$nIdClient }}">
<div class="table-responsive">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th style="width: 4%" class="text-center"></th>
				<th >@lang('messages.name')</th>
				<th style="width: 9%">&nbsp;</th>
			</tr>
		</thead>
		<tbody class="document-list-parent">
			@foreach($aDirectories as $sDirectory)
			@if($sDirectory != '.' && $sDirectory != '..')
			<tr data-id_client='' data-id_document=''>
				<td class="text-center">
					@if($sHourDir == NULL )
					<i class='fa fa-folder text-gray'></i>
					@else
					<i class='fa fa-file-excel-o text-gray'></i>
					@endif
				</td>
				<td >
					@if($sHourDir == NULL )
					<a 	href="javascript:void(0)" 
					class="jsdocumentLink document-name"
					data-dir_name='{{ $sDirectory  }}' 
					data-date_dir='{{ $sDateDir  }}'
					data-hour_dir='{{ $sHourDir  }}' >
					{{ $sDirectory }}</a>
					@else
					{{ $sDirectory }}
					@endif
				</td>
				<td>
					<div class="box-tools">
						{{-- <button class="btn btn-box-tool" type="button" name="remove_document" data-id_document="{{ $sDirectory }}" value="delete" title="delete document">
							<span class="fa fa-trash"></span>
						</button> --}}
						@if($sHourDir != NULL)
						{{-- <a href="{{ route('exported.survey.download', ['nIdDocument' => @$oDocument->id_document, 'sDocumentName' => @$oDocument->document_name]) }}" class="btn btn-box-tool"><i class="fa fa-download"></i></a> --}}
						@endif
					</div>
				</td>
			</tr>
			@endif
			@endforeach
		</tbody>
	</table>
</div>




