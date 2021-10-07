    <!-- jQuery 2.1.4 -->
    {{ Html::script(AppHelper::ASSETS.'plugins/jQuery/jQuery-2.1.4.min.js') }}

    <!-- jQuery UI 1.11.4 -->
    {{ Html::script(AppHelper::ASSETS.'dist/js/jquery-ui.min.js') }}
    
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
    
    <!-- tags input -->
    {{ Html::script(AppHelper::ASSETS.'plugins/taginput/bootstrap-tagsinput.js') }}


    {{ Html::script(AppHelper::ASSETS.'dist/js/jquery.maskedinput.min.js') }}
    {{ Html::script(AppHelper::ASSETS.'dist/js/jquery.maskssn.js') }}

    <!-- DataTables -->
    {{ Html::script(AppHelper::ASSETS.'plugins/datatables/jquery.dataTables.min.js') }}
    {{ Html::script(AppHelper::ASSETS.'plugins/datatables/dataTables.bootstrap.min.js') }}

    {{-- {{ Html::script('https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js') }} --}}

    {{-- dependent-dropdown --}}
    {{ Html::script(AppHelper::ASSETS.'plugins/dependent-dropdown/js/dependent-dropdown.min.js') }}

    <!-- file input control -->
    {{ Html::script(AppHelper::ASSETS.'plugins/fileinput/js/fileinput.js') }}   

    <!-- jvectormap -->
    {{-- {{ Html::script(AppHelper::ASSETS.'plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}  
    {{ Html::script(AppHelper::ASSETS.'plugins/jvectormap/jquery-jvectormap-us-aea-en.js') }} --}}
   
    {{ Html::script(AppHelper::ASSETS.'dist/js/moment-2.10.2.min.js') }}

    {{ Html::script(AppHelper::ASSETS.'plugins/daterangepicker/daterangepicker.js') }}

    <!-- iCheck -->
    {{ Html::script(AppHelper::ASSETS.'plugins/iCheck/icheck.min.js') }}

    <!-- wizard -->
    {{ Html::script(AppHelper::ASSETS.'plugins/bootstrap-wizard/js/jquery.bootstrap.wizard.min.js') }}

    <!-- toaster -->
    {{ Html::script(AppHelper::ASSETS.'dist/js/jquery.toaster.js') }}
    {{ Html::script(AppHelper::ASSETS.'dist/js/toastr.js') }}

    <!-- Time Picker -->
    {{ Html::script(AppHelper::ASSETS.'plugins/timepicker/bootstrap-timepicker.min.js') }}

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

    {{ Html::script(AppHelper::ASSETS.'plugins/select_all/select_all.js') }}

    
    <!--Shortcut Keys js -->
    {{ Html::script(AppHelper::ASSETS.'plugins/jquery-hotkeys/jquery.hotkeys.js') }}
    {{-- {{ Html::script(AppHelper::ASSETS.'dist/js/sorttable.js') }} --}}



    <!--Location -->    
    {{ Html::script('https://maps.googleapis.com/maps/api/js?key=AIzaSyAIi5irMwQvdQfkxXAGxlHxSwfs2RrUQxY ') }}
    
    <script type="text/javascript">
        $(document).ready(function(){
            $('.notifications-menu').click(function(e){
                var oElement = $(this);
                // if(oElement.hasClass('open') == false){
                //     oElement.find('.menu').html('');
                //     return false;
                // }
                var sURL = APP_URL+'/notification/notification-listing';
                $.ajax({
                    type: "GET",
                    url: sURL,
                    processData: false, 
                    contentType: false,
                    success: function (oResponse) {
                        oElement.find('.menu').html(oResponse.data.sHtml);
                    },
                    error: function (jqXHR, oException) {
                    }
                });
            });
        });
    </script>
 <!-- 
 1.AIzaSyADJIfa_pPbO41BcGrmcuupieg3rSoFCDE
 2.AIzaSyAIi5irMwQvdQfkxXAGxlHxSwfs2RrUQxY 


 ex:https://maps.google.com/maps/api/geocode/json?address='360001'&key=AIzaSyAIi5irMwQvdQfkxXAGxlHxSwfs2RrUQxY
-->
{{-- {{ Html::script('http://maps.google.com/maps/api/js?sensor=false') }} --}}
