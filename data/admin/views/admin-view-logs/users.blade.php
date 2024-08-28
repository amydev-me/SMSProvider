@extends('admin-layouts.log-master')

@section('title', 'Users')
@section('user', 'active')

@section('stylesheet')

<style>
	#user_list_filter input {
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

<user-logs inline-template>
	<div>
		<div class="row form-inline">
			<div class="col-auto">
				<div class="form-group">
					<label>From:</label>
					<select class="custom-select mr-sm-2" id="f-account-type" @change="changeType">
						<option value="">All</option>
						<option value="Free">Free</option>
						<option value="Premium">Premium</option>
					</select>
				</div>
			</div>

			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item active" aria-current="page">Users</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="top-campaign">
			<div class="table-responsive no-padding">
				<table id="user_list" class="table table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>User Type</th>
							<th>Phone</th>
							<th>Email</th>
							<th>View</th>
						</tr>
					</thead>

					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</user-logs>

@endsection