<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')
    @include('layouts.admin.styles')
    <title>ARS | 500 </title>
  </head>
<body class="hold-transition">
<div class="wrapper">
 <section class="content">
      <div class="error-page">
        <h2 class="headline text-red"> 500</h2>

        <div class="error-content">  
        @if($code == '1049' || $code == '1044')
          <h3><i class="fa fa-warning text-red"></i> Oops!</h3>
          <p>  
           You have reached database limit, Please contact support to add more clients
            Meanwhile, you may return to        
             <a href="{{AppHelper::APP_URL}}" id="cancel" class="">dashboard</a>.            
          </p>
        @elseif($code == '42S02') 
        	<h3><i class="fa fa-warning text-red"></i> Oops!</h3>
          <p>
          {{ $message }}  
           Looks like base table or view not found.<br>
            Meanwhile, you may return to      
             <a href="{{AppHelper::APP_URL}}" id="cancel" class="">dashboard</a>.            
          </p>
        @elseif($code == '1062') 
          <h3><i class="fa fa-warning text-red"></i> Oops!</h3>
          <p>  
           Integrity constraint violation: 1062 Duplicate entry for PRIMARY KEY<br>
            Meanwhile, you may return to      
             <a href="{{AppHelper::APP_URL}}" id="cancel" class="">dashboard</a>.            
          </p>
        
        @else
        	<h3><i class="fa fa-warning text-red"></i>Oops! Something went wrong.</h3>
          <p>            
            {{-- Meanwhile, you may return to      
             <a href="{{AppHelper::APP_URL}}" id="cancel" class="">dashboard</a>.  --}}     
             {{$message}}      
          </p>
        @endif      
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
  
</div>
<!-- ./wrapper -->


</body>
</html>
