@extends('fieldrep.app')
@section('page-title') | Offers @stop
@section('content')

<div class="content-wrapper">
  <section class="content">
    @include('fieldrep.offers_page')
    @include('fieldrep.instruction')
  </section>
  </div>
  @stop

  @section('custom-script')
  <script type="text/javascript">
    var offer_status = 'all';
  </script>
  {{ Html::script(AppHelper::ASSETS.'dist/js/pages/fieldrep_dashboard.js') }}
  @append