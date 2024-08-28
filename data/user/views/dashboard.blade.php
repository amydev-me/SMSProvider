@extends('layouts.user-master')

@section('title', 'Dashboard')
@section('dashboard', 'active')

@section('style')
<style>
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

	.alert-warning {
		font-size: 12px;
	}

	label {
		padding-right: 10px;
	}
</style>
@endsection

@section('content')

@if (Auth::guard('web')->user()->accept_updated_terms == 0)

	@if ( $term->expire_at < \Carbon\Carbon::now() )

	<div class="row">
		<div class="col">
			<div class="alert alert-danger">
				Your services are limited until you accept our updated <a href="{{ route('terms') }}" target="_blank">Terms & Conditions</a>. Click <a href="{{ route('user.terms.accept') }}">Here</a> to accept.
			</div>
		</div>
	</div>

	@else

	<div class="row">
		<div class="col">
			<div class="alert alert-warning">
				We have updated our <a href="{{ route('terms') }}" target="_blank">Terms & Conditions</a>. Click <a href="{{ route('user.terms.accept') }}">Here</a> to accept before {{ \Carbon\Carbon::parse($term->expire_at)->format('d M Y') }} or your services will be stopped.
			</div>
		</div>
	</div>

	@endif

@endif

<div class="row form-inline mb-3">
	<div class="col">
		<div class="form-group float-right">
			<label>Year:</label>
			<select id="year" class="form-control">
				@for ($i = $current_year; $i >= $register_year; $i--)
				<option value="{{ $i }}" @if($i == $selected_year) selected @endif>{{ $i }}</option>
				@endfor
			</select>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6 col-lg-3">
		<div class="overview-item overview-item--c2 p-3">
			<div class="overview__inner">
				<div class="overview-box clearfix">
					<div class="row">
						<div class="col-xl-2"></div>
						<div class="col-sm-4 col-xl-2" style="padding-left: 9px">
							<div class="icon">
								<i class="fas fa-location-arrow"></i>
							</div>
						</div>

						<div class="col-sm-8 col-xl-8" style="padding-left: 9px">
							<div class="text m-t-10">
								<h3 style="color: white">{{ number_format( Auth::guard('web')->user()->getSmsSentCount($selected_year) ) }}</h3>
								<span>SMS Sent</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-6 col-lg-3">
		<div class="overview-item overview-item--c3 p-3">
			<div class="overview__inner">
				<div class="overview-box clearfix pl-2">
					<div class="row">
						<div class="col-xl-2"></div>
						<div class="col-sm-4 col-xl-2" style="padding-left: 9px;">
							<div class="icon">
								<i class="fas fa-tachometer-alt"></i>
							</div>
						</div>

						<div class="col-sm-8 col-xl-8" style="padding-left: 9px;">
							<div class="text m-t-10">
								<h3 style="color: white">{{ number_format( Auth::guard('web')->user()->usage($selected_year) ) }}</h3>
								<span>Credit Used</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-6 col-lg-3">
		<div class="overview-item overview-item--c4 p-3">
			<div class="overview__inner">
				<div class="overview-box clearfix pl-2">
					<div class="row">
						<div class="col-xl-2"></div>
						<div class="col-sm-4 col-xl-2" style="padding-left: 9px;">
							<div class="icon">
								<i class="fas fa-percent"></i>
							</div>
						</div>

						<div class="col-sm-8 col-xl-8" style="padding-left: 9px;">
							<div class="text m-t-10">
								<h3 style="color: white">{{ Auth::guard('web')->user()->getDeliveryRate($selected_year) }} %</h3>
								<span>Delivered</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-6 col-lg-3">
		<div class="overview-item overview-item--c1 p-3">
			<div class="overview__inner">
				<div class="overview-box clearfix pl-2">
					<div class="row">
						<div class="col-xl-2"></div>
						<div class="col-sm-4 col-xl-2" style="padding-left: 9px;">
							<div class="icon">
								<i class="far fa-user"></i>
							</div>
						</div>

						<div class="col-sm-8 col-xl-8" style="padding-left: 9px;">
							<div class="text m-t-10">
								<h3 style="color: white">{{ Auth::guard('web')->user()->contacts->count() }}</h3>
								<span>Contacts</span>
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
	<product-usage></product-usage>
	<sms-usage></sms-usage>
	<last-week></last-week>
</div>
@endsection

@section('script')
<script>
	$('#year').change(function() {
		window.location.href = '/dashboard?year=' + this.value;
	});
</script>
@endsection