<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.web.head')

    @include('layouts.web.styles')

    @yield('custom-styles')

</head>

<body>
    <div id="app">
        @include('layouts.web.header')
	
        
        @yield('content')
        
        <footer>

            @include('layouts.web.scripts')

            <script type="text/javascript">
                @yield('custom-scripts')                    
            </script>
			
			<div class="copyright_foot">
				<p class="text-center"> {{ trans('messages.copyright') }}</p>
			</div>
        </footer>
        
    </div>
</body>
</html>
