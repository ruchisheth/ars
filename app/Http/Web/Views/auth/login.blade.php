<!DOCTYPE html>
<html>
<head>
  @include('layouts.web.head')
  @include('layouts.web.styles')
  <title>ARS | {{ trans('messages.login') }} </title>
</head>
<body class="hold-transition login-page front-page">

  <div class="form-container">
    <div class="front-page-detail">
      <div class="container">
        <div class="front-login-detail">
          <div class="col-md-6">
            <div class="description">
              <img src="{{ asset('public'.config('constants.LOGOWHITE')) }}">
            </div>
            <p>Drive Accountability.<br>
                Performance. Results.</p>
            <span>ARS Site Service Manager™ provides retail marketing organizations with a robust and highly customizable web-based solution that provides vital information to:</span>
            <span>Whether you wish to make better decisions, implement stronger customer relationships or manage performance goals, Site Service Manager™ provides the flexibility of analyzing integrated data from Web, IVR and hand-held data sources.</span>
          </div>
          {{ Form::open([
            'method'  =>  'POST',
            'enctype'  =>  "multipart/form-data"
        ]) }}
          <div class="col-md-6">
            <div class="front-login-form">
              <span class='login-title'>{{ trans('messages.login_to_yourr_account') }}</span>
              <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                <label>{{ trans('messages.your_email') }}</label>
                <div class="input-group">
                  <input type="text" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                </div>
                @if ($errors->has('email'))
                <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
              </div>

               <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                  <label>{{ trans('messages.your_password') }}</label>
                  <div class="input-group">
                    <input type="password" name="password" class="form-control" placeholder="Password">
                  </div>
                  @if ($errors->has('password'))
                  <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                  </span>
                  @endif
                </div>

                <div class="form-group {{ $errors->has('client_code') ? ' has-error' : '' }}">
                  <label>{{ trans('messages.client_code') }}</label>
                  <div class="input-group">
                    <input type="text" name="client_code" class="form-control" placeholder="Client Code" style="text-transform:uppercase">
                  </div>
                  @if ($errors->has('client_code'))
                  <span class="help-block">
                    <strong>{{ $errors->first('client_code') }}</strong>
                  </span>
                  @endif
                </div>

                <div class="row">
                  <div class="col-md-9">
                    <div class="checkbox icheck">
                      <label>
                        <input type="checkbox" name="remember" value="1"> 
                      </label><span>{{ trans('messages.remember_me') }}</span>
                    </div>
                  </div><!-- /.col -->
                  <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                      <i class="fa fa-btn fa-sign-in"></i> {{ trans('messages.sign_in') }}
                    </button>
                  </div><!-- /.col -->
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <a href="{{ url('/password/reset') }}">{{ trans('messages.i_forgot_my_password') }}</a>
                  </div>
                </div>

            </div>
          </div>
          {{ Form::close() }}
        </div>
      </div>
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
    });
   </script>
 </body>
 </html>
