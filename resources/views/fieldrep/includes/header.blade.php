<header class="main-header">
  <a href="{{route('fieldrep.home')}}" class="logo">
    <span class="logo-mini">
      <img src="{{ asset('public'.config('constants.LOGOWHITE')) }}" alt="AlphaRep Service" title="AlphaRep Service" height="20px">
    </span>
    <span class="logo-lg">
      <img src="{{ asset('public'.config('constants.LOGOWHITE')) }}" alt="AlphaRep Service" title="AlphaRep Service" height="40px">
    </span>
  </a>
  <nav class="navbar navbar-static-top" role="navigation">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="dropdown notifications-menu">
          <a href="javascript::void(0)" class="dropdown-toggle test" data-toggle="dropdown" aria-expanded="true">
            <i class="fa fa-bell-o"></i>
            <span class="label label-danger">{{ ($nNotificationCount > 0) ? $nNotificationCount : '' }}</span>
          </a>
          <ul class="dropdown-menu">
            <li>
              <ul class="menu">
              </ul>
            </li>
          </ul>
        </li>
        <li class="dropdown user user-menu ">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="{{ getImageURL(config('constants.USERIMAGEFOLDER').'/'.@$profile->profile_pic) }}" class="user-image">
            <span class="hidden-xs">
              {{ $oLoggedInUser->UserDetails->first_name.' '.$oLoggedInUser->UserDetails->last_name   }}
            </span>
          </a>
          <ul class="dropdown-menu">
            <li class="user-header ">
              <img src="{{ getImageURL(config('constants.USERIMAGEFOLDER').'/'.@$profile->profile_pic) }}" class="img-square">
              <p>{{ $oLoggedInUser->UserDetails->first_name.' '.$oLoggedInUser->UserDetails->last_name   }}</p>
            </li>
            <li class="user-footer">
              <div class="pull-left">
                <a href="{{route("fieldrep.show.profile.get")}}" class="btn btn-default btn-flat">@lang('messages.profile')</a>
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

