<header class="header-desktop">
	<div class="section__content section__content--p30">
		<div class="container-fluid">
			<div class="header-wrap float-right">
				<div class="header-button">
					<div class="account-wrap">
						<div class="account-item clearfix js-item-menu">
							<div class="image">
								<i class="fas fa-user-circle fa-2halfx"></i>
							</div>

							<!-- <div class="content">
								<a class="js-acc-btn" href="#">
									{{ Auth::guard('log')->user()->username }}
								</a>
							</div> -->

							<div class="account-dropdown js-dropdown">
								<div class="info clearfix">
									<div class="content">
										<h5 class="name">
											<a href="#">{{ Auth::guard('log')->user()->username }}</a>
										</h5>
									</div>
								</div>

								<div class="account-dropdown__body">
									<div class="account-dropdown__item">
										<a href="javascript:void(0)" id="change_password"><i class="zmdi zmdi-settings"></i>Change Password</a>
									</div>
								</div>

								<div class="account-dropdown__footer">
									<a href="{{ route('dashboard-user.logout') }}"><i class="zmdi zmdi-power"></i>Logout</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>