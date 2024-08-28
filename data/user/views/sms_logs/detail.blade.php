@extends('layouts.user-master')

@section('title', 'SMS LOGS')
@section('history','active')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.dataTables.min.css') }}">

<style>
	#sms_list_filter input {
		border: 1px solid #d2d6de !important;
	}
</style>
@endsection

@section('content')
<div class="row">
	<div class="col">
		<div class="card contact">
			<div class="card-header">
				<h5 class="mb-0">Log Detail</h5>
			</div>

			<div class="card-body">
				<div class="row">
					<div class="col-sm-3">
						Log ID
					</div>

					<div class="col-sm-9">
						{{ $sms_log->id }}
					</div>
				</div>

				<div class="row">
					<div class="col-sm-3">
						Batch ID
					</div>

					<div class="col-sm-9">
						{{ $sms_log->batch_id }}
					</div>
				</div>
				<hr/>

				<div class="row">
					<div class="col-sm-3">
						Sender
					</div>

					<div class="col-sm-9">
						{{ $sms_log->sender_name }}
					</div>
				</div>

				<div class="row">
					<div class="col-sm-3">
						Message
					</div>

					<div class="col-sm-9">
						{{ $sms_log->message_content }}
					</div>
				</div>
				<hr/>

				<div class="row">
					<div class="col-sm-3">
						Message Part
					</div>

					<div class="col-sm-9">
						{{ $sms_log->message_parts }}
					</div>
				</div>

				<div class="row">
					<div class="col-sm-3">
					   Encoding
					</div>

					<div class="col-sm-9">
						{{ $sms_log->encoding }}
					</div>
				</div>

				<div class="row">
					<div class="col-sm-3">
						Source
					</div>

					<div class="col-sm-9">
						{{ $sms_log->source }}
					</div>
				</div>
				<hr/>

				<div class="row">
					<div class="col-sm-3">
						Total
					</div>

					<div class="col-sm-9">
						({{ $sms_log->getDeliveredCount() }}) Send, ({{ $sms_log->getFailedCount() }}) Failed, ({{ $sms_log->getRejectedCount() }}) Rejected, ({{ $sms_log->getDeliveredCount() }}) Success
					</div>
				</div>

				<div class="row">
					<div class="col-sm-3">
						Total SMS
					</div>

					<div class="col-sm-9">
						{{ $sms_log->total_sms }} SMS
					</div>
				</div>

				<div class="row">
					<div class="col-sm-3">
						Credit Usage
					</div>
					
					<div class="col-sm-9">
						{{ $sms_log->total_credit }} Credit
					</div>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<h5 class="mb-0">Delivery Status</h5>
			</div>

			<div class="card-body">
				<div class="table-responsive">
					<table id="sms_list" class="table table-hover">
						<thead>
							<tr>
								<th scope="col">No.</th>
								<th scope="col">Recipient</th>
								<th scope="col">Message ID</th>
								<th scope="col">Country</th>
								<th scope="col">Operator</th>
								<th scope="col">Status</th>
								<th scope="col">Sent At</th>
							</tr>
						</thead>

						<tbody>
							@foreach ($sms_log_details as $index => $detail)

							<tr>
								<th scope="row">{{ $index+1 }}</th>
								<td>{{ $detail->recipient }}</td>
								<td>{{ $detail->id }}</td>
								<td>{{ $detail->country_id ? $detail->country->name : 'unknown' }}</td>
								<td>{{ $detail->operator }}</td>
								<td>{{ $detail->status }}</td>
								<td>{{ \Carbon\Carbon::parse($detail->send_at)->format('d-M-Y H:i:s') }}</td>
							</tr>

							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm">
				<div class="float-left">
					<a href="{{ route('compose', ['log_id' => $sms_log->id]) }}">
						<button type="button" class="btn btn-primary btn-sm">
							<i class="far fa-share-square"></i>&nbsp; Resend Sms
						</button>
					</a>
				</div>

				<div class="col-sm">
					<div class="float-right">
						<a href="{{route('logs')}}">
							<button type="button" class="btn btn-secondary btn-sm">Back</button>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/jquery.dataTables.min.js') }}"></script>

<script>
	$(document).ready(function() {
		$('#sms_list').DataTable();
	});
</script>
@endsection