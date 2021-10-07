@extends('app_no_header')
@section('page-title') | Assignments @stop
@section('content')

<div class="content-wrapper fill-survey">
	<section class="content">
	
		 <iframe src="http://docs.google.com/gview?url=http://infolab.stanford.edu/pub/papers/google.pdf&embedded=true" style="width:100%; height:800px;" frameborder="0"></iframe>
		 {{-- <iframe src="http://docs.google.com/gview?url=http://alpharepservice.com/beta/public/assets/images/instruction_img/test doc.docx&embedded=true" style="width:100%; height:800px;" frameborder="0"></iframe> --}}
		 {{-- <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=http://alpharepservice.com/beta/public/assets/images/instruction_img/test doc.docx' width='100%' height='800px' frameborder='0'>This is an embedded <a target='_blank' href='http://office.com'>Microsoft Office</a> document, powered by <a target='_blank' href='http://office.com/webapps'>Office Online</a>.</iframe> --}}


		{{-- <div class="kv-zoom-body file-zoom-content"> --}}
{{-- <embed class="kv-preview-data file-zoom-detail" src="http://infolab.stanford.edu/pub/papers/google.pdf" type="application/pdf" internalinstanceid="9" style="width: 100%; height: 100%; min-height: 800px;" title=""> --}}
{{-- <embed class="kv-preview-data file-zoom-detail" src="http://alpharepservice.com/beta/public/assets/images/instruction_img/test doc.docx" type="application/pdf" internalinstanceid="9" style="width: 100%; height: 100%; min-height: 800px;" title=""> --}}
{{-- </div> --}}

	</section>
</div>
@stop

@section('custom-script')
<script type="text/javascript">
	$(document).ready(function(){
		$('.ndfHFb-c4YZDc-Wrql6b').css('opacity',0);
	});
</script>
	
@stop