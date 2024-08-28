<!-- MENU SIDEBAR-->
<aside class="menu-sidebar d-none d-lg-block">
	<div class="logo">
		<a href="{{ route('admin.index') }}">
			<img src="{{ asset('img/triplesmsblack.png') }}" alt="TripleSMS" style="width: 90%"/>
		</a>
	</div>

	<div class="menu-sidebar__content js-scrollbar1">
		<nav class="navbar-sidebar">
			<ul class="list-unstyled navbar__list">
				<li class="@yield('dashboard') has-sub">
					<a href="{{ route('admin.dashboard.index') }}">
						<i class="fas fa-tachometer-alt"></i>Dashboard
					</a>
				</li>

				<li class="@yield('compose') has-sub">
					<a href="{{ route('admin.compose') }}">
						<i class="fas fa-envelope"></i></i>Compose
					</a>
				</li>

				<li class="@yield('schedule') has-sub">
					<a href="{{ route('admin.schedule.index') }}">
						<i class="far fa-clock"></i>Schedule
					</a>
				</li>

				<li class="@yield('operator-log') has-sub">
					<a href="{{ route('admin.operator-log.index') }}">
						<i class="fas fa-tachometer-alt"></i>SMS Logs
					</a>
				</li>

				<li class="@yield('order') has-sub">
					<a href="{{ route('admin.order.index') }}">
						<i class="fas fa-receipt"></i>Orders (Package)
					</a>
				</li>

				<li class="@yield('payg-order') has-sub">
					<a href="{{ route('admin.payg-order') }}">
						<i class="fas fa-receipt"></i>Orders (PAYG)
					</a>
				</li>

				<li class="@yield('user') has-sub">
					<a href="{{ route('admin.user.index') }}">
						<i class="fas fa-users-cog"></i>Manage Users
					</a>
				</li>

				<li class="@yield('newsletter') has-sub">
					<a href="{{ route('admin.newsletter.index') }}">
						<i class="far fa-newspaper"></i></i>Newsletter
					</a>
				</li>

				<li class="@yield('api') has-sub">
					<a href="{{ route('admin.api.index') }}">
						<i class="fas fa-key"></i></i>API
					</a>
				</li>

				<hr/>

				<li class="@yield('package') has-sub">
					<a href="{{ route('admin.package') }}">
						<i class="fas fa-shopping-bag"></i>Packages
					</a>
				</li>

				<li class="@yield('country') has-sub">
					<a href="{{ route('admin.country') }}">
						<i class="fas fa-globe-americas"></i>Countries
					</a>
				</li>

				<li class="@yield('gateway') has-sub">
					<a href="{{ route('admin.gateway') }}">
						<i class="fas fa-door-open"></i>Gateway
					</a>
				</li>

				<li class="@yield('sender') has-sub">
					<a href="{{ route('admin.sender') }}">
						<i class="fas fa-user-friends"></i>Sender IDs
					</a>
				</li>

				<li class="@yield('article') has-sub">
					<a href="{{ route('admin.article') }}">
						<i class="fas fa-comments"></i>FAQ
					</a>
				</li>

				<li class="@yield('terms') has-sub">
					<a href="{{ route('admin.terms') }}">
						<i class="far fa-file-alt"></i>T & C
					</a>
				</li>

				<li class="@yield('setting') has-sub">
					<a href="{{ route('admin.setting.index') }}">
						<i class="zmdi zmdi-settings"></i>Setting
					</a>
				</li>

				@if (Auth::guard('admin')->user()->role == 3 || Auth::guard('admin')->user()->role == 4)
				<hr/>

				<li class="@yield('purchase') has-sub">
					<a href="{{ route('admin.purchase.index') }}">
						<i class="fas fa-shopping-cart"></i>Local Purchases
					</a>
				</li>

				<li class="@yield('intl-purchase') has-sub">
					<a href="{{ route('admin.intl-purchase.index') }}">
						<i class="fas fa-cart-plus"></i>Intl Purchases
					</a>
				</li>
				@endif

				@if (Auth::guard('admin')->user()->role == 4)
				<hr/>

				<li class="@yield('admin') has-sub">
					<a href="{{ route('admin.list') }}">
						<i class="fas fa-user-tie"></i>Manage Admins
					</a>
				</li>

				<li class="@yield('telecom') has-sub">
					<a href="{{ route('admin.telecom') }}">
						<i class="fas fa-broadcast-tower"></i>Telecom
					</a>
				</li>
				@endif
			</ul>
		</nav>
	</div>
</aside>
<!-- END MENU SIDEBAR-->