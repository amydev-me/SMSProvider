<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>@yield('title') - TripleSMS</title>
	<!-- Fonts -->
	<link rel="shortcut icon" type="image/x-icon" href="{{asset('img/32.png')}}" />
	<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">


	
	<!-- Style -->
	<link href="{{ asset('css/app.min.css') }}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/theme.min.css') }}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/material-design-iconic-font.min.css') }}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/hamburgers.min.css') }}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/animsition.min.css') }}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/admin/styles.css') }}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/main.min.css') }}" media="all" rel="stylesheet" type="text/css" />
	<link href="{{ asset('css/jquery-ui.min.css') }}" media="all" rel="stylesheet" type="text/css" />

	@yield('stylesheet')

	<style>
		[v-cloak] > * { display:none; }
		[v-cloak]::before { content: "loading..."; }

		.notifi__item .content {
			width: 100%;
		}
	</style>
</head>
<body>
	@include('log-includes.header-mobile')
	@include('log-includes.header-desktop')
	@include('log-includes.sidebar')

	<content id="app">
		<!-- PAGE CONTAINER-->
		<div class="page-container">
			<!-- MAIN CONTENT-->
			<div class="main-content">
				<div class="container-fluid">
					@yield('content')
				</div>
			</div>
			<!-- END MAIN CONTENT-->
		</div>
		<!-- END PAGE CONTAINER-->
	</content>

	<div class="modal animation fadeIn" id="change_password_modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">

				<div class="modal-header">
					<h5 class="modal-title">Change Password</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<form role="form" id="change_password_form" autocomplete="off">
					<div class="modal-body">
						<div class="form-group">
							<label>Old Password</label>
							<input type="password" name="old_password" id="old_password" class="au-input au-input--full form-control is-valid"/>

							<div class="text-danger" id="old_password_error"></div>
						</div>

						<div class="form-group">
							<label>New Password</label>
							<input type="password" name="new_password" id="new_password" class="au-input au-input--full form-control is-valid"/>

							<div class="text-danger" id="new_password_error"></div>
						</div>

						<div class="form-group">
							<label>Confirm Password</label>
							<input type="password" name="confirm_password" id="confirm_password" class="au-input au-input--full form-control is-valid"/>

							<div class="text-danger" id="confirm_password_error"></div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary" id="save_new_password">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	@include('admin-includes.admin-footer')

	<script type="text/javascript" src="{{ asset('js/admin/manifest.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/admin/admin_vendor.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/admin/admin_app.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/admin/common.js' )}}"></script>
	<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/animsition.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/main.min.js') }}"></script>
	
	@yield('script')
</body>
</html>