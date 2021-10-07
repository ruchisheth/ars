<!DOCTYPE html>
<html>
    <head>
    {{--*/ $oLoggedInUser =  Auth::user(); /*--}}
    @include('fieldrep.includes.head')

    @include('fieldrep.includes.styles')

    @yield('custome-style')
    </head>
  <!-- <body class="hold-transition skin-blue sidebar-mini"> -->
  <body class="hold-transition skin-{{ (@$clients_settings->theme_color) ? $clients_settings->theme_color : 'blue'}} sidebar-mini">
    <div class="wrapper">
        @include('fieldrep.includes.header')

        @include('fieldrep.includes.sidebar')
        
        @yield('content')

        @include('fieldrep.includes.confirm-modal')

        @include('fieldrep.includes.scripts')
        
        @yield('custom-script')
    </div>
 </body>
 </html>


 
