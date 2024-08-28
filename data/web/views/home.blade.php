@extends('layouts.app')

@section('home', 'active')

@section('description', "Send your SMS messages online with the Cheapest Bulk SMS Gateway in Myanmar. TripleSMS.com is the leading SMS gateway service provider in Myanmar with lowest rates.")

@section('keywords', "TripleSMS, SMS, Send SMS, SMS Send, Bulk SMS, SMS Poh, SMS Poh Mal, SMS Gateway, SMS Portal, SMS Gateway Service, SMS Service Provider, Send SMS Online, SMS Send Online, SMS Online, SMS API, SMS Gateway API, Compose SMS, Schedule SMS, Cheapest SMS, Best SMS, SMS Deals, Best, Cheap, Gateway, API, Myanmar")

@section('title', "Send Bulk SMS Online with Lowest Rates in Myanmar | TripleSMS")

@section('style')
<style>
	#main.navbar {
		display: none;
	}

	#welcome.navbar {
		display: flex;
	}

	content.user {
		margin-top: 0px;
	}

	.btn-pink {
		border-radius: 25px;
		background-color: white;
		color: #9602b5;
		padding: 8px 30px;
		transition: 0.5s;
	}

	.btn-pink:hover {
		background-color: #9602b5;
		color: white;
	}

	#main.navbar {
		display: none;
	}

	content.user {
		margin-top: 0px;
	}

	.para {
		float:left !important;
	}

	.footerList {
		float:right;
	}

	@media (max-width: 599px) {
		.para,.footerList{
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

	.marginFooter a {
		color: #fff;
	}

	.form-control{
		font-size: 12px;
	}

	@media (max-width: 1440px){
		.marginFooter{
			margin-bottom: -32px;
		}
	}

	@media (max-width: 575px){
		.mobileMargin {
			margin-top: 25px;
		}
	}

	.card-body {
		padding: 6px;
	}

	.icon,.icon1,.icon2{
		padding-top: 40px;
	}
</style>
@endsection

@section('content')

@include('includes.hero')

<div class="container-fluid">
	<div class="container" style="max-width: 1000px;">
		<div class="row" style="flex-direction: row-reverse">
			<div class="col-sm">
				<img alt="Send Bulk SMS with TripleSMS" src="{{ asset('img/working2.svg') }}" width="100%">
			</div>

			<div class="col-sm" style="display: flex; align-items: center;">
				<div class="mx-auto pb-4 py-sm-5 picture-text">
					<h2 style="font-size: 1.25rem;">How does Bulk SMS help your business?</h2>
					<p>Bulk SMS messaging is sending a large number of SMS messages to the mobile phones of a predetermined group of recipients. Bulk SMS helps businesses and enterprises in communicating with their users, to send out important information, promotions and mobile marketing. Bulk messaging is widely used for information and communication between both staff and customers.</p>
				</div>
			</div>
		</div>
	</div>

	<div class="row iconArea">
		<h2 style="width: 100%; text-align: center">Why TripleSMS?</h2>

		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-4">
					<div class="icon2">
						<i class="fas fa-desktop" style="color: #4272d7"></i>
					</div>

					<div class="card-body">
						<h4 class="card-title" style="text-align: center">Send SMS Online</h4>
						<p class="card-text" style="text-align: center">Send SMS from your browser to your customers or friends. Create groups and send SMS to multiple contacts at the same time. <a href="{{ route('register') }}" title="Create an Account" target="_blank">Register</a> now to try.</p>
					</div>
				</div>

				<div class="col-sm-12 col-md-4">
					<div class="icon1">
						<i class="fas fa-code" style="color: #4272d7"></i>
					</div>

					<div class="card-body">
						<h4 class="card-title" style="text-align: center">SMS Gateway API</h4>
						<p class="card-text" style="text-align: center"><strong>99.99%</strong> API success rate. Our API integration is simple and easy to use. Register and send your very fast SMS by using our <a href="{{ route('documentation') }}" title="API Documentation" target="_blank">API Documentation</a>.</p>
					</div>
				</div>

				<div class="col-sm-12 col-md-4">
					<div class="icon">
						<i class="fas fa-phone" style="color: #4272d7; transform: rotate(90deg)"></i>
					</div>

					<div class="card-body">
						<h4 class="card-title" style="text-align: center">Technical Support</h4>
						<p class="card-text" style="text-align: center">24/7 LiveChat, Phone and Email  support. Our highly skilled technicians are here to help you get started or solve any problems you may have.</p>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12 col-md-4">
					<div class="icon2">
						<i class="fas fa-lock" style="color: #4272d7"></i>
					</div>

					<div class="card-body">
						<h4 class="card-title" style="text-align: center">High Level Security</h4>
						<p class="card-text" style="text-align: center">Security is our top priority. Our services are encrypted with SSL / HTTPS and all of your personal information and sms logs are stored safely.</p>
					</div>
				</div>

				<div class="col-sm-12 col-md-4">
					<div class="icon2">
						<i class="far fa-calendar-alt" style="color: #4272d7"></i>
					</div>

					<div class="card-body">
						<h4 class="card-title" style="text-align: center">Scheduled Text Messages</h4>
						<p class="card-text" style="text-align: center">Compose your messages at night and schedule them to send at morning automatically. Use our schedule service and chill.</p>
					</div>
				</div>

				<div class="col-sm-12 col-md-4">
					<div class="icon2">
						<i class="fas fa-dollar-sign" style="color: #4272d7"></i>
					</div>

					<div class="card-body">
						<h4 class="card-title" style="text-align: center">Affordable Pricing</h4>
						<p class="card-text" style="text-align: center">TripleSMS is the Cheapest Bulk SMS Service Provider in Myanmar. Go ahead to our <a href="{{ route('pricing') }}" title="Affordable & Reliable Pricing" target="_blank">Pricing</a> page and see amazing deals.</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container" style="max-width: 1000px">
		<div class="row">
			<div class="col my-5 center">
				<h1 class="phoneContentTitle">Best SMS Gateway API in Myanmar</h1>

				<p>TripleSMS provides a very simple web interface to compose and schedule bulk sms messages with the click of a single button.</p>
				<p>Make a right path for your business by targeting the right people at the right time.</p>
				<p>Engage your customers like never before with TripleSMS.</p>
			</div>
		</div>

		<div class="row">
			<div class="phone container mb-5">
				<div class="row">
					<div class="col-sm">
						<div class="iphone1" style="margin-bottom: 30px">
						</div>
					</div>

					<div class="col-sm">
						<div class="android" style="margin-bottom: 30px">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row" style="background-color: #f2f2f2;padding: 30px 0px">
		<div class="container text-center">
			<span style="color: #999;font-weight: 500;font-size: 25px;">What are you waiting for?</span>
			<div class="pt-2">
				@guest
				<a title="Register" href="{{ route('register') }}" class="btn" style="background-color: teal; color: white;">Get Started</a>
				@else
				<a href="{{ route('buy') }}" class="btn" style="background-color: teal; color: white;">Buy SMS Packages</a>
				@endguest
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	window.addEventListener("scroll", function() {
		if (window.scrollY >0) {
			$('#welcome.navbar').css('display', 'none');
			$('#main.navbar').css('display', 'flex');
		}
		else{
			$('#welcome.navbar').css('display', 'flex');
			$('#main.navbar').css('display', 'none');
		}
	}, false);

	$(".navbar-toggler").click(function(){
		$('.sidenav').css('width', '100%');
		$('.sidenav .nav-item').css('text-align', 'center');
	});

	$(".closebtn").click(function(){
		$('.sidenav').css('width', '0px');
	});

	$( window ).on('load' , function() {

		if(window.scrollY>0){
			$('#welcome.navbar').css('display', 'none');
			$('#main.navbar').css('display', 'flex');
		}
	});

	var $animation_elements = $('.animation-element');
	var $window = $(window);

	function check_if_in_view() {
		var window_height = $window.height();
		var window_top_position = $window.scrollTop();
		var window_bottom_position = (window_top_position + window_height);

		$.each($animation_elements, function() {
			var $element = $(this);
			var element_height = $element.outerHeight();
			var element_top_position = $element.offset().top;
			var element_bottom_position = (element_top_position + element_height);

			//check to see if this current container is within viewport
			if ((element_bottom_position >= window_top_position) &&
				(element_top_position <= window_bottom_position)) {
				$element.addClass('in-view');
			} else {
				$element.removeClass('in-view');
			}
		});
	}

	$(window).on('scroll resize', check_if_in_view);

	$(window).trigger('scroll');
</script>

<script type="application/ld+json">
	{
		"@context": "http://schema.org",
		"@type": "Service",
		"serviceType": "Bulk SMS Gateway Service Provider",
		"url": "https://triplesms.com",
		"name": "TripleSMS",
		"areaServed": {
			"@type": "Country",
			"name": "Myanmar"
		}
	}
</script>
@endsection