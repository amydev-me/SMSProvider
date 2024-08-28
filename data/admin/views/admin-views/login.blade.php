@extends('admin-layouts.admin-master')

@section('title', 'Admin Login')

@section('content')

<div class="hero">
	<div class="img2">
		<div class="page-wrapper">
			<div class="page-content">
				<div class="login-wrap" style="margin-top: 6rem;">
					<div class="login-content">
						<div class="login-form">
							<form action="{{ route('admin.login') }}" method="post">
								@csrf

								@if ($errors->has('errors'))
								<div class="alert alert-danger">
									<strong> {{ $errors->first('errors') }}</strong>
								</div>
								@endif

								<div class="form-group">
									<label>Username</label>
									<input class="au-input au-input--full form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" type="text" name="username" placeholder="Username" value="{{ old('username') }}" autofocus />

									@if ($errors->has('username'))
									<div class="invalid-feedback">
										{{ $errors->first('username') }}
									</div>
									@endif
								</div>

								<div class="form-group">
									<label>Password</label>
									<input class="au-input au-input--full form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" placeholder="Password" value="{{ old('password') }}" autocomplete="new-password"/>

									@if ($errors->has('password'))
									<div class="invalid-feedback">
										{{ $errors->first('password') }}
									</div>
									@endif
								</div>

								<button class="au-btn au-btn--block au-btn--blue m-b-20" type="submit">Sign In</button>

								<a href="/">Go to main site</a>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<nav class="navbar fixed-bottom navbar-light bg-dark">
	<p class="navbar-brand" style="width: 100%; text-align: center; color: white;"><span style="font-size: 0.8rem">Copyright © <b>TripleSMS</b></span></p>
</nav>

@endsection