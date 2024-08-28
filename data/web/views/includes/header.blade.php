<nav id="main" class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
	<div class="container">
		<a class="navbar-brand" href="{{ route('index') }}"><img alt="TripleSMS" src="{{ asset('img/triplesmswhite.png') }}" width="100%"></a>

		<button class="navbar-toggler" type="button" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
			<div class="navbar-nav">
				@include('includes.nav')
			</div>
		</div>

		<div class="navbar-collapse" id="navbarNavAltMarkup">
			<div id="mySidenav" class="sidenav">
				<a href="javascript:void(0)" class="closebtn">&times;</a>
				@include('includes.nav')
			</div>
		</div>
	</div>
</nav>

<nav id="welcome" class="navbar fixed-top navbar-expand-lg navbar-dark">
	<a class="navbar-brand" href="{{ route('index') }}"><img alt="TripleSMS" src="{{ asset('img/triplesmswhite.png') }}" width="100%"></a>

	<button class="navbar-toggler" type="button" data-target="#navbarNavAltMarkup2" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarNavAltMarkup2">
		<div class="navbar-nav" style="width:100%;">
			@include('includes.nav')
		</div>
	</div>
	
	<div class="navbar-collapse" id="navbarNavAltMarkup2">
		<div id="mySidenav" class="sidenav">
			<a href="javascript:void(0)" class="closebtn">&times;</a>
			@include('includes.nav')
		</div>
	</div>
</nav>