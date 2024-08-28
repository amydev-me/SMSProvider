@extends('admin-layouts.log-master')

@section('title', 'User Order')
@section('user', 'active')

@section('stylesheet')

<style>
	#sms_list_filter input {
		border: 1px solid #d2d6de !important;
	}

	.form-group {
		margin-bottom: 0px;
	}

	label {
		padding: 10px;
	}
</style>

@endsection

@section('content')

<user-operator inline-template>
	<div>
		<div class="row form-inline">
			<div class="col-auto">
				<div class="form-group">
					<label>From:</label>
					<date-picker v-model="f_from_date" id="f-from-date" :config="options" placeholder="From Date" @dp-change="changeDate"></date-picker>
				</div>
			</div>

			<div class="col-auto">
				<div class="form-group">
					<label>To:</label>
					<date-picker v-model="f_to_date" id="f-to-date" :config="options" placeholder="To Date" @dp-change="changeDate"></date-picker>
				</div>
			</div>

			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item"><a href="{{ route('dashboard-user.index') }}">Users</a></li>
						<li class="breadcrumb-item active">{{ $user->username }}</li>
						<li class="breadcrumb-item active" aria-current="page">Operator Logs</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="top-campaign">
			<div class="table-responsive no-padding">
				<table id="sms_list" class="table table-hover">
					<thead>
						<tr>
							<th>No.</th>
							<th>Time Sent</th>
							<th>Recipients</th>
							<th>Message</th>
							<th>Message Parts</th>
							<th>Total SMS</th>
							<th>Detail</th>
						</tr>
					</thead>

					<tbody>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row" style="padding-bottom: 25px;">
			<div class="col">
				<a href="{{ route('dashboard-user.index') }}" class="float-right">
					<button type="button" class="btn btn-primary">Back</button>
				</a>
			</div>
		</div>
	</div>
</user-operator>

@endsection