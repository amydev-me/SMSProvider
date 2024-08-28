@extends('layouts.app-master')

@section('register', 'active')
@section('description', "Register at TripleSMS and get 3 free SMS messages.")
@section('title', "Register | TripleSMS")

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/pretty-checkbox.min.css') }}">

<style>
	.page-content--bge5 {
		padding-top: 2rem !important;
	}

	h1 {
		font-size: 25px;
		color: #797979;
	}

	.login-wrap {
		max-width: 650px;
	}

	.btn_tel_send{
    margin-top: -45px;
    margin-left: 213px;
    border: none;
    background-color: #4272d7;
    padding: 10px 10px 11px 10px !important;
    color: white !important;
}
</style>
@endsection

@section('content')
<div class="page-wrapper">
	<div class="page-content--bge5">
		<h1 class="text-center">Create an Account</h1>

		<div class="login-wrap">
			<div class="login-content" v-cloak>
				<signup inline-template>
					<div class="login-form">
						@if($errors->any())
							<div class="alert alert-danger alert_login" style="text-align: left">

								<ul>
									@foreach ($errors->all() as $error)
										<li>{{$error}}</li>
									@endforeach
								</ul>

							</div>
						@endif

						<form @submit.prevent="validateData" autocomplete="off" ref="form" method="POST" action="{{ route('register') }}">
							@csrf

							<div class="row">
								<div class="form-group col-sm-6">
									<label>Username <span class="text-danger">*</span></label>
									<input type="text" name="username" id="username-field" v-model="user.userName" data-vv-delay="500" v-validate="'required|alpha_dash|verify_user|min:3'" :class="errors.has('username')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'"/>

									<div class="text-danger" v-if="errors.has('username')">@{{ errors.first('username') }}</div>
								</div>

								<div class="form-group col-sm-6">
									<label>Email <span class="text-danger">*</span></label>
									<input type="text" v-model="user.emailAddress" v-validate="'required|email|verify_email'" name="email" data-vv-delay="500" :class="errors.has('email')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'" placeholder="someone@example.com"/>

									<div class="text-danger" v-if="errors.has('email')">@{{ errors.first('email') }}</div>
								</div>

								<div class="form-group col-sm-6">
									<label>Full Name <span class="text-danger">*</span></label>
									<input type="text" v-model="user.fullName" v-validate="'required|max:255'" name="full_name" :class="errors.has('full_name')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'"/>

									<div class="text-danger" v-if="errors.has('full_name')">@{{ errors.first('full_name') }}</div>
								</div>

								<div class="form-group col-sm-6">
									<label style="float: left;">Password <span class="text-danger">*</span></label>
									<div style="float: right;">
										<button type="button" @click="switchVisibility" tabindex="-1">
											<span :class="togglePassword"></span>
										</button>
									</div>

									<input :type="passwordFieldType" v-model="user.userPassword" v-validate="'required'" type="password" name="password" :class="errors.has('password')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'" autocomplete="new-password"/>

									<div class="text-danger" v-if="errors.has('password')">@{{ errors.first('password') }}</div>
								</div>

								<div class="form-group col-sm-6">
									<label>Mobile <span class="text-danger">*</span></label>
									
									<div class="input-group">
										<input type="tel" v-model="user.mobileNo" v-validate="'required|verify_phone'" id="phone" name="mobile" :class="errors.has('mobile') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'"/>

										<div class="input-group-append">
											<button type="button" class="btn btn_tel_send" @click="sendConfirmationCode">Send</button>
										</div>
									</div>

									<div class="text-danger" v-if="errors.has('mobile')">@{{ errors.first('mobile') }}</div>
								</div>

								<div class="form-group col-sm-6">
									<label>Confirmation Code <span class="text-danger">*</span></label>
									<input type="text" v-model="user.confirmCode" v-validate="'required'" name="confirm_code" :class="errors.has('confirm_code') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'">

									<div class="text-danger" v-if="errors.has('confirm_code')">@{{ errors.first('confirm_code') }}</div>
								</div>
								
								<div class="form-group col-sm-12">
									<label>Company Name</label>
									<input v-model="user.companyName" type="text" name="company" :class="errors.has('company')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'"/>

									<div class="text-danger" v-if="errors.has('company')">@{{ errors.first('company') }}</div>
								</div>

								<div class="form-group col-sm-12">
									<label>Address</label>
									<textarea v-model="user.userAddress" name="address" :class="errors.has('address')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'"></textarea>

									<div class="text-danger" v-if="errors.has('address')">@{{ errors.first('address') }}</div>
								</div>

								<!-- <div class="form-group col-sm-12">
									<div id="recaptcha"></div>

									<input type="hidden" v-model="captcha" v-validate="'required'" name="captcha"/>
									<div class="text-danger" v-if="errors.has('captcha')">Please verify that you are not a robot.</div>
								</div> -->

								<div class="col-sm-12" style="margin-bottom: 15px;">
									<div class="pretty p-svg p-smooth p-bigger" style="margin-right: 10px;">
										<input type="checkbox" name="accept_terms" v-model="user.acceptTerms" v-validate="'required'"/>
										<div class="state p-primary">
											<svg class="svg svg-icon" viewBox="0 0 20 20">
												<path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white; fill: white;"></path>
											</svg>
											<label></label>
										</div>
									</div>

									<a href="{{ route('terms') }}" title="Terms & Conditions" target="_blank">Agree to Terms & Conditions</a> <span class="text-danger">*</span>

									<div class="text-danger" v-if="errors.has('accept_terms')">@{{ errors.first('accept_terms') }}</div>
								</div>

								<div class="col-sm-12">
									<button type="submit" class="au-btn au-btn--block au-btn--green">Register</button>
								</div>
							</div>
						</form>

						<div class="register-link">
							<p>
								Already have an account?
								<a href="{{ route('login') }}" title="Sign in to TripleSMS">Sign in</a>
							</p>
						</div>
					</div>
				</signup>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<!-- <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script> -->
<script type="text/javascript" src="{{ asset('js/user/common.js') }}"></script>

<script>
	setTimeout(function () {
		$("#username-field").focus();
	}, 200);
	
	/*var onloadCallback = function() {
		setTimeout(function () {
			try {
				grecaptcha.render('recaptcha', {
					sitekey: '6Lf46mgUAAAAALfuQM1fSyD4BDmTCryhnpxWxIxX'
				});
			}
			catch(err) {
				location.reload();
			}
		}, 500);
	};*/
</script>
@endsection