@extends('admin-layouts.company')

@section('title', 'Operator Log Details')
@section('operator-log', 'active')

@section('stylesheet')

<style>
	#sms_list_filter input {
		border: 1px solid #d2d6de !important;
	}
</style>

@endsection

@section('content')

<div class="row">
	<div class="col">

		<div class="row">
			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item"><a href="{{ route('admin.operator-log.index') }}">SMS Logs</a></li>
						<li class="breadcrumb-item active" aria-current="page">Log Detail</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="card">
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
						({{ $sms_log->getDeliveredCount() }}) Send,
						({{ $sms_log->getFailedCount() }}) Failed,
						({{ $sms_log->getRejectedCount() }}) Rejected,
						({{ $sms_log->getDeliveredCount() }}) Success
					</div>
				</div>

				<div class="row">
					<div class="col-sm-3">
						Total Usage
					</div>

					<div class="col-sm-9">
						{{ $sms_log->total_sms }} SMS
					</div>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				Delivery Status
			</div>

			<div class="card-body">
				<div class="table-responsive no-padding">
					<table id="sms_list" class="table table-hover">
						<thead>
							<tr>
								<th>No.</th>
								<th>Recipient</th>
								<th>Country</th>
								<th>Operator</th>
								<th>Status</th>
								<th>Sent At</th>
							</tr>
						</thead>

						<tbody>
							@foreach ($sms_log_details as $detail)

							<tr>
								<th>{{ $loop->iteration }}</th>
								<td>{{ $detail->recipient }}</td>
								<td>{{ $detail->country_id ? $detail->country->name : 'unknown' }}</td>
								<td>{{ $detail->operator }}</td>
								<td>{{ $detail->status }}</td>
								<td>{{ \Carbon\Carbon::parse($detail->send_at)->format('d-M-Y h:i:s A') }}</td>
							</tr>

							@endforeach
						</tbody>
					</table>
				</div>
			</div>

			<div class="card-footer">
				<a href="{{ route('admin.compose', ['log_id' => $sms_log->id]) }}"><button type="button" class="btn btn-primary btn-sm"><i class="far fa-share-square"></i>&nbsp; Resend Sms</button></a>
			</div>
		</div>

	</div>
</div>

<div class="row" style="padding-bottom: 25px; justify-content: flex-end;">
	<div class="col-auto">
		<a href="{{ url()->previous() }}"><button type="button" class="btn btn-primary">Back</button></a>
	</div>
</div>

@endsection

@section('script')
<script>
	$(document).ready(function() {
		$('#sms_list').DataTable();
	});
</script>
@endsection