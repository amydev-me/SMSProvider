<a title="Home - TripleSMS" class="nav-item nav-link @yield('home')"href="{{ route('index') }}">Home<span class="sr-only">(current)</span></a>
<a title="API Documentation" class="nav-item nav-link @yield('documentation')" href="{{ route('documentation') }}">Documentation</a>
@guest
	<a title="Affordable & Reliable Pricing" class="nav-item nav-link @yield('pricing')" href="{{ route('pricing') }}">Pricing</a>
	<a title="Create an Account" class="nav-item nav-link @yield('register')" href="{{ route('register') }}">Register</a>
	<a title="Sign in" class="nav-item nav-link @yield('login')" href="{{ route('login') }}">Sign in</a>
@else
	<a title="Affordable & Reliable Pricing" class="nav-item nav-link @yield('buy')" href="{{ route('buy') }}">Pricing</a>
	<a title="Dashboard" class="nav-item nav-link @yield('dashboard')" href="{{ route('dashboard.index') }}"> {{ Auth::guard('web')->user()->full_name }} </a>
@endguest