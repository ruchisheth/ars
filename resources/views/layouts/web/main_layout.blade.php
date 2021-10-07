<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.web.head')

    @include('layouts.web.styles')

    @yield('custom-styles')

</head>

<body class="hold-transition fixed sidebar-mini sidebar-collapse skin-black-light">
    <div class="wrapper">
        {{-- <div id="app"> --}}
            @include('layouts.web.header')

            @include('layouts.web.sidebar')

            <div class="content-wrapper">
                    @yield('content')
            </div>


            <!-- Main Footer -->
            <footer class="main-footer">
                <!-- To the right -->
                <div class="pull-right hidden-xs">
                    Anything you want
                </div>
                <!-- Default to the left -->
                {{-- <strong>Copyright &copy; 2016 <a href="#">Company</a>.</strong> All rights reserved. --}}
                <strong>{{ trans('messages.copyright') }}</strong>
                @include('layouts.web.scripts')
                @yield('custom-scripts')                    
            </footer>
        {{-- </div> --}}
    </div>
</body>
</html>
