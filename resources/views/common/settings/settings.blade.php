@extends('app')
@section('page-title') | Settings @stop
@section('content')
{{--*/ $oLoggedInUser =  Auth::user(); /*--}}
<div class="content-wrapper">
  <section class="content">
    <div class="row">
        @include('common.settings.general_settings')
      
        @if($oLoggedInUser->user_type == config('constants.USERTYPE.ADMIN'))
		    @include('admin.settings.lists')
	    @endif
    </div>
    @if($oLoggedInUser->user_type == config('constants.USERTYPE.ADMIN'))
		<div class="row">
			<div class="col-md-6">
				<div class="ftp_settings">
					@include('AdminView::settings.ftp_settings')
				</div>
			</div>
		</div>
	@endif
  </section>
</div>
@stop