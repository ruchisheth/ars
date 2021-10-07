<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')
    @include('layouts.admin.styles')
    <title>ARS | 404 </title>
  </head>
<body class="hold-transition">
<div class="wrapper">
 <section class="content">
      <div class="error-page">
        <h2 class="headline text-yellow"> 404</h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
          <p>  
            We could not find the page you were looking for.
            Meanwhile, you may return to        
             <a href="{{AppHelper::APP_URL}}" id="cancel" class="">dashboard</a>.            
          </p>       
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
  
</div>
<!-- ./wrapper -->


</body>
</html>
