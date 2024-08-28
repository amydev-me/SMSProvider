<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8"/>
	<meta name="csrf-token" content="{{ csrf_token() }}"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta name="google-site-verification" content="Kw9Y174lSivj2hLBxUhN0nNRRsNeycJ6X6i4aqCMfz8"/>

	<title>TripleSMS - @yield('title')</title>
	<link rel="shortcut icon" type="image/x-icon" href="{{asset('img/32.png')}}" />
	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	
	<!-- Style -->
	<link href="{{ asset('css/app.min.css') }}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/theme.min.css') }}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/material-design-iconic-font.min.css') }}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/hamburgers.min.css') }}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/animsition.min.css') }}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/main.min.css') }}" media="all" rel="stylesheet" type="text/css" />

	<style type="text/css">
		.hero {
			height: calc(100vh - 45px);
		}

		.hero .img2 {
			margin-top: 0;
			background: url(/img/public_sms_login.svg);
		}

		footer {
			display: none;
		}
	</style>
</head>
<body>
	@yield('content')

	<script type="text/javascript" src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/animsition.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/main.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/notify.min.js') }}"></script>

	@yield('script')
</body>
</html>