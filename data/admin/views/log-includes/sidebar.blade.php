<!-- MENU SIDEBAR-->
<aside class="menu-sidebar d-none d-lg-block">
	<div class="logo">
		<a href="#">
			<img src="{{ asset('img/triplesmsblack.png') }}" alt="TripleSMS" style="width: 90%"/>
		</a>
	</div>

	<div class="menu-sidebar__content js-scrollbar1">
		<nav class="navbar-sidebar">
			<ul class="list-unstyled navbar__list">
				<li class="@yield('user') has-sub">
					<a href="{{ route('dashboard-user.index') }}">
						<i class="fas fa-users-cog"></i>Users
					</a>
				</li>

				<li class="@yield('search') has-sub">
					<a href="{{ route('dashboard-user.search') }}">
						<i class="fas fa-search"></i>Search SMS
					</a>
				</li>
			</ul>
		</nav>
	</div>
</aside>
<!-- END MENU SIDEBAR-->