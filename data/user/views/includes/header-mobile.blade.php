<!-- HEADER MOBILE-->
<header class="header-mobile d-block d-lg-none">
	<div class="header-mobile__bar">
		<div class="container-fluid">
			<div class="header-mobile-inner">
				<a class="logo" href="/">
					<img src="{{ asset('img/triplesmsblack.png') }}" alt="TripleSMS" width="50%"/>
				</a>

				<button class="hamburger hamburger--slider" type="button">
					<span class="hamburger-box">
						<span class="hamburger-inner"></span>
					</span>
				</button>
			</div>
		</div>
	</div>

	<nav class="navbar-mobile">
		<div class="container-fluid">
			<ul class="navbar-mobile__list list-unstyled">
				<li class="@yield('dashboard') has-sub">
					<a href="{{ route('dashboard.index') }}">
						<i class="fas fa-tachometer-alt"></i>Dashboard
					</a>
				</li>

				<li class="@yield('compose') has-sub">
					<a href="{{ route('compose') }}">
						<i class="far fa-envelope"></i>Compose
					</a>
				</li>

				<li class="@yield('history') has-sub">
					<a href="{{ route('logs') }}">
						<i class="fas fa-tachometer-alt"></i>SMS Logs
					</a>
				</li>

				<li class="@yield('schedule') has-sub">
					<a href="{{ route('schedule.index') }}">
						<i class="far fa-clock"></i>Schedule List
					</a>
				</li>

				<div class="line-break"></div>

				<li class="@yield('contact-list') has-sub">
					<a href="{{ route('contact.index') }}">
						<i class="fas fa-user"></i>Contacts
					</a>
				</li>

				<li class="@yield('address-book') has-sub">
					<a href="{{ route('address-book.index') }}">
						<i class="fas fa-address-book"></i>Address Book
					</a>
				</li>

				<div class="line-break"></div>

				@if (Auth::guard('web')->user()->sms_type != 'PAYG')
				<li class="@yield('pricing') has-sub">
					<a href="{{ route('buy') }}">
						<i class="fas fa-shopping-cart"></i>Buy
					</a>
				</li>
				@endif

				<li class="@yield('invoice') has-sub">
					<a href="{{ route('invoices') }}">
						<i class="fas fa-receipt"></i>Invoice
					</a>
				</li>

				<li class="@yield('apikey') has-sub">
					<a href="{{ route('rest_api.api_keys') }}">
						<i class="fas fa-key"></i>API Keys
					</a>
				</li>

				<li class="@yield('documentation') has-sub">
					<a href="{{ route('api.documentation') }}">
						<i class="fas fa-archive"></i>Documentation
					</a>
				</li>
			</ul>
		</div>
	</nav>
</header>
<!-- END HEADER MOBILE-->