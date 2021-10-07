<!DOCTYPE html>
<html>
<head>
  @include('layouts.admin.head')
  @include('layouts.admin.styles')
  <title>ARS | 555 </title>
</head>
<body class="hold-transition">
  <div class="wrapper">
   <section class="content">
    <div class="error-page">

      <!-- <div class="col-md-8"> -->
        <div class="error-content">
          <h1>Survey is Not Available.!</h1>
          <!-- <p>Survey is Not Available.</p> -->
        
        <center><span>Go Back to <a href="{{route('fieldrep.home')}}">Home</a></span></center>
      </div>
    <!-- </div> -->

    <!-- <div class="col-md-4">
      <img class="survey-error-img" src="{{AppHelper::APP_URL}}{{AppHelper::ASSETS}}dist/img/locked_doc-2.svg">
    </div>  -->
    <!-- /.error-content -->
  </div>
  <!-- /.error-page -->
</section>

</div>
<!-- ./wrapper -->


</body>
</html>
