<!DOCTYPE html>
<html>
<head>
  @include('layouts.admin.head')
  @include('layouts.admin.styles')
  <title>ARS | Reset Password </title>
</head>
<body class="hold-transition login-page">

  <div class="form-container">{{-- pwd-reset --}}
    <div class="sign-in">
      <div class="description">
        <img src="{{ AppHelper::LOGO_WHITE}}">
        <p>ARS is a leading application service provider committed to delivering outstanding technology solutions for the Retail Marketing industry.</p><p>ARS collaborates with it's clients to help them maximize performance and manage their businesses better.</p>
      </div>
      <div class="">{{-- reset-pwd-i --}}

       {{ Form::open(array(
        'method'  =>  'post',
        'url'  => '/password/reset',
        'enctype'  =>  "multipart/form-data"
        )) }}
        <h3 class="login-box-msg">Reset Password</h3>
        <div class="row">
          <div class="col-md-12">
            @if (session('status'))
            <div class="alert alert-success">
              {{ session('status') }}
            </div>
            @endif
          {{-- @include('includes.success')
          @include('includes.errors') --}}
        </div>
      </div>
      <input type="hidden" name="token" value="{{ $token }}">
      <div class="row">
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
        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
          <label>Confirm Password</label>
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-hashtag"></i> </span>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
          </div>
          @if ($errors->has('password_confirmation'))
          <span class="help-block">
            <strong>{{ $errors->first('password_confirmation') }}</strong>
          </span>
          @endif
        </div>
        @endif

        <div class="row">
          <div class="col-md-6">
            <div class="checkbox icheck">
              <label>
                <a href="{{ route('login') }}"><i class="fa  fa-arrow-left"></i> back</a>
              </label>
            </div>
          </div><!-- /.col -->
          <div class="col-md-6">
            <button type="submit" class="btn btn-primary btn-block btn-flat">
              <i class="fa fa-btn fa-envelope"></i> Reset Password
            </button>
          </div><!-- /.col -->
        </div>

        {{ Form::close() }}

      </div>
    </div>
  </div>
  @include('layouts.admin.scripts')
</body>
</html>
