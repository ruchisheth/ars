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
    <div class="error-page survey-error">

      <div class="col-md-8">
        <div class="error-content survey">
          <h1>You Don't Have permission to access this Survey</h1>
          <p>You are signed in as {{ Auth::user()->email}}.</p>
          {{-- <a href="{{AppHelper::APP_URL}}" class="btn btn-primary " id="cancel" class="">Sign in as Different User</a>   --}}
          <a href="{{ route("logout") }}" 
          class="btn btn-primary" 
          onclick="event.preventDefault(); 
          document.getElementById('logout-form').submit();">
          Sign in as Different User</a>
          <form id="logout-form" action="{{ url('/logout') }}" method="GET" style="display: none;">
          {{ csrf_field() }}
          </form>
        <span>or Go Back to <a href="{{route('fieldrep.home')}}">Home</a></span>
      </div>
    </div>

    <div class="col-md-4">
      <img class="survey-error-img" src="{{AppHelper::APP_URL}}{{AppHelper::ASSETS}}dist/img/locked_doc-2.svg">
    </div> 
    <!-- /.error-content -->
  </div>
  <!-- /.error-page -->
</section>

</div>
<!-- ./wrapper -->


</body>
</html>
