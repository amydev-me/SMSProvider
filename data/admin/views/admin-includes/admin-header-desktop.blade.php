<header class="header-desktop">
	<div class="section__content section__content--p30">
		<div class="container-fluid">
			<div class="header-wrap float-right">
				<div class="header-button">
					@if (Auth::guard('admin')->user()->role == 3 || Auth::guard('admin')->user()->role == 4)
					<div style="padding-right: 20px">
						<span>NHN Balance:</span> {{ number_format( Auth::guard('admin')->user()->getBalance() ) }} MMK
					</div>

					<span style="padding-right: 20px"> | </span>

					<div style="padding-right: 28px">
						<span>Dexatel Balance:</span> {{ number_format( Auth::guard('admin')->user()->getIntlBalance() ) }} MMK
					</div>
					@endif

					<div class="noti-wrap">
						<div class="noti__item js-item-menu">
							<i class="zmdi zmdi-notifications"></i>
							<span class="quantity notification-quantity" style="display: none;"></span>

							<div class="notifi-dropdown js-dropdown">
								<div class="notifi__title">
									<p>You have <span class="notification-quantity"></span> Notifications</p>
								</div>

								<div id="package-notify"></div>

								<div class="notifi__footer" style="text-align: center;">
									<a href="{{ route('admin.order.index') }}" style="display: inline-flex;">Orders</a>
								</div>
							</div>
						</div>
					</div>

					<div class="account-wrap">
						<div class="account-item clearfix js-item-menu">
							<div class="image">
								<i class="fas fa-user-circle fa-2halfx"></i>
							</div>

							<div class="account-dropdown js-dropdown">
								<div class="info clearfix">
									<div class="content">
										<h5 class="name" style="margin-bottom: 0px;">
											{{ Auth::guard('admin')->user()->username }}
										</h5>
									</div>
								</div>

								<div class="account-dropdown__item">
									<a href="javascript:void(0)" id="change_password"><i class="zmdi zmdi-settings"></i>Change Password</a>
								</div>

								<div class="account-dropdown__footer">
									<a href="{{ route('admin.logout') }}"><i class="zmdi zmdi-power"></i>Logout</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>