<div class="container-fluid marginFooter">
	<div class="row horn" style="padding-bottom: 20px;">
		<div class="container" style="padding-top: 20px;">
			<div class="row">
				<div class="col-sm-4">
					<h6 style="padding-bottom: 20px;">Pricing & Documentation</h6>

					<div style="padding-bottom: 10px;">
						<a href="" title="Home" target="_blank">
							<i class="fa fa-angle-right"></i>
							<span style="font-size: 13px;">Home</span>
						</a>
					</div>

					@guest

					<div style="padding-bottom: 10px;">
						<a href="{{ route('pricing') }}" title="Affordable & Reliable Pricing" target="_blank">
							<i class="fa fa-angle-right"></i>
							<span style="font-size: 13px;">Pricing</span>
						</a>
					</div>

					<div style="padding-bottom: 10px;">
						<a href="{{ route('documentation') }}" title="API Documentation" target="_blank">
							<i class="fa fa-angle-right"></i>
							<span style="font-size: 13px;">Documentation</span>
						</a>
					</div>

					<div style="padding-bottom: 10px;">
						<a href="{{ route('register') }}" title="Create an Account" target="_blank">
							<i class="fa fa-angle-right"></i>
							<span style="font-size: 13px;">Register</span>
						</a>
					</div>

					<div style="padding-bottom: 10px;">
						<a href="{{ route('login') }}" title="Sign in" target="_blank">
							<i class="fa fa-angle-right"></i>
							<span style="font-size: 13px;">Sign in</span>
						</a>
					</div>

					@else

					<div style="padding-bottom: 10px;">
						<a href="{{ route('buy') }}" title="Affordable & Reliable Pricing" target="_blank">
							<i class="fa fa-angle-right"></i>
							<span style="font-size: 13px;">Pricing</span>
						</a>
					</div>

					<div style="padding-bottom: 10px;">
						<a href="{{ route('documentation') }}" title="API Documentation" target="_blank">
							<i class="fa fa-angle-right"></i>
							<span style="font-size: 13px;">Documentation</span>
						</a>
					</div>

					<div style="padding-bottom: 10px;">
						<a href="{{ route('privacy') }}" title="Privacy Policy" target="_blank">
							<i class="fa fa-angle-right"></i>
							<span style="font-size: 13px;">Privacy Policy</span>
						</a>
					</div>

					@endguest
				</div>

				<div class="col-sm-4">
					<h6 style="padding-bottom: 20px;">Privacy & Terms</h6>

					<div style="padding-bottom: 10px;">
						<a href="{{ route('terms') }}" title="Terms & Conditions" target="_blank">
							<i class="fa fa-angle-right"></i>
							<span style="font-size: 13px">Terms & Conditions</span>
						</a>
					</div>

					<div style="padding-bottom: 10px;">
						<a href="{{ route('privacy') }}" title="Privacy Policy" target="_blank">
							<i class="fa fa-angle-right"></i>
							<span style="font-size: 13px">Privacy Policy</span>
						</a>
					</div>

					<div style="padding-bottom: 10px;">
						<a href="{{ route('contact-us') }}" title="Contact Us" target="_blank">
							<i class="fa fa-angle-right"></i>
							<span style="font-size: 13px">Contact Us</span>
						</a>
					</div>
					<div style="padding-bottom: 10px;">
						<a href="{{ route('faq') }}" title="FAQs" target="_blank">
							<i class="fa fa-angle-right"></i>
							<span style="font-size: 13px">FAQs</span>
						</a>
					</div>
				</div>

				<div class="col-sm-4" style="padding-bottom: 30px">
					<h6 style="padding-bottom: 20px" class="mobileMargin">About TripleSMS</h6>

					<i class="fa fa-phone" style="transform: rotate(90deg); font-size: 18px; color: #dd404f"></i>

					<div style="margin-top:-28px;margin-left:25px;">
						@php
							$phones = [];
							if ($setting->phones) {
								$phones = explode(',', $setting->phones);
							}
						@endphp

						@foreach ($phones as $phone)
							<a href="{{ 'tel: '.$phone }}" style="padding-left: 10px; padding-bottom: 2px; font-size: 13px">{{ $phone }}</a><br>
						@endforeach
					</div>

					<div>
						<i class="fa fa-envelope" style="font-size: 18px; color: #28a745"></i>
						<a href="{{ 'mailto: '.$setting->email.'?Subject=Hello%20again' }}" title="E-Mail" target="_top" style="padding-left: 12px; font-size: 13px">{{ $setting->email }}</a>
					</div>

					<div>
						<img src="{{ URL::asset('img/fblogo.svg') }}" alt="facebook" width="18px" height="18px"/>
						<a title="Facebook" href="{{ $setting->facebook_url }}" target="_blank" style="padding-left: 12px; font-size: 13px;" rel="nofollow">TripleSMS</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="divider"></div>