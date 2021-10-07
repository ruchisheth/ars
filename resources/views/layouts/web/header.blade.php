<header class="main-header">
	<!-- Logo -->
	<a href="{{ url('/') }}" class="logo">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini">
			<img src="{{ asset('public'.config('constants.LOGO')) }}" alt="Alpha Rep Service" title="Alpha Rep Service" height="40px">
		</span>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg">
			<img src="{{ asset('public'.config('constants.LOGO')) }}" alt="Alpha Rep Service" title="Alpha Rep Service" height="40px">
		</span>
	</a>

	<!-- Header Navbar -->
	<nav class="navbar navbar-static-top" role="navigation">
		<!-- Navbar Right Menu -->
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						@if(Auth::user()->user_type == config('constants.USERTYPE.FIELDREP'))
							<img src="{{ asset('public/assets/web/img/user-thumbnail.png') }}" class="user-image" alt="User Image">
							<span class="hidden-xs">
								{{ Auth::user()->UserDetails->first_name.' '.Auth::user()->UserDetails->last_name }}
							</span>
						@elseif(Auth::user()->user_type == config('constants.USERTYPE.CLIENT'))
							<img src="{{ config('constants.CLIENTLOGOFOLDER').Auth::user()->UserDetails->client_logo }}" onerror=this.src="img/undefined.jpg" class="user-image" alt="User Image">
							<span class="hidden-xs">
								{{ Auth::user()->UserDetails->client_name }}
							</span>
						@endif
					</a>
					<ul class="dropdown-menu">
						<li class="user-footer">
							<a href="{{ route('fieldrep.account-setting') }}">
								<img src="{{ asset('public/assets/web/img/account-settings.png') }}" >
								{{ trans('messages.account_setting') }}
							</a>
						</li>
						<li class="user-footer">
							<a href="{{ route("logout") }}">
								<img src="{{ asset('public/assets/web/img/logout.png') }}" >
								{{ trans('messages.logout') }}
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>
