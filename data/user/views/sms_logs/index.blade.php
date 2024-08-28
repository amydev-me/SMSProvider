@extends('layouts.user-master')

@section('title', 'SMS LOGS')
@section('history','active')

@section('style')
<style>
	.col-sm-4 {
		max-width: 15.333333%;
	}

	@media (min-width: 576px) {
		.exportBtn{
			margin-top:-58px;
		}
	}

	@media (max-width: 575px) {
		.col-5 {
			padding-left: 11px;
			padding-right: 0px;
			max-width: 37.666667%;
		}
	}
</style>
@endsection

@section('content')
<sms-logs inline-template>
	<div v-cloak>
		<div class="row pb-3">
			<div class="col col-sm-10">
				<div class="row">
					<div class="col-5 col-sm-3">
						<date-picker v-model="startDate" :config="options" name="start_date"></date-picker>
					</div>

					<div class="col-5 col-sm-3">
						<date-picker v-model="endDate" :config="options" name="end_date"></date-picker>
					</div>

					<div class="col-2 col-sm-3 pl-2 pt-1">
						<button type="button" @click="logsFilterByDate" class="btn btn-primary btn-sm">Search</button>
					</div>
				</div>
			</div>

			<!-- <div class="col">
				<a :href="'/export-logs?start_date='+startDate+ '&end_date='+endDate" class="float-right pl-2 pt-1">
					<button class="btn btn-primary btn-sm"><i class="fa fa-upload"></i>&nbsp; Export Excel</button>
				</a>
			</div> -->
		</div>

		<!-- TOP CAMPAIGN-->
		<div class="top-campaign">
			<div class="table-responsive">
				<table class="table table-top-campaign">
					<thead>
						<tr>
							<th scope="col">No</th>
							<th scope="col">Time Sent</th>
							<th scope="col">Recipients</th>
							<th scope="col">Sender</th>
							<th scope="col">Message</th>
							<th scope="col">Message Parts</th>
							<th scope="col">Total SMS</th>
							<th scope="col">Credit Usage</th>
							<th scope="col">Detail</th>
						</tr>
					</thead>

					<tbody>
						<tr v-for="(log,index) in logs">
							<td>@{{ pagination.from + index }}</td>
							<td>@{{ formatDate(log.create_sms) }}</td>
							<td>
								<span v-for="(detail, index) in log.log_details.slice(0,3)">
									@{{ detail.recipient }}

									<span v-if="log.log_details.slice(0,3).length != index+1">
										@{{ log.log_details.slice(0,3).length>1 ? ',' : '' }}
									</span>

									<span v-if="log.log_details.slice(0,3).length == index+1">
										<span v-if="log.log_details.slice(0,3).length >1">...</span>
									</span>
								</span>
							</td>
							<td>@{{ log.sender_name }}</td>
							<td>@{{ log.message_content }}</td>
							<td>@{{ log.message_parts }}</td>
							<td>@{{ log.total_sms }}</td>
							<td>@{{ log.total_credit }}</td>
							<td>
								<a role="button" :href="'/log-details?log_id='+log.id" class="btn btn-danger btn-sm">Detail</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<vue-pagination  :length.number="pagination.last_page" v-model="pagination.current_page" @input="logsFilterByDate"></vue-pagination>
			</div>
		</div>
	</div>
</sms-logs>
@endsection