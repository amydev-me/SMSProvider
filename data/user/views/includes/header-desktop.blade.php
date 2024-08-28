<header class="header-desktop">
	<div class="section__content section__content--p30">
		<div class="container-fluid">
			<div class="header-wrap float-right">
				<div class="header-button">
					<div style="padding-right: 20px">
						@if (Auth::guard('web')->user()->sms_type == 'Package')

						<span>Balance:</span> {{ number_format(Auth::guard('web')->user()->credits()) }} Credit

						@elseif (Auth::guard('web')->user()->sms_type == 'PAYG')

						<span>Unpaid Balance:</span> {{ number_format(Auth::guard('web')->user()->unpaid_credits()) }} Credit

						@else

						<span>Balance:</span> {{ number_format(Auth::guard('web')->user()->usd_credits(), 4) }} USD

						@endif
					</div>

					@if (!Auth::guard('web')->user()->sms_type == 'PAYG')
					<div>
						<div style="padding-right: 20px">
							<a class="btn btn-sm btn-primary" href="{{ route('buy') }}">
								<i class="fas fa-shopping-cart"></i>
								<span>Buy Credit</span>
							</a>
						</div>
					</div>
					@endif

					<div class="account-wrap">
						<div class="account-item clearfix js-item-menu">
							<div class="image">
								<i class="fas fa-user-circle fa-2halfx"></i>
							</div>

							<div class="account-dropdown js-dropdown">
								<div class="info clearfix">
									<div class="content">
										<h5 class="name">
											<a href="#">{{ Auth::guard('web')->user()->full_name }}</a>
										</h5>
										<span class="email">{{ Auth::guard('web')->user()->email }}</span>
									</div>
								</div>

								<div class="account-dropdown__body">
									<div class="account-dropdown__item">
										<a href="{{ route('user.profile') }}">
											<i class="zmdi zmdi-account"></i>Profile
										</a>
									</div>

									<div class="account-dropdown__item">
										<a href="{{ route('user.setting') }}">
											<i class="zmdi zmdi-settings"></i>Setting
										</a>
									</div>
								</div>

								<div class="account-dropdown__footer">
									<a href="{{route('logout')}}">
										<i class="zmdi zmdi-power"></i>Logout</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>