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
        <img src="{{ asset('public/'.config('constants.LOGOWHITE')) }}">
        <p>{!! trans('messages.ars_intro') !!}</p>
      </div>
      {{ Form::open(array(
        'method'  =>  'POST',
        'url'  => route('admin.login'),
        )) }}
        <h3 class="login-box-msg">{{ trans('messages.sign_in') }}</h3>
        

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

        <div class="row">
          <div class="col-md-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox" name="remember" value="1"> 
              </label> @lang('messages.remember_me')
            </div>
          </div><!-- /.col -->
          <div class="col-md-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">
              <i class="fa fa-btn fa-sign-in"></i> @lang('messages.sign_in')
            </button>
          </div><!-- /.col -->
        </div>
        <div class="row">
          <div class="col-md-12">
            <a href="{{ url('/password/reset') }}"> @lang('messages.i_forgot_my_password') </a>
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
   </script>
 </body>
 </html>
