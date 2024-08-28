<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<meta name="google-site-verification" content="7JH-OsKc1YpsKfYFa8Ogqu0CSmmryrZWNvc4usnjgL8"/>

	<meta name="description" content="@yield('description')"/>
	<meta name="keywords" content="@yield('keywords')"/>
	<title>@yield('title')</title>

	<link href="{{ asset('img/32.png') }}" rel="shortcut icon" type="image/x-icon"/>

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet"/>
	<link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous"/>
	<!-- Style -->
	<link href="{{ asset('css/app.min.css') }}" media="all" rel="stylesheet"/>
	<link href="{{ asset('css/theme.min.css') }}" media="all" rel="stylesheet"/>
	<link href="{{ asset('css/main.min.css') }}" media="all" rel="stylesheet"/>
	<link href="{{ asset('css/material-design-iconic-font.min.css') }}" media="all" rel="stylesheet"/>
	<link href="{{ asset('css/hamburgers.min.css') }}" media="all" rel="stylesheet"/>
	<link href="{{ asset('css/animsition.min.css') }}" media="all" rel="stylesheet"/>
	<link href="{{ asset('css/csstyles.css') }}" media="all" rel="stylesheet"/>
	<link href="{{ asset('css/intlTelInput.min.css') }}" media="all" rel="stylesheet"/>

	<style>
		[v-cloak] > * {
			display:none;
		}

		[v-cloak]::before {
			content: "loading...";
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

		.divider {
			padding: 0.5px;
			background-color: #495057;
		}

		footer {
			background-color: #343a40;
		}
	</style>

	@yield('style')
</head>
<body>
	<header>
		@include('includes.header')
	</header>

	<content class="user" id="vue-web">
		@yield('content')

		<!-- Load Facebook SDK for JavaScript -->
		<div id="fb-root"></div>

		<!-- Your customer chat code -->
		<div class="fb-customerchat" attribution=setup_tool page_id="217105992309964"></div>
	</content>

	@include('includes.footer-nav')
	
	<footer>
		@include('includes.footer')
	</footer>

	<script src="{{ asset('js/web/manifest.js') }}"></script>
	<script src="{{ asset('js/web/webvendor.js') }}"></script>
	<script src="{{ asset('js/web/webapp.js') }}"></script>
	<script src="{{ asset('js/animsition.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-tagsinput.min.js') }}"></script>
	<script src="{{ asset('js/intlTelInput.min.js') }}"></script>

	<!-- Load Facebook SDK for JavaScript -->
<!-- 	<script>
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1';
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script> -->

	@yield('script')
</body>
</html>