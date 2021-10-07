<header class="main-header">
  <!-- Logo -->
  {{--*/ $oSuperAdminProfile = Auth::user()->UserDetails /*--}}  {{-- Don't delete this line. special syntax to assign variable --}}
  <a href="{{route('home')}}" class="logo">
    <span class="logo-mini">
      <img src="{{ asset('public'.config('constants.LOGOWHITE')) }}" alt="Alpha Rep Service" title="Alpha Rep Service" height="20px">
    </span>
    <span class="logo-lg">
      <img src="{{ asset('public'.config('constants.LOGOWHITE')) }}" alt="Alpha Rep Service" title="Alpha Rep Service" height="40px">
    </span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only"></span>
    </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="dropdown user user-menu ">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="{{ getImageURL(config('constants.USERIMAGEFOLDER')).'/'.$oSuperAdminProfile->profile_pic }}" class="user-image">
            <span class="hidden-xs">{{ $oSuperAdminProfile->name }}</span>
          </a>
          <ul class="dropdown-menu">
            <li class="user-header ">
              <img src="{{ getImageURL(config('constants.USERIMAGEFOLDER')).'/'.$oSuperAdminProfile->profile_pic }}" class="img-square">
              <p>{{ $oSuperAdminProfile->name }}</p>
            </li>

            <li class="user-footer">
              <div class="pull-left">
                <a href="{{route("admin.profile")}}" class="btn btn-default btn-flat">{{ trans('messages.profile') }}</a>            
              </div>
              <div class="pull-right">
                <a href="{{ route("logout") }}" class="btn btn-default btn-flat">{{ trans('messages.sign_out') }}</a>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>