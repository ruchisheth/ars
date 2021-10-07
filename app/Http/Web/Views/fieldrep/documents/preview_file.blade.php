<html>
<head>
    <title>{{ env('APP_NAME').' | '.$oDocument->document_name }}</title>
    @include('layouts.web.head')
    @include('layouts.web.styles')
</head>
<body>
    <div class="container-fluid col-lg-12 col-md-12 col-sm-12 co-xs-12 file-preview no-padding">
        @if(empty($sDisplayUrl))
        <ul>

            <li>
                {{--*/ $aFileNameData = explode('.', $oDocument->file_name);  /*--}}
                <span>
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
                    <i class='fa {{ $sIcon }} fa-5x '></i>
                </span>
            </li>
            <li>

                <span>{{ $oDocument->document_name }}</span>
                <a href="{{ route('document.download-file', ['nIdDocument' => $oDocument->id_document, 'sDocumentName' => $oDocument->document_name]) }}" 
                 class="btn btn-primary">{{ trans('messages.download') }}</a>
                {{-- <a href="{{ route('utility.download-file',[$sIdDocument,$sDisplayFileName]) }}">{{ trans('messages.download') }}</a> --}}

            </li>
        </ul>
        <span class="no-preview-available">{{ trans('messages.file_preview_not_available') }}</span>
        @else
        <iframe src="{{$sDisplayUrl}}" frameborder='0' width="100%"></iframe>
        @endif
    </div>
    <footer>
        <script type="text/javascript">
            $(document).ready(function() {
                $('iframe').css('height', $(window).height());
            });

            $(window).resize(function() {
                $('iframe').css('height', $(window).height());
            });
        </script>
    </footer>
</body>
</html>