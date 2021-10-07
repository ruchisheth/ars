    <!-- jQuery 2.1.4 -->
    {{ Html::script(AppHelper::ASSETS.'plugins/jQuery/jQuery-2.1.4.min.js') }}

    <!-- jQuery UI 1.11.4 -->
    {{ Html::script('https://code.jquery.com/ui/1.11.4/jquery-ui.min.js') }}

    <!-- jQuery Form -->
    {{ Html::script(AppHelper::ASSETS.'plugins/jquery-form/js/jquery.form.min.js') }}

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>

    <!-- Bootstrap 3.3.5 -->
    {{ Html::script(AppHelper::ASSETS.'bootstrap/js/bootstrap.min.js') }}
    
    <!-- select2 -->
    {{ Html::script(AppHelper::ASSETS.'plugins/select2/select2.full.min.js') }}

    <!-- Input Mask -->
    {{ Html::script(AppHelper::ASSETS.'plugins/input-mask/jquery.inputmask.js') }}

    <!-- DataTables -->
    {{ Html::script(AppHelper::ASSETS.'plugins/datatables/jquery.dataTables.min.js') }}
    {{ Html::script(AppHelper::ASSETS.'plugins/datatables/dataTables.bootstrap.min.js') }}

    <!-- file input control -->
    {{ Html::script(AppHelper::ASSETS.'plugins/fileinput/js/fileinput.min.js') }}    

    <!-- jvectormap -->
    {{ Html::script(AppHelper::ASSETS.'plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}
    {{ Html::script(AppHelper::ASSETS.'plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}
    
    <!-- daterangepicker -->
    {{ Html::script(AppHelper::ASSETS.'dist/js/moment-2.10.2.min.js') }}
    {{ Html::script(AppHelper::ASSETS.'plugins/daterangepicker/daterangepicker.js') }}

    <!-- iCheck -->
    {{ Html::script(AppHelper::ASSETS.'plugins/iCheck/icheck.min.js') }}    

    <!-- toaster -->
    {{ Html::script(AppHelper::ASSETS.'dist/js/jquery.toaster.js') }}
    {{ Html::script(AppHelper::ASSETS.'dist/js/toastr.js') }}

    <!-- AdminLTE App -->
    {{ Html::script(AppHelper::ASSETS.'dist/js/app.min.js') }}

    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    {{ Html::script(AppHelper::ASSETS.'dist/js/pages/dashboard2.js') }}

    <!-- AdminLTE for demo purposes -->
    {{ Html::script(AppHelper::ASSETS.'dist/js/demo.js') }}

    <!-- Timezone Js -->
    {{ Html::script(AppHelper::ASSETS.'dist/js/jstz.min.js') }}

    <!-- Custom Js -->
    {{ Html::script(AppHelper::ASSETS.'dist/js/custom.js') }}