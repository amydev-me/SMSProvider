@extends('layouts.user-master')

@section('title', 'Invoices')
@section('invoice', 'active')

@section('content')
<div class="top-campaign">
	<div class="table-responsive">
		<table class="table table-top-campaign">
			<thead>
				<tr>
					<th scope="col">Invoice No</th>
					<th scope="col">Date</th>

					@if (Auth::guard('web')->user()->sms_type == 'Package')

					<th scope="col">Package</th>
					<th scope="col">Credit</th>
					<th scope="col">Promo Credit</th>
					<th scope="col">Total Credit</th>
					<th scope="col">Price</th>

					@elseif (Auth::guard('web')->user()->sms_type == 'PAYG')

					<th scope="col">Total Credit</th>
					<th scope="col">Price</th>
					<th scope="col">Status</th>

					@else

					<th scope="col">Total USD</th>

					@endif

					<th scope="col">Download</th>
				</tr>
			</thead>

			<tbody>
				@foreach ($invoices as $package)
				<tr>
					<td>{{ $package->invoice_no }}</td>

					@if (Auth::guard('web')->user()->sms_type == 'Package')

					<td>{{ \Carbon\Carbon::parse($package->order_date)->format('d M Y H:i:s') }}</td>
					<td>{{ $package->package->packageName }}</td>
					<td>{{ number_format($package->credit) }}</td>
					<td>{{ $package->extra_credit > 0 ? number_format($package->extra_credit) : '' }}</td>
					<td>{{ number_format($package->total_credit) }}</td>
					<td>{{ number_format($package->cost) }}</td>
					<td><a href="{{ route('download', ['invoice_id' => $package->invoice_no]) }}">Download</a></td>

					@elseif (Auth::guard('web')->user()->sms_type == 'PAYG')

					<td>{{ \Carbon\Carbon::parse($package->invoice_date)->format('d M Y H:i:s') }}</td>
					<td>{{ number_format($package->credit) }}</td>
					<td>{{ number_format($package->credit * 6) }} MMK</td>
					<td>{{ ucfirst($package->status) }}</td>
					<td><a href="{{ route('download.payg', ['invoice_id' => $package->invoice_no]) }}">Download</a></td>

					@else

					<td>{{ \Carbon\Carbon::parse($package->order_date)->format('d M Y H:i:s') }}</td>
					<td>{{ number_format($package->total_usd) }}</td>
					<td><a href="{{ route('download', ['invoice_id' => $package->invoice_no]) }}">Download</a></td>

					@endif
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection