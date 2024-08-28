<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<style>
		@page {
			margin: 0px;
		}

		body {
			margin: 0px;
		}

		.clearfix::after {
			content: "";
			clear: both;
			display: table;
		}

		.clearfix::after {
			content: "";
			clear: both;
			display: table;
		}

		.logostyle {
			width: 100%;
			height: 140px;
			float: left;
			background-color: #0000ff9e;
			clear:left;
		}

		.infostyle {
			text-align: right;
			color: white;
			padding-right: 40px;
			float: right;
			clear: right;
			margin-top: 50px;
		}

		.linestyle {
			height: 1px;
			border: none;
			color:#ddd;background-color:#ddd;
		}

		.table {
			width: 100%;
			order-spacing: 0;
			border-collapse: collapse
		}

		thead {
			display: table-header-group;
			vertical-align: middle;
			border-color: inherit;
		}

		.tabel tr {
			display: table-row;
			vertical-align: inherit;
			border-color: inherit;
			height:50px;
		}

		.table>thead>tr {
			height:50px;
		}

		.table>tbody>tr {
			height:50px;
		}

		.table th,.table td {
			padding: 8px;
			border-bottom: 1px solid #ddd;
		}

		.table th {
			color: #0000ff9e;
		}

		.m-t-30 {
			margin-top: 30px;
		}

		.row {
			margin-right: -10px;
			margin-left: -10px;
		}

		h4 {
			font-size:18px;
			font-family: inherit;
			font-weight: 500;
			line-height: 1.1;
			color: inherit;
			display: block;
		}

		b {
			font-weight: 700;
		}

		.label {
			display: inline;
			padding: 5px;
			font-size: 75%;
			font-weight: 700;
			line-height: 1;
			color: #ddd;
			text-align: center;
			white-space: nowrap;
			vertical-align: baseline;
			border-radius: 10px;
			height: 32px;
			background-color: #ebc142;
		}

		.label-warning {
			background-color: #ebc142;
		}

		.label-danger {
			background-color: #FF6C60;
		}

		.label-success {
			background-color: #2eb398;
		}

		.parentstyle{
			float: left;
			clear:left;
			padding-left: 30px;
		}

		.invoiceinfo {
			text-align: right;
			float: right;
			clear: right;
			padding: 5px;
			margin-bottom: 10px;
			padding-right: 30px;
			display: block;
		}

		.bb .invoiceinfo p {
			margin: 0px;
			display: block;
			-webkit-margin-before: 1em;
			-webkit-margin-after: 1em;
			-webkit-margin-start: 0px;
			-webkit-margin-end: 0px;
		}
	</style>
</head>
<body>
	<div style="display: block" class="bb">
		<div class="clearfix">
			<div class="logostyle clearfix">
				<div style="float: left;margin-top: 34px;padding-left: 30px">
					<img src="{{public_path().'/img/logo.png'}}" style="padding-top: 10px;width: 55px;height: auto">
					<h2 style="float: left;color: white;padding-left: 60px;padding-top: 6px;">TripleSMS</h2>
				</div>

				<div class="infostyle" >
					<span>info@triplesms.com</span><br/>
					<abbr>Phone:</abbr>
					<span style="margin-right: 4px">+959 507 414 9</span><br>
					<span style="margin-right: 4px">+959 958 848 388</span><br>
					<span style="margin-right: 4px">+959 441 216 033</span><br>
				</div>
			</div>
		</div>

		<div style="padding: 10px">
			<div class="clearfix" style="margin-top: 10px">
				<div class="parentstyle">
					Invoice To <br>
					{{ $invoice->user->full_name }}<br>
					{{ $invoice->user->mobile }}<br>
					{{ $invoice->user->email }}
				</div>

				<div class="invoiceinfo">
					Invoice Number:  {{ $invoice->invoice_no }}<br>

					@if ($invoice->user->sms_type == 'PAYG')
					Invoice Date: {{ $invoice->invoice_date->format('d-M-Y') }}<br>
					@endif

					@if ($invoice->payment_date != NULL)
					Payment Date: {{ $invoice->payment_date->format('d-M-Y') }}<br>
					@endif

					Status: <strong>{{ strtoupper($invoice->status) }}</strong>
				</div>
			</div>

			<table class="table m-t-30">
				<tbody>
					@if ($invoice->user->sms_type == 'Package')

					<tr>
						<th style="width: 30%; text-align: right;">Package</th>
						<td></td>
						<td style="text-align: right;"><h5 style="color: #007bff;">{{ $invoice->package->packageName }}</h5></td>
					</tr>

					<tr>
						<th style="width: 30%; text-align: right;">Credit</th>
						<td></td>
						<td style="text-align: right;">{{ number_format($invoice->credit) }}</td>
					</tr>

					@elseif ($invoice->user->sms_type == 'PAYG')

					<tr>
						<th style="width: 30%; text-align: right;">{{ $invoice->status == 'paid' ? 'Credit' : 'Unpaid Credit' }}</th>
						<td></td>
						<td style="text-align: right;">{{ number_format($invoice->credit) }}</td>
					</tr>

					@else

					<tr>
						<th style="width: 30%; text-align: right;">USD</th>
						<td></td>
						<td style="text-align: right;">{{ number_format($invoice->total_usd) }}</td>
					</tr>

					@endif

					@if (isset($invoice->extra_credit) && $invoice->extra_credit != NULL && $invoice->extra_credit > 0)
					<tr>
						<th style="width: 30%; text-align: right;">Promo Credit</th>
						<td></td>
						<td style="text-align: right;">{{ number_format($invoice->extra_credit) }}</td>
					</tr>
					@endif

					@if ($invoice->user->sms_type == 'Package')
					<tr>
						<th style="width: 30%; text-align: right;">Total Credit</th>
						<td></td>
						<td style="text-align: right;">
							<h5>{{ number_format($invoice->total_credit) }}</h5>
						</td>
					</tr>
					@endif

				</tbody>

				<tfoot>
					<tr>
						<td style="width: 30%; text-align: right;"></td>
						<td style="text-align: center;"><h3>Total</h3></td>
						<td style="text-align: right;"><h3>MMK {{ number_format($invoice->cost) }}</h3><small>(includes Taxes & Fees)</small></td>
					</tr>
				</tfoot>
			</table>
		</div>

		<div style="text-align: center; padding-top: 70px; font-size: 24px; color: #0000ff9e;">Thank You!</div>
	</div>
</body>
</html>