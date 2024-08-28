@extends('admin-layouts.log-master')

@section('title', 'Search SMS')
@section('search', 'active')

@section('stylesheet')
<style>
	@media (max-width: 330px) {
		.col {
			max-width: 34%;
		}
	}

	.form-group {
		margin-bottom: 0px;
	}

	label {
		padding-right: 10px;
	}

	.breadcrumb {
		padding: 8px 10px;
		margin-bottom: 0px;
	}

	.pagination__navigation--disabled {
		opacity: .6;
		pointer-events: none;
	}

	.pagination__more {
		pointer-events: none;
	}
</style>
@endsection

@section('content')
<search-sms inline-template>
	<div v-cloak>
		<div class="row form-inline">
			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item active" aria-current="page">Search</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="row form-inline mt-3">
			<div class="col-auto">
				<div class="form-group">
					<label>Search Type:</label>
					<select class="custom-select" v-model="search_type">
						<option value="">Select</option>
						<option value="log_id">Log ID</option>
						<option value="message_id">Message ID</option>
					</select>
				</div>
			</div>

			<div class="col-auto">
				<div class="form-group">
					<label>Search:</label>
					<input type="text" class="form-control" v-model="keyword" placeholder="Search">
				</div>
			</div>

			<div class="col-auto">
				<div class="input-group float-right">
					<button type="submit" class="btn btn-primary" @click="searchClick">
						<span class="fa fa-search"></span> Search
					</button>
				</div>
			</div>
		</div>

		<div class="top-campaign mt-3">
			<div class="table-responsive">
				<table class="table table-top-campaign">
					<thead>
						<tr>
							<th>Log ID</th>
							<th>Message ID</th>
							<th>Mobile</th>
							<th>Status</th>
							<th>Operator Status</th>
							<th>Sent At</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
						<tr v-for="log in logs">
							<td>@{{ pagination.log_id }}</td>
							<td>@{{ log.id }}</td>
							<td>@{{ log.recipient }}</td>
							<td>@{{ log.status }}</td>
							<td>@{{ log.operator_status }}</td>
							<td>@{{ log.send_at }}</td>
							<td>
								<a :href="'/dashboard-user/detail/' + pagination.log_id"><i class="far fa-eye text-info"></i></a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 text-center">
				<vue-pagination :length.number="pagination.last_page" v-model="pagination.current_page" @input="filter"></vue-pagination>
			</div>
		</div>
	</div>
</search-sms>
@endsection