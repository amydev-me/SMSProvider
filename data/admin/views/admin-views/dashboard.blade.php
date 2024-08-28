@extends('admin-layouts.company')

@section('title', 'Admin Dashboard')
@section('dashboard', 'active')

@section('stylesheet')
<style>
	.text {
		width: 100%;
		/*text-align: right;*/
	}

	a.dashboard-card {
		width: 100%;
	}

	.overview-box .icon i {
		font-size: 45px;
	}

	.row {
		text-align: center;
	}

	h3 {
		font-size: 20px;
		margin-bottom: .4rem;
	}

	label {
		padding-right: 10px;
	}

	@media (max-width: 324px) {
		.au-card {
			padding: 35px 13px;
		}
	}
	@media only screen and (max-width: 1405px) and (min-width: 1300px) {
		.au-card {
			padding: 40px 20px 40px 20px !important;
		}
	}
</style>
@endsection

@section('content')
<div class="row form-inline mb-3">
	<div class="col">
		<div class="form-group float-right">
			<label>Year:</label>
			<select id="year" class="form-control">
				@for ($i = $current_year; $i >= 2018; $i--)
				<option value="{{ $i }}" @if($i == $selected_year) selected @endif>{{ $i }}</option>
				@endfor
			</select>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6 col-lg-3">
		<a class="dashboard-card" href="{{ route('admin.user.index') }}">
			<div class="overview-item overview-item--c2 p-3">
				<div class="overview__inner">
					<div class="overview-box clearfix pl-2">
						<div class="row">
							<div class="col-sm-4 col-xl-3" style="padding-left: 9px">
								<div class="icon">
									<i class="far fa-user"></i>
								</div>
							</div>

							<div class="col-sm-8 col-xl-9" style="padding-left: 9px">
								<div class="text">
									<h3 style="color: white">{{ number_format( $users->count() ) }}</h3>
									<span>Total Users</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-sm-6 col-lg-3">
		<a class="dashboard-card" href="{{ route('admin.user.index', ['user' => 'premium']) }}">
			<div class="overview-item overview-item--c3 p-3">
				<div class="overview__inner">
					<div class="overview-box clearfix pl-2">
						<div class="row">
							<div class="col-sm-4 col-xl-3" style="padding-left: 9px">
								<div class="icon">
									<i class="fas fa-user-tie"></i>
								</div>
							</div>

							<div class="col-sm-8 col-xl-9" style="padding-left: 9px">
								<div class="text">
									<h3 style="color: white">{{ number_format( $users->where('account_type', 'Premium')->count() ) }}</h3>
									<span>Premium Users</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-sm-6 col-lg-3">
		<a class="dashboard-card" href="{{ route('admin.order.index') }}">
			<div class="overview-item overview-item--c4 p-3">
				<div class="overview__inner">
					<div class="overview-box clearfix pl-2">
						<div class="row">
							<div class="col-sm-4 col-xl-3" style="padding-left: 9px">
								<div class="icon">
									<i class="fas fa-shopping-cart"></i>
								</div>
							</div>

							<div class="col-sm-8 col-xl-9" style="padding-left: 9px">
								<div class="text">
									<h3 style="color: white">{{ number_format( $orders->count() ) }}</h3>
									<span>Packages Sold</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-sm-6 col-lg-3">
		<div class="overview-item overview-item--c1 p-3">
			<div class="overview__inner">
				<div class="overview-box clearfix pl-2">
					<div class="row">
						<div class="col-sm-4 col-xl-3" style="padding-left: 9px">
							<div class="icon">
								<i class="far fa-user"></i>
							</div>
						</div>

						<div class="col-sm-8 col-xl-9" style="padding-left: 9px">
							<div class="text">
								<h3 style="color: white">{{ number_format( $orders->sum('cost') + $payg_orders->sum('cost') ) }}</h3>
								<span>Total Income</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<operator-chart></operator-chart>
	<delivery-chart></delivery-chart>
	<package-chart></package-chart>
</div>

<div class="row">
	<registration-chart></registration-chart>
	<package-bar-chart></package-bar-chart>
</div>
@endsection

@section('script')
<script>
	$('#year').change(function() {
		window.location.href = '/admin/dashboard?year=' + this.value;
	});
</script>
@endsection