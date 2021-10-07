@extends('layouts.superadmin.app')
@section('page-title') | Admin Profile @stop
@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        @include('SuperAdminView::profile_section')
      </div>
      <div class="col-md-6">
        @include('common.change_password')
      </div>
    </div>
  </section>
</div>

@stop