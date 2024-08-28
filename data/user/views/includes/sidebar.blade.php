<!-- MENU SIDEBAR-->
<aside class="menu-sidebar d-none d-lg-block">
	<div class="logo center">
		<a href="/">
			<img src="{{ asset('img/triplesmsblack.png') }}" alt="TripleSMS" width="90%"/>
		</a>
	</div>

	<div class="menu-sidebar__content js-scrollbar1">
		<nav class="navbar-sidebar">
			<ul class="list-unstyled navbar__list">
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
		</nav>
	</div>
</aside>
<!-- END MENU SIDEBAR-->