 <!--[if IE]>
        <meta HTTP-EQUIV="REFRESH" content="0; url=http://www.google.com">
    <![endif]-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{!! csrf_token() !!}"/>
    <meta http-equiv="cache-control" content="private, max-age=0, no-cache">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta name="_token" content="{{ csrf_token() }}" >
    <meta name="_base_url" content="{{ url('/') }}">
    <title>{{ env('APP_NAME') }}@yield('title')</title>
