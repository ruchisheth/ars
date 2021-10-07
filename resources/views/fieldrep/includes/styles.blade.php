    <!-- Bootstrap 3.3.5 -->
    {!! Html::style(AppHelper::ASSETS.'bootstrap/css/bootstrap.min.css',array('async' => 'async')) !!}

    <!-- datatables -->
    {!! Html::style(AppHelper::ASSETS.'plugins/datatables/dataTables.bootstrap.css') !!}
    {{-- {!! Html::style('https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css') !!} --}}
    
    {{-- dependent dropdown --}}
    {!! Html::style(AppHelper::ASSETS.'plugins/dependent-dropdown/css/dependent-dropdown.min.css') !!}

    <!-- file input control -->
    {!! Html::style(AppHelper::ASSETS.'plugins/fileinput/css/fileinput.min.css') !!}

    <!-- Font Awesome -->
    {!! Html::style(AppHelper::ASSETS.'font-awesome/css/font-awesome.min.css') !!}

    <!-- Ionicons -->
    {!! Html::style(AppHelper::ASSETS.'ionicons/css/ionicons.min.css') !!}

    {{ Html::style(AppHelper::ASSETS.'plugins/select2/select2.min.css') }}
    <!-- Theme style -->
    {!! Html::style(AppHelper::ASSETS.'dist/css/AdminLTE.min.css') !!}

    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    {!! Html::style(AppHelper::ASSETS.'dist/css/skins/_all-skins.css') !!}
    <!-- iCheck -->
    {!! Html::style(AppHelper::ASSETS.'plugins/iCheck/flat/blue.css') !!}
    {!! Html::style(AppHelper::ASSETS.'plugins/iCheck/all.css') !!}
    {!! Html::style(AppHelper::ASSETS.'plugins/iCheck/square/blue.css') !!}

    <!-- jvectormap -->
    {{-- {!! Html::style(AppHelper::ASSETS.'plugins/jvectormap/jquery-jvectormap-1.2.2.css') !!} --}}
    
    <!-- tags input -->
    {{ Html::style(AppHelper::ASSETS.'plugins/taginput/bootstrap-tagsinput.css') }}

    <!-- Daterange picker -->
    {!! Html::style(AppHelper::ASSETS.'plugins/daterangepicker/daterangepicker-bs3.min.css') !!}

    <!-- Toaster -->
    {!! Html::style(AppHelper::ASSETS.'dist/css/toastr.css') !!}      

    <!-- Time Picker -->
    {{ Html::style(AppHelper::ASSETS.'plugins/timepicker/bootstrap-timepicker.min.css') }}

     {{-- Html::style(AppHelper::ASSETS.'dist/css/custom.css')  --}} 

    <!-- <link rel="stylesheet" type="text/css" href="http://wts/ars/public/assets/dist/css/custom.css?v=<?php echo time();?>" /> --> 
     {{ Html::style(AppHelper::ASSETS.'dist/css/custom.css') }}


   
    {!! Html::style(AppHelper::ASSETS.'dist/css/responsive.css') !!}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
