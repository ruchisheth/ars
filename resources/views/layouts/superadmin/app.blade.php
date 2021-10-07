<!DOCTYPE html>
<html>
    <head>
    @include('layouts.superadmin.head')

    @include('layouts.superadmin.styles')

    @yield('custom-style')
    </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        @include('layouts.superadmin.header')

        @include('layouts.superadmin.sidebar')
        
        @yield('content')

        @include('layouts.superadmin.confirm-modal')

        @include('layouts.superadmin.scripts')
        
        @yield('custom-script')
    </div>
 </body>
 </html>


 
