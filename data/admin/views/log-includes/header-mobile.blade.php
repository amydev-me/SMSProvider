<!-- HEADER MOBILE--> 
<header class="header-mobile d-block d-lg-none">
	<div class="header-mobile__bar">
		<div class="container-fluid">
			<div class="header-mobile-inner">
				<a class="logo" href="index.html">
					<img src="{{ asset('img/triplesmsblack.png') }}" alt="TripleSMS" style="width: 50%"/>
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
		</div>
	</nav>
</header>
<!-- END HEADER MOBILE-->