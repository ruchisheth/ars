<section id="header">

	<nav class="navbar navbar-default navbar-fixed-top normal">
		<div class="container">
			
			<!-- Brand and toggle get grouped for better mobile display -->

			<div class="navbar-header">
				<a class="navbar-brand" href="{{ url('/') }}"><img src="{{ config('constants.LOGO') }}"></a>
			</div>

			
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="{{ route('client.assignment-list') }}">
							<img class='icon-img' src="{{ asset('public/assets/web/img/asignment.png') }}"><span>ASSIGNMENTS</span>
						</a>
					</li>
					@if(Auth::user()->user_type == config('constants.USERTYPE.FIELDREP'))
					<li><a href="#"><img class='icon-img' src="{{ asset('public/assets/web/img/offers.png') }}"><span>OFFERS</span></a></li>
					<li><a href="#"><img class='icon-img' src="{{ asset("public/assets/web/img/calender.png") }}"><span>CALENDAR</span></a></li>
					@endif
					<li><a href="#"><img class='icon-img' src="{{ asset('public/assets/web/img/bell.png') }}"><span>NOTIFICATIONS</span></a></li>
					{{-- <li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<img class='icon-img' src="{{ asset('public/assets/web/img/setting.png') }}">
							<span>settings</span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="{{ url('client/profile') }}"><img class='nav-drop-img' src="{{asset('public/assets/web/img/profile.png')}}">Profile</a></li>
							<li><a href="{{ route("logout") }}"><img class='nav-drop-img' src="{{asset('public/assets/web/img/logout.png')}}">Sign out</a></li>
						</ul>
					</li> --}}
					<li class="dropdown user user-menu ">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">

							<img src="https://www.alpharepservice.com/public/assets/dist/img/user-thumbnail.png" class="user-image" alt="">
							<label class="hidden-xs">
								{{ App\Client::where(['id_user' => Auth::id()])->first()->client_name }}
							</label>
						</a>
						<ul class="dropdown-menu">
							<li><a href="{{ url('client/profile') }}"><img class='nav-drop-img' src="{{asset('public/assets/web/img/profile.png')}}">Profile</a></li>
							<li><a href="{{ route("logout") }}"><img class='nav-drop-img' src="{{asset('public/assets/web/img/logout.png')}}">Sign out</a></li>
						</ul>
					</li>
					{{-- <li><a href="#"><img src="public/assets/web/img/setting.png"><span>settings</span></a></li> --}}
				</ul>
			</div>
		</div>
	</nav>

	<!-- Fixed navbar -->
		 {{-- <nav id="header" class="navbar navbar-fixed-top">
            <div id="header-container" class="navbar-container">
            	<div class="container">
            		<div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a id="brand" class="navbar-brand" href="#"><img src="{{ config('constants.LOGO') }}"></a>
	                </div>
	                <div id="navbar" class="collapse navbar-collapse">
	                    <ul class="nav navbar-nav navbar-right">
					<li><a href="{{ route('client.assignment-list') }}"><img src="{{ asset('public/assets/web/img/asignment.png') }}"><span>assignments</span></a></li>
					@if(Auth::user()->user_type == config('constants.USERTYPE.FIELDREP'))
					<li><a href="#"><img src="{{ asset('public/assets/web/img/offers.png') }}"><span>offers</span></a></li>
					<li><a href="#"><img src="{{ asset("public/assets/web/img/calender.png") }}"><span>calendar</span></a></li>
					@endif
					<li><a href="#"><img src="{{ asset('public/assets/web/img/bell.png') }}"><span>notification</span></a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<img src="{{ asset('public/assets/web/img/setting.png') }}"><span>settings</span></a>
							<ul class="dropdown-menu">
								<li><a href="{{ url('client/profile') }}"><img class='nav-drop-img' src="{{asset('public/assets/web/img/profile.png')}}">profile</a></li>
								<li><a href="#"><img class='nav-drop-img' src="{{asset('public/assets/web/img/logout.png')}}">Sign out</a></li>
							</ul>
						</li>
				
					</ul>
	                </div>
            	</div>
            </div>
        </nav> --}}
        <!-- Fixed navbar -->
    </section>