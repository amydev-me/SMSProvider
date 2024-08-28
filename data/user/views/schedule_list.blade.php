@extends('layouts.user-master')

@section('title', 'Schedule List')
@section('schedule','active')

@section('style')
<style>
	.pagination{
		justify-content: center;;
	}
</style>
@endsection

@section('content')
<div class="row">
	<div class="col">
		<div class="top-campaign">
			<div class="table-responsive">
				<table class="table table-top-campaign  table-hover">
					<thead>
						<tr>
							<th scope="col">No</th>
							<th scope="col">Sender</th>
							<th scope="col">Message</th>
							<th scope="col">Timezone</th>
							<th scope="col">Start At</th>
							<th scope="col">Myanmar Time</th>
							<th scope="col">Status</th>
							<th scope="col">Description</th>
							<th scope="col">Action</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($messages as $index => $message)

						<tr class="clickable-row">
							<td>{{ $index + 1 }}</td>
							<td>{{ $message->sender_name }}</td>
							<td>{{ $message->message_content }}</td>
							<td>{{ $message->utc_timezone }}</td>
							<td>
								{{ Carbon\Carbon::parse($message->send_at, 'Asia/Rangoon')->timezone('UTC')->setTimezone($message->utc_timezone)->format('d M Y h:i A') }}
							</td>
							<td>{{ Carbon\Carbon::parse($message->send_at)->format('d M Y h:i A') }}</td>
							<td>{{ $message->status }}</td>
							<td>{{ $message->warn_message }}</td>
							<td>
								@if ($message->status != 'Delivered')

								<a href="{{ route('schedule.cancel', ['schedule_id' => $message->id]) }}" class="item" data-toggle="tooltip" data-placement="top" title="delete" style="color: black;">
									<i class="zmdi zmdi-close-circle" style="font-size: 25px;"></i>
								</a>

								@endif
							</td>
						</tr>

						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col">
		{{ $messages->links() }}
	</div>
</div>
@endsection