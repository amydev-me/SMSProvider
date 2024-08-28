@extends('layouts.app-master')

@section('login', 'active')
@section('description', "Sign in to TripleSMS and manage your contacts, groups, messages and send SMS to anyone.")
@section('title', "Sign in | TripleSMS")

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/pretty-checkbox.min.css') }}">

<style>
	.page-content--bge5 {
		padding-top: 2rem !important;
	}

	h1 {
		font-size: 26px;
		padding-bottom: 15px;
	}
</style>
@endsection

@section('content')
<div class="page-wrapper">
	<div class="page-content--bge5">
		<div class="login-wrap">
			<div class="login-content">
				<h1 class="text-center">Sign in</h1>
				<div class="login-form">
					<form action="{{ route('login') }}" method="POST" autocomplete="off">
						@csrf

						@if ($errors->has('errors'))
						<div class="alert alert-danger">
							<strong> {{ $errors->first('errors') }}</strong>
						</div>
						@endif

						<div class="form-group">
							<label>Username or Email</label>
							<input type="text" name="username" id="username-field" class="au-input au-input--full form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" value="{{ old('username') }}"/>
							@if ($errors->has('username'))
								<div class="invalid-feedback">
									{{ $errors->first('username') }}
								</div>
							@endif
						</div>

						<div class="form-group">
							<label style="float: left;">Password</label>
							<div style="float: right;">
								<span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
							</div>

							<input type="password" name="password" id="password-field" class="au-input au-input--full form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" value="{{ old('password') }}" autocomplete="new-password">
							@if ($errors->has('password'))
								<div class="invalid-feedback">
									{{ $errors->first('password') }}
								</div>
							@endif
						</div>

						<div style="margin-bottom: 15px;">
							<div class="pretty p-svg p-smooth p-bigger" style="margin-right: 10px;">
								<input type="checkbox" name="remember" value="1"/>
								<div class="state p-primary">
									<svg class="svg svg-icon" viewBox="0 0 20 20">
										<path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white; fill: white;"></path>
									</svg>
									<label>Remember Me</label>
								</div>
							</div>

							<label class="float-right">
								<a title="Reset Password" href="{{ route('recoveries.new') }}">Forgot Password?</a>
							</label>
						</div>

						<button class="au-btn au-btn--block au-btn--green" type="submit">sign in</button>
					</form>

					<div class="register-link">
						<p>
							Don't have an account?
							<a title="Register at TripleSMS" href="{{ route('register') }}">Create Account</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
	$(".toggle-password").click(function() {
		$(this).toggleClass("fa-eye fa-eye-slash");
		var input = $($(this).attr("toggle"));
		
		if (input.attr("type") == "password") {
			input.attr("type", "text");
		} else {
			input.attr("type", "password");
		}
	});

	$("#username-field").focus();
</script>
@endsection