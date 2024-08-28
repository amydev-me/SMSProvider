@extends('layouts.app-master')

@section('description', "Contact TripleSMS support via phone, email, contact form or facebook anytime. We are here for you 24/7 with fully support.")

@section('title', "Contact Us | TripleSMS")

@section('style')
<style>
	.text-danger {
		color: #dc3545 !important;
		font-size: 12px !important;
	}

	.alert-danger {
		font-size: 12px;
	}

	.para {
		float: left !important;
	}

	.footerList {
		float: right;
	}

	.form-control {
		line-height: 2.5;
	}

	.contactHeader {
		font-size: 23px;
		font-weight: 500;
		color: #999;
	}

	.pushBottomMargin {
		padding: 50px;
	}

	a {
		color: #fff;
	}

	.form-control {
		font-size: 16px;
	}

	.divider {
		padding: 0.5px;
		background-color: #495057;
	}

	@media (max-width: 599px) {
		.para, .footerList {
			float: none !important;
		}

		.footerList a {
			font-size: 12px;
		}

		.footerList {
			padding-left: 55px;
		}
	}

	@media (max-width: 375px) {
		.footerList {
			padding-left: 35px;
		}
	}

	@media (max-width: 320px) {
		.footerList {
			padding-left: 0px;
		}
	}
	
	@media (max-width: 575px) {
		.mbDisplay {
			display: none;
		}
	}

	@media (min-width: 2000px){
		.pushBottomMargin {
			padding-bottom: 500px;
		}
		.formCenter {
			margin-top: 400px;
		}
	}

	@media (max-width: 1440px) {
		.marginFooter{
			margin-bottom: -32px;
		}
	}

	@media (max-width: 575px) {
		.mobileMargin{
			margin-top: 25px;
		}
	}
</style>
@endsection

@section('content')
<div class="container">
	<div style="padding: 50px;"></div>

	<div class="row formCenter">
		<div class="col-sm-1"></div>

		<div class="col-sm-5 mbDisplay">
			<h1 class="contactHeader" style="padding-bottom: 20px">Contact Us</h1>

			<p>
				<i class="fa fa-phone" style="transform: rotate(90deg); font-size: 18px; color: #dd404f"></i>
				<a href="tel:+959 5074179" style="padding-left: 23px; font-size: 19px; color: #6c757d;">+959 5074179</a><br>
				<a href="tel:+959 2052248" style="padding-left: 45px; font-size: 19px; color: #6c757d;">+959 2052248</a><br>				
				<a href="tel:+959 958848388" style="padding-left: 45px; font-size: 19px; color: #6c757d;">+959 958848388</a><br>
				<a href="tel:+959 441216033" style="padding-left: 43px; font-size: 19px; color: #6c757d;">+959 441216033</a>
			</p>

			<p>
				<i class="fa fa-envelope" style="font-size: 18px; color: #28a745"></i>
				<a href="mailto:info@triplesms.com?Subject=Hello%20again" style="padding-left: 20px; font-size: 18px; color: #6c757d;">info@triplesms.com</a>
			</p>

			<p>
				<img src="{{URL::asset('img/fblogo.svg')}}" width="18px" height="18px">
				<a href="https://www.facebook.com/Triple-SMS-217105992309964" target="_blank" style="padding-left: 20px; font-size: 18px; color: #6c757d;" rel="nofollow">TripleSMS</a>
			</p>
		</div>

		<div class="col-sm-6">
			<h3 class="text-center contactHeader" style="padding-bottom: 20px">We’d love to hear from you.</h3>

			@if($errors->any())
				<div class="alert alert-danger alert_login" style="text-align: left">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<form id="contact-form" action="{{ route('contact-us') }}" method="POST">
				@csrf

				<div class="form-group">
					<input type="text" class="form-control" name="name" id="name" placeholder="Name"/>

					<div id="name-error" class="invalid-feedback">
						The name field is required.
					</div>
				</div>

				<div class="form-group">
					<input type="email" class="form-control" name="email" id="email" placeholder="Email"/>

					<div id="email-error" class="invalid-feedback">
						The email field is required.
					</div>
				</div>

				<div class="form-group">
					<textarea class="form-control" rows="3" name="text" id="text" placeholder="Message"></textarea>

					<div id="text-error" class="invalid-feedback">
						The message field is required.
					</div>
				</div>

				{!! NoCaptcha::display() !!}

				<div id="recaptcha-error" class="invalid-feedback">
					Please verify that you are not a robot.
				</div>

				<br/>

				<button id="send-contact-message" type="submit" class="btn btn-primary" style="font-size: 14px; width: 100%">Send</button>
			</form>
		</div>
	</div>

	<div class="pushBottomMargin"></div>
</div>

@include('includes.footer-nav')

@endsection

@section('script')
{!! NoCaptcha::renderJs() !!}

<script>
	$('#send-contact-message').click(function(e) {
		e.preventDefault();
		var error = false;

		if ($('#name').val() == '') {
			$('#name-error').show();
			error = true;
		} else {
			$('#name-error').hide();
		}

		if ($('#email').val() == '') {
			$('#email-error').show();
			error = true;
		} else {
			$('#email-error').hide();
		}

		if ($('#text').val() == '') {
			$('#text-error').show();
			error = true;
		} else {
			$('#text-error').hide();
		}

		if (grecaptcha.getResponse().length == 0) {
			$('#recaptcha-error').show();
			error = true;
		} else {
			$('#recaptcha-error').hide();
		}

		if (error != true) {
			$('#contact-form').submit();
		}
	});
</script>
@endsection