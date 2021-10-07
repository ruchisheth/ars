<!DOCTYPE html>
<html>
    <head>
    @include('fieldrep.includes.head')

    @yield('custome-style')
    </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        @include('includes.header')

        @include('includes.sidebar')
        
        @yield('content')

        @include('includes.confirm-modal')

        @include('includes.footer')
        @include('includes.control_sidebar')
        @include('includes.scripts')
        
        @yield('custom-script')
 </body>
