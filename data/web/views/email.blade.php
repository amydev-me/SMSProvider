@extends('layouts.app-master')

@section('description', "Enter your email address that you used to register. We'll send you an email with a link to reset your password.")

@section('title', "Reset Password | TripleSMS")

@section('style')
<style>
	h1 {
		margin-top: 1.5rem;
		font-size: 26px;
		color: #797979;
		padding-bottom: 10px;
	}
</style>
@endsection

@section('content')

<div class="page-wrapper">
	<div class="page-content--bge5">	
		<div class="login-wrap">
			<h1 class="text-center">Reset Password</h1>

			<div class="login-content">	
				<div class="login-form">
					@if ($errors->any())
						<div class="alert alert-danger alert_login" style="text-align: left">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form id="reset-form" action="{{ route('password.email') }}" method="post">
						@csrf

						<div class="form-group">
							<label><span>Email</span></label>
							<input class="au-input au-input--full form-control" type="email" name="email" id="email"/>

							<div id="email-error" class="invalid-feedback" style="display: none;">
								The email field is required.
							</div>
						</div>

						<div class="form-group">
							{!! NoCaptcha::display() !!}

							<div id="recaptcha-error" class="invalid-feedback" style="display: none;">
								Please verify that you are not a robot.
							</div>
						</div>

						<div class="text-center">
							<button id="send-password-reset" type="submit" class="btn btn-primary" style="width: 100%">
								{{ __('Send Recovery Link') }}
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
{!! NoCaptcha::renderJs() !!}

<script>
	$('#send-password-reset').click(function(e) {
		e.preventDefault();
		var error = false;

		if ($('#email').val() == '') {
			$('#email-error').show();
			error = true;
		} else {
			$('#email-error').hide();
		}

		if (grecaptcha.getResponse().length == 0) {
			$('#recaptcha-error').show();
			error = true;
		} else {
			$('#recaptcha-error').hide();
		}

		if (error != true) {
			$('#reset-form').submit();
		}
	});
</script>
@endsection