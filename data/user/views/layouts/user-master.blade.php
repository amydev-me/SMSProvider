<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('title') - TripleSMS</title>

	<link rel="shortcut icon" type="image/x-icon" href="{{asset('img/32.png')}}" />

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<!-- Style -->
	<link href="{{asset('css/animate.min.css')}}" rel="stylesheet">
	<link href="{{asset('css/app.min.css')}}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{asset('css/theme.min.css')}}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{asset('css/main.min.css')}}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{asset('css/material-design-iconic-font.min.css')}}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{asset('css/hamburgers.min.css')}}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{asset('css/animsition.min.css')}}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{asset('css/csstyles.css')}}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{asset('css/intlTelInput.min.css')}}" media="all" rel="stylesheet" type="text/css" />

	<style type="text/css">
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
			.iti-flag {background-image: url("/img/flags@2x.png");}
		}

		.text-danger {
			color: #dc3545 !important;
			font-size: 12px !important;
		}

		.alert-danger {
			font-size: 12px;
		}

		.pagination {
			justify-content: center;;
		}

		.pagination__navigation--disabled {
			opacity: .6;
			pointer-events: none;
		}

		.pagination__more {
			pointer-events: none;

		}

		.line-break {
			display: block;
			height: 1px;
			padding: 0;
			margin: 1rem 0;
			border-top: 1px solid rgba(0, 0, 0, .1);
		}

		.navbar-sidebar li i {
			width: 14px;
		}
	</style>

	@yield('style')

	@include('includes.header-mobile')
	@include('includes.header-desktop')
	@include('includes.sidebar')
</head>
<body>
	<content>
		<div class="page-container">
			<div class="main-content" id="app">
				<div class="container-fluid">
					@yield('content')
				</div>
			</div>
		</div>
	</content>
	
	<footer>
		{{--@include('includes.footer')--}}
	</footer>

	<script type="text/javascript" src="{{asset('js/user/manifest.js') }}"></script>
	<script type="text/javascript" src="{{asset('js/user/vendor.js') }}"></script>
	<script type="text/javascript" src="{{asset('js/user/app.js') }}"></script>
	<script type="text/javascript" src="{{asset('js/user/common.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/jquery-ui.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/animsition.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/main.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/bootstrap-tagsinput.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/intlTelInput.min.js') }}"></script>

	@yield('script')
</body>
</html>