<header class="main-header">
  <!-- Logo -->
  {{--*/ $user_role = Auth::user()->roles->slug /*--}}  {{-- Don't delete this line. special syntax to assign variable --}}
  @if('admin' == $user_role)
    <a href="{{route('admin.home')}}" class="logo"> 
  @elseif($user_role == 'fieldrep')
    <a href="{{route('fieldrep.home')}}" class="logo">
  {{-- @elseif(Auth::user()->hasrole('super_admin'))     --}}
  @elseif($user_role == 'super_admin')
      <a href="{{route('super_admin.home')}}" class="logo">
  @endif
    <span class="logo-mini">
      <img src="{{ asset(AppHelper::LOGO_WHITE) }}" alt="Alpha Rep Service" title="Alpha Rep Service" height="20px">
    </span>
    <span class="logo-lg">
      <img src="{{ asset(AppHelper::LOGO_WHITE) }}" alt="Alpha Rep Service" title="Alpha Rep Service" height="40px">
    </span>
  </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
              @if($user_role == 'fieldrep')
                <li class="dropdown notifications-menu">
                    <a href="javascript::void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-danger">{{ ($nNotificationCount > 0) ? $nNotificationCount : '' }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <ul class="menu"></ul>
                        </li>
                    </ul>
                </li>
            @endif
            <li class="dropdown user user-menu ">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                @if($user_role == 'super_admin')
                  {{ Html::image(AppHelper::IMAGE.AppHelper::AVATAR_IMG,"",['class'=>'user-image']) }}
                @else
                  @if(@$clients_settings->logo != '')
                    {{ Html::image(AppHelper::USER_IMAGE.@$clients_settings->logo,"",['class'=>'user-image']) }}
                  @else
                    {{ Html::image(AppHelper::IMAGE.AppHelper::AVATAR_IMG,"",['class'=>'user-image']) }}
                  @endif
                @endif
                <span class="hidden-xs">
                  @if($user_role == 'super_admin')
                    {{ Auth::user()->UserDetails->name }}
                  @elseif($user_role == 'admin')
                    {{ Auth::user()->UserDetails->name }}
                  @else
                    {{ Auth::user()->UserDetails->first_name }} {{ Auth::user()->UserDetails->last_name }}
                  @endif
                </span>
              </a>
              <ul class="dropdown-menu">
                <li class="user-header ">
                  @if($user_role == 'super_admin')
                    {{ Html::image(AppHelper::IMAGE.AppHelper::AVATAR_IMG,"",['class'=>'img-square']) }}
                    <p>ADMIN</p>
                  @else
                    @if(@$clients_settings->logo != '')
                      {{ Html::image(AppHelper::USER_IMAGE.@$clients_settings->logo,"",['class'=>'img-square']) }}
                    @else
                      {{ Html::image(AppHelper::IMAGE.AppHelper::AVATAR_IMG,"",['class'=>'img-square']) }}                      
                    @endif

                    @if($user_role == 'admin')
                      <p>{{ Auth::user()->UserDetails->name }}</p>
                    @elseif($user_role == 'fieldrep')
                      <p>{{ Auth::user()->UserDetails->first_name }} {{ Auth::user()->UserDetails->last_name }}</p>
                    @endif
                  @endif
                </li>
                
                <li class="user-footer">
                  <div class="pull-left">
                    @if($user_role == 'admin')
                    <a href="{{route("admin.profile")}}" class="btn btn-default btn-flat">Profile</a>
                    @elseif($user_role == 'fieldrep')
                    <a href="{{route("fieldrep.show.profile.get")}}" class="btn btn-default btn-flat">Profile</a>
                    @elseif($user_role == 'super_admin')
                    <a href="{{route("admin.profile")}}" class="btn btn-default btn-flat">Profile</a>            
                    @endif
                  </div>
                  <div class="pull-right">
                    <a href="{{ route("logout") }}" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>