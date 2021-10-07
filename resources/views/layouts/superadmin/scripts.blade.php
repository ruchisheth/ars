    <!-- jQuery 2.1.4 -->
    {!! Html::script(asset('public/assets/plugins/jQuery/jQuery-2.1.4.min.js')) !!}

    <!-- jQuery UI 1.11.4 -->
    {!! Html::script(asset('public/assets/dist/js/jquery-ui.min.js')) !!}
    
    <!-- jQuery Form -->
    {!! Html::script(asset('public/assets/plugins/jquery-form/js/jquery.form.min.js')) !!}

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
     $.widget.bridge('uibutton', $.ui.button);
    </script>

    <!-- Bootstrap 3.3.5 -->
    {!! Html::script(asset('public/assets/bootstrap/js/bootstrap.min.js')) !!}

    <!-- select2 -->
    {!! Html::script(asset('public/assets/plugins/select2/select2.full.min.js')) !!}

    <!-- Input Mask -->
    {!! Html::script(asset('public/assets/plugins/input-mask/jquery.inputmask.js')) !!}
    
    <!-- tags input -->
    {!! Html::script(asset('public/assets/plugins/taginput/bootstrap-tagsinput.js')) !!}


    {!! Html::script(asset('public/assets/dist/js/jquery.maskedinput.min.js')) !!}
    {!! Html::script(asset('public/assets/dist/js/jquery.maskssn.js')) !!}

    <!-- DataTables -->
    {!! Html::script(asset('public/assets/plugins/datatables/jquery.dataTables.min.js')) !!}
    {!! Html::script(asset('public/assets/plugins/datatables/dataTables.bootstrap.min.js')) !!}

    {{-- {!! Html::script('https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js') !!} --}}

    {{-- dependent-dropdown --}}
    {!! Html::script(asset('public/assets/plugins/dependent-dropdown/js/dependent-dropdown.min.js')) !!}

    <!-- file input control -->
    {!! Html::script(asset('public/assets/plugins/fileinput/js/fileinput.js')) !!}   

    <!-- jvectormap -->
    {{-- {!! Html::script(asset('public/assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')) !!}  
    {!! Html::script(asset('public/assets/plugins/jvectormap/jquery-jvectormap-us-aea-en.js')) !!} --}}
   
    {!! Html::script(asset('public/assets/dist/js/moment-2.10.2.min.js')) !!}

    {!! Html::script(asset('public/assets/plugins/daterangepicker/daterangepicker.js')) !!}

    <!-- iCheck -->
    {!! Html::script(asset('public/assets/plugins/iCheck/icheck.min.js')) !!}

    <!-- wizard -->
    {!! Html::script(asset('public/assets/plugins/bootstrap-wizard/js/jquery.bootstrap.wizard.min.js')) !!}

    <!-- toaster -->
    {!! Html::script(asset('public/assets/dist/js/jquery.toaster.js')) !!}
    {!! Html::script(asset('public/assets/dist/js/toastr.js')) !!}

    <!-- Time Picker -->
    {!! Html::script(asset('public/assets/plugins/timepicker/bootstrap-timepicker.min.js')) !!}

    <!-- AdminLTE App -->
    {!! Html::script(asset('public/assets/dist/js/app.min.js')) !!}

    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    {!! Html::script(asset('public/assets/dist/js/pages/dashboard2.js')) !!}

    <!-- AdminLTE for demo purposes -->
    {!! Html::script(asset('public/assets/dist/js/demo.js')) !!}

    <!-- Timezone Js -->
    {!! Html::script(asset('public/assets/dist/js/jstz.min.js')) !!}

    <!-- Custom Js -->
    {!! Html::script(asset('public/assets/dist/js/custom.js')) !!}

    {!! Html::script(asset('public/assets/plugins/select_all/select_all.js')) !!}

    
    <!--Shortcut Keys js -->
    {!! Html::script(asset('public/assets/plugins/jquery-hotkeys/jquery.hotkeys.js')) !!}
    {{-- {!! Html::script(asset('public/assets/dist/js/sorttable.js')) !!} --}}



    <!--Location -->    
    {!! Html::script('https://maps.googleapis.com/maps/api/js?key=AIzaSyAIi5irMwQvdQfkxXAGxlHxSwfs2RrUQxY ') !!}
 <!-- 
 1.AIzaSyADJIfa_pPbO41BcGrmcuupieg3rSoFCDE
 2.AIzaSyAIi5irMwQvdQfkxXAGxlHxSwfs2RrUQxY 

 ex:https://maps.google.com/maps/api/geocode/json?address='360001'&key=AIzaSyAIi5irMwQvdQfkxXAGxlHxSwfs2RrUQxY
-->
{{-- {!! Html::script('http://maps.google.com/maps/api/js?sensor=false') !!} --}}
