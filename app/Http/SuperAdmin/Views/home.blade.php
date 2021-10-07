@if(Auth::check())
@include('SuperAdminView::dashboard')
@else
@include('SuperAdminView::auth.login')
@endif
