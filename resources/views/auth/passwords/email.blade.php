<!DOCTYPE html>
<html>
<head>
  @include('layouts.admin.head')
  @include('layouts.admin.styles')
  <title>ARS | Login </title>
</head>
<body class="hold-transition login-page">

  <div class="form-container ">
    <div class="sign-in">
      <div class="description">
        <img src="{{ env('APP_URL').AppHelper::LOGO_WHITE}}">
        <p>ARS is a leading application service provider committed to delivering outstanding technology solutions for the Retail Marketing industry.</p><p>ARS collaborates with it's clients to help them maximize performance and manage their businesses better.</p>
      </div>

      {{-- <div class="reset-pwd"> --}}
      <div class="reset-pwd-email">
        {{ Form::open(array(
          'method'  =>  'POST',
          'url'  => '/password/email',
          )) 
        }}
        <h3 class="login-box-msg">Reset Password</h3>
      <div class="row">
        <div class="col-md-12">
         @include('includes.success')
         {{-- @include('includes.errors') --}}
       </div>

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

      <div class="row">
        <div class="col-md-5">
          <div class="checkbox icheck">
            <label>
              <a href="{{ route('login') }}"><i class="fa  fa-arrow-left"></i> back</a>
            </label>
          </div>
        </div><!-- /.col -->
        <div class="col-md-7">
          <button type="submit" class="btn btn-primary btn-block btn-flat">
            <i class="fa fa-btn fa-envelope"></i> Send Password Reset Link
          </button>
        </div><!-- /.col -->
      </div>

      {{ Form::close() }}
    </div>
    {{-- </div> --}}
  </div>
</div>
@include('layouts.admin.scripts')
</body>
</html>
