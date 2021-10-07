<!DOCTYPE html>
<html>
<head>
  @include('layouts.admin.head')
  @include('layouts.admin.styles')
</head>
<body class="hold-transition feedback-page">
  <div class="fdbk-master">
    <div class="feedback-box">
      <div class="box no-border">
        <div class="box-header no-padding"><div class="fdbk-qest"> Thank You! </div></div>
        <div class="box-body">
        <div class="m-btm">
          <div class="text-success"><i class="fa fa-check fa-4x"></i></div>
          <h4>Your form has been successfully submitted!</h4>
          <p>Your opinions and comments are very important to us and we read every message that we receive.</p>
          <p>Our goal is to improve our service any way we can, and we appreciate that you've taken the time to fill out our feedback form.</p>
        </div>
        </div>
      </div>
      </div>
     {{--  <div class="fdbk-box">
        <div class="fdbk-wrapper box">
          <div class="fdbk-qest"> Thank You </div>

          <div class="box-body">
            <div class="row">
              <div class="col-md-12">         
                <p>Your Form has been successfully submitted!</p>

                <p>Your opinions and comments are very important to SEL and we read every message that we receive.</p>

                <p>Our goal is to improve our service any way we can, and we appreciate your taking the time to fill out our feedback form.</p>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
    </div>
    {{-- <div class="fdbk-box">
      <div class="fdbk-wrapper">
        <div class="fdbk-qest"> Thank You </div>
        
        <div class="fdbk-radio">
          <div class="row">
            <div class="col-xs-12">         
              <p>Your Form has been successfully submitted!</p>

              <p>Your opinions and comments are very important to SEL and we read every message that we receive.</p>

              <p>Our goal is to improve our service any way we can, and we appreciate your taking the time to fill out our feedback form.</p>
            </div>
          </div>
        </div>
      </div>
    </div> --}}
  </div>
</body>
{{ Html::script(AppHelper::ASSETS.'plugins/jQuery/jQuery-2.1.4.min.js') }}

<!-- Bootstrap 3.3.5 -->
{{ Html::script(AppHelper::ASSETS.'bootstrap/js/bootstrap.min.js') }}
</body>
</html>