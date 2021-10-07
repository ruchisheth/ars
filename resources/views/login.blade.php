<!DOCTYPE html>
<html>
<head>
  @include('layouts.admin.head')
  @include('layouts.admin.styles')
  <title>ARS | Login </title>
</head>
<body class="hold-transition login-page">

  <div class="form-container">
    <div class="sign-in">
      <div class="description">
        <img src="{{ asset(AppHelper::LOGO_WHITE) }}">
        <p>ARS is a leading application service provider committed to delivering outstanding technology solutions for the Retail Marketing industry.</p><p>ARS collaborates with it's clients to help them maximize performance and manage their businesses better.</p>

      </div>


      {{ Form::open([
                      'method'  =>  'POST',
                      'route'   =>  'login',
                      'enctype' =>  "multipart/form-data"
                    ]) 
      }}

       <h3 class="login-box-msg">Sign in</h3>
       
       <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
        <label>Your email</label>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-envelope-o"></i> </span>
          <input type="text" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
        </div>
        @if ($errors->has('email'))
        <span class="help-block">
          <strong>{{ $errors->first('email') }}</strong>
        </span>
        @endif
      </div>

      <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
        <label>Your password</label>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-lock"></i> </span>
          <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        @if ($errors->has('password'))
        <span class="help-block">
          <strong>{{ $errors->first('password') }}</strong>
        </span>
        @endif
      </div>
      
      @if(Route::currentRouteName() != 'super-admin.login')
      <div class="form-group {{ $errors->has('client_code') ? ' has-error' : '' }}">
        <label>Client Code</label>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-hashtag"></i> </span>
          <input type="text" name="client_code" value="" class="form-control" placeholder="Client Code" style="text-transform:uppercase">
        </div>
        @if ($errors->has('client_code'))
        <span class="help-block">
          <strong>{{ $errors->first('client_code') }}</strong>
        </span>
        @endif
      </div>
      @endif
      <div class="row">
        <div class="col-md-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" name="remember" value="1"> 
            </label> Remember Me
          </div>
        </div><!-- /.col -->
        <div class="col-md-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">
            <i class="fa fa-btn fa-sign-in"></i> Sign In
          </button>
        </div><!-- /.col -->
      </div>
      <div class="row">
        <div class="col-md-12">
          <a href="{{ url('/password/reset') }}">I forgot my password</a>
        </div>
      </div>
      {{ Form::close() }}

    </div>
  </div>

  <!-- jQuery 2.1.4 -->
  {{ Html::script(AppHelper::ASSETS.'plugins/jQuery/jQuery-2.1.4.min.js') }}

  <!-- Bootstrap 3.3.5 -->
  {{ Html::script(AppHelper::ASSETS.'bootstrap/js/bootstrap.min.js') }}

  <!-- iCheck -->
  {{ Html::script(AppHelper::ASSETS.'plugins/iCheck/icheck.min.js') }}

  <script>
    $(document).ready(function () { 


      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    })
     // $(document).ready(function(){
     //      function disableBack() {window.history.forward()}
     //      window.onload = disableBack();
     //  window.onpageshow = function(evt) {if(evt.persisted) disableBack()}
     //  });
   </script>



   <!-- jQuery 2.1.4 -->
   {{ Html::script(AppHelper::ASSETS.'plugins/jQuery/jQuery-2.1.4.min.js') }}

   <!-- Bootstrap 3.3.5 -->
   {{ Html::script(AppHelper::ASSETS.'bootstrap/js/bootstrap.min.js') }}

   <!-- iCheck -->
   {{ Html::script(AppHelper::ASSETS.'plugins/iCheck/icheck.min.js') }}

   <script>
    $(document).ready(function () { 


      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    })
     // $(document).ready(function(){
     //      function disableBack() {window.history.forward()}
     //      window.onload = disableBack();
     //  window.onpageshow = function(evt) {if(evt.persisted) disableBack()}
     //  });
   </script>
 </body>
 </html>
