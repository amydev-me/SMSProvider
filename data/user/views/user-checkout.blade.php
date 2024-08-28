@extends('layouts.user-master')

@section('title', 'Checkout')

@section('style')
<style>
	button:disabled {
		cursor: no-drop;
	}

	.no-click {
		pointer-events: none;
		cursor: default;
	}
</style>
@endsection

@section('content')
<div class="page-content--bge5">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">

				@if (!$is_verify)
				<div class="alert alert-warning">
					Please verify your email address. Didn't get the email? <a href="{{ route('resend-mail') }}"><strong>Resend</strong> </a>
				</div>
				@endif

				<div class="card">
					<div class="card-body">
						<h3>Please Confirm Your Purchase</h3>

						<div class="outer-table-wrap">
							<div class="inner-table-wrap">
								<table class="table table-hover">
									<tbody>
										<tr>
											<th>Package</th>
											<td></td>
											<td class="text-right"><h5 style="color: #007bff;">{{ $package->packageName }}</h5></td>
										</tr>

										<tr>
											<th>Credit</th>
											<td></td>
											<td class="text-right">{{ number_format($package->credit) }}</td>
										</tr>

										@if (count($package->promotions) == 1 && $package->remaining_promo > 0)
										<tr>
											<th>Promo Credit <small>({{ $package->remaining_promo }} times remaining)</small></th>
											<td></td>
											<td class="text-right">{{ number_format($package->promotions{0}->promo_credit) }}</td>
										</tr>
										@endif

										<tr>
											<th>Total Credit</th>
											<td></td>
											<td class="text-right">
												@if (count($package->promotions) == 1 && $package->remaining_promo > 0)
												<h5>{{ number_format($package->credit + $package->promotions{0}->promo_credit) }}</h5>
												@else
												<h5>{{ number_format($package->credit) }}</h5>
												@endif
											</td>
										</tr>
									</tbody>

									<tfoot>
										<tr>
											<td></td>
											<td class="text-center"><h3>Total</h3></td>
											<td class="text-right"><h3>MMK {{ number_format($package->cost) }}</h3><small>(includes Taxes & Fees)</small></td>
										</tr>

										<tr>
											<td></td>
											<td class="text-right"></td>
											<td class="text-right"></td>
										</tr>
									</tfoot>

								</table>

								<div class="text-right">
									<a role="button" href="{{ route('buy') }}" class="btn btn-secondary">Cancel</a>
									<button id="prompt" type="submit" class="btn btn-success" @if(!$is_verify) disabled @endif>Checkout</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal animation" id="confirm_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Confirm Order</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<p>Are you sure you want to order <strong>{{ $package->packageName }}</strong> package?</p>
					</div>

					<form action="{{ route('confirm-order') }}" method="POST" role="form" autocomplete="off">
						@csrf
						<input type="hidden" name="package_id" value="{{ $package->id }}">

						<div class="modal-footer">
							<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary" id="submit"><i id="loading" class="fas fa-spinner fa-spin" style="display: none;"></i> Confirm</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$('#prompt').click(function() {
		$('#confirm_modal').modal('show');
	});

	$('#submit').click(function() {
		$(this).addClass("no-click");
		$('#loading').show();
	});
</script>
@endsection