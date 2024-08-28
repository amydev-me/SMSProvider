<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<meta name="google-site-verification" content="7JH-OsKc1YpsKfYFa8Ogqu0CSmmryrZWNvc4usnjgL8"/>

	<meta name="description" content="@yield('description')"/>
	<title>@yield('title')</title>

	<link rel="shortcut icon" type="image/x-icon" href="{{asset('img/32.png')}}"/>

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
	<!-- Style -->
	<link href="{{ asset('css/app.min.css') }}" media="all" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('css/theme.min.css') }}" media="all" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('css/main.min.css') }}" media="all" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('css/material-design-iconic-font.min.css') }}" media="all" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('css/hamburgers.min.css') }}" media="all" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('css/animsition.min.css') }}" media="all" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('css/csstyles.css') }}" media="all" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('css/intlTelInput.min.css') }}" media="all" rel="stylesheet" type="text/css"/>

	<style type="text/css">
		[v-cloak] > * {
			display:none;
		}

		[v-cloak]::before {
			content: "loading...";
			color: blue;
		}

		.intl-tel-input {
			width:100%;
		}

		.intl-tel-input .selected-flag {
			z-index: 4;
		}

		.intl-tel-input .country-list {
			z-index: 5;
		}

		.input-group .intl-tel-input .form-control {
			border-top-left-radius: 4px;
			border-top-right-radius: 0;
			border-bottom-left-radius: 4px;
			border-bottom-right-radius: 0;
		}

		.iti-flag {
			background-image: url("/img/flags.png");
		}

		@media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2 / 1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {
			.iti-flag {
				background-image: url("/img/flags@2x.png");
			}
		}

		.text-danger {
			color: #dc3545 !important;
			font-size: 12px !important;
		}

		.alert-danger {
			font-size: 12px;
		}
	</style>
	
	@yield('style')
</head>
<body>
	<header>
		<nav id="main"  class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
			<div class="container">
				<a class="navbar-brand" href="{{ route('index') }}"><img alt="TripleSMS" src="{{ asset('img/triplesmswhite.png') }}" width="100%"></a>
				
				<button class="navbar-toggler" type="button" data-target="#navbarNavAltMarkup2" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
					<div class="navbar-nav">
					  @include('includes.nav')
					</div>
				</div>

				<div class="navbar-collapse" id="navbarNavAltMarkup">
					<div class="sidenav">
						<a href="javascript:void(0)" class="closebtn">&times;</a>
						@include('includes.nav')
					</div>
				</div>
			</div>
		</nav>
	</header>

	<content class="user" id="vue-web">
		@yield('content')
	</content>

	<footer>
		@include('includes.footer')
	</footer>

	<script type="text/javascript" src="{{asset('js/web/manifest.js') }}"></script>
	<script type="text/javascript" src="{{asset('js/web/webvendor.js') }}"></script>
	<script type="text/javascript" src="{{asset('js/web/webapp.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/animsition.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/bootstrap-tagsinput.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/intlTelInput.min.js') }}"></script>

	<script type="text/javascript">
		$(".navbar-toggler").click(function() {
			$('.sidenav').css('width', '100%');
			$('.sidenav .nav-item').css('text-align', 'center');
		});

		$(".closebtn").click(function() {
			$('.sidenav').css('width', '0');
		});
	</script>

	@yield('script')

</body>
</html>