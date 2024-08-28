@extends('layouts.app-master')

@section('title', 'Password Reset | TripleSMS')

@section('style')
<style type="text/css">
	h1{
		font-size: 22px;
		padding-bottom: 10px;
	}
	@media (max-width: 600px){
		.col-md-3,.col-md-6{
			width: 48%;
			padding-right: 5px;
		}
	}
	@media (max-width: 399px){
		.col-md-3,.col-md-6{
			width: 40%;
			padding-right: 5px;
		}
	}
	@media (max-width: 350px){
		.col-md-3,.col-md-6{
			width: 36%;
		}
	}
</style>
@endsection

@section('content')
<div class="page-wrapper">
	<div class="page-content--bge5">
		<div class="login-wrap">
			<div class="login-content">
				<h1>Enter Your New Password</h1>
				<div class="login-form">
					<form method="POST" action="{{ route('password.reset') }}">
						@csrf

						<input type="hidden" name="token" value="{{ $token }}">

						<div class="form-group">
							<label for="email">{{ __('E-Mail Address') }}</label>
								<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" placeholder="Enter Email" required autofocus>

								@if ($errors->has('email'))
								<span class="invalid-feedback">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
								@endif
						</div>

						<div class="form-group">
							<label for="password">{{ __('Password') }}</label>
								<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="New Password" required>

								@if ($errors->has('password'))
									<span class="invalid-feedback">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
								@endif
						</div>

						<div class="form-group">
							<label for="password-confirm">{{ __('Confirm Password') }}</label>
								<input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm New Password" required>
						</div>

						<div class="form-group row mb-0 text-right">
							<div class="col-sm-6 col-md-3 offset-md-3">
								<button type="submit" class="btn btn-primary">
									{{ __('Cancel') }}
								</button>
							</div>
							<div class="col-sm-6 col-md-6">
								<button type="submit" class="btn btn-primary">
									{{ __('Change Password') }}
								</button>
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection