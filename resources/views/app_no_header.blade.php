<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')

    @include('layouts.admin.styles')

    @yield('custome-style')
    
</head>
<body class="hold-transition skin-{{ (@$clients_settings->theme_color) ? $clients_settings->theme_color : 'blue'}} sidebar-mini">

    @yield('content')

    @include('layouts.admin.scripts')
    
    @yield('custom-script')
    <div class="hide" id="overlay">
        <span class="text text-primary">
            <i class="fa fa-spinner fa-spin fa-5x"></i>
        </span>
    </div>     
</body>
</html>



