@extends('app')
@section('page-title') | {{ ucfirst(str_plural(@$entity)) }} @stop
@section('content')
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <div class="error-page">
      <div class="error-content">
      <h3><i class="fa fa-warning text-yellow"></i> Oops! No Data Available.</h3>
        <p>

          @if($entity != 'survey')It looks like you haven't created any {{ strtolower(@$entity) }} yet. @if($url != '') Click {{ Html::linkRoute(@$url,'here') }} to create your first {{ strtolower(@$entity)  }}.@endif @endif
        </p>
    </div><!-- /.error-content -->
  </div><!-- /.error-page -->
</section><!-- /.content -->
</div><!-- /.content-wrapper -->
 @stop