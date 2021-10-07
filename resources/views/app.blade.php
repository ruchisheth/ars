<!DOCTYPE html>
<html>
<head>
    {{--*/ $oLoggedInUser =  Auth::user(); /*--}}
    @include('layouts.admin.head')

    @include('layouts.admin.styles')

    @yield('custome-style')
    
</head>
<body class="hold-transition skin-{{ (@$clients_settings->theme_color) ? $clients_settings->theme_color : 'blue'}} sidebar-mini">
    <div class="wrapper">
        @include('layouts.admin.header')

        @if($oLoggedInUser->user_type == config('constants.USERTYPE.ADMIN'))
        @include('layouts.admin.sidebar')
        @elseif($oLoggedInUser->user_type == config('constants.USERTYPE.FIELDREP'))
        @include('fieldrep.includes.sidebar')
        @endif
        
        @yield('content')

        @include('layouts.admin.confirm-modal',['name'   => 'Profile'])

        @include('layouts.admin.scripts')
        
        @yield('custom-script')

        <div class="hide" id="overlay">
            <span class="text text-primary">
                <i class="fa fa-spinner fa-spin fa-5x"></i>
            </span>
        </div>
    </div>
</body>



