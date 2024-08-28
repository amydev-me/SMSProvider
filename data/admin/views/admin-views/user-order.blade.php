@extends('admin-layouts.company')

@section('title', 'User Order')
@section('user', 'active')

@section('stylesheet')
<link href="{{ asset('css/intlTelInput.css') }}" media="all" rel="stylesheet" type="text/css" />

<style>
	#order_list_filter input {
		border: 1px solid #d2d6de !important;
	}
</style>
@endsection

@section('content')
<user-order inline-template :sms-type="'{!! $user->sms_type !!}'">
	<div v-cloak>
		<div class="row">
			<div class="col" style="padding-top: 0.7rem;">
				<button type="button" class="btn btn-primary" @click="showNewOrder">
					<i class="fas fa-shopping-bag"></i>&nbsp; Add Order
				</button>
			</div>

			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Manage Users</a></li>
						<li class="breadcrumb-item"><a href="{{ route('admin.user.view', ['id' => $id]) }}">{{ $user->username }}</a></li>
						<li class="breadcrumb-item active" aria-current="page">Order List</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="top-campaign">
			<div class="table-responsive no-padding">
				<table id="order_list" class="table table-hover">
					<thead>
						<tr>
							<th>Order ID</th>
							<th>Invoice Number</th>

							@if ($user->sms_type == 'Package')
							<th>Package</th>
							<th>Cost</th>
							<th>Credit</th>
							<th>Extra Credit</th>
							<th>Total Credit</th>
							@elseif ($user->sms_type == 'PAYG')
							<th>Cost</th>
							<th>Total Credit</th>
							@endif

							<th>Payment Date</th>

							@if ($user->sms_type == 'Package')
							<th>Order Date</th>
							@elseif ($user->sms_type == 'PAYG')
							<th>Invoice Date</th>
							@endif

							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row" style="padding-bottom: 25px;">
			<div class="col">
				<a href="{{ route('admin.user.view', ['id' => $id]) }}" class="float-right">
					<button type="button" class="btn btn-primary">Back</button>
				</a>
			</div>
		</div>

		<div class="modal animation fade-in" id="order_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">@{{ order.title }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<span>@{{ order.text }}</span>
					</div>

					<div class="modal-footer" >
						<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary" @click="submit" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Confirm</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal animation fade-in" id="new_order" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">

					<div class="modal-header">
						<h5 class="modal-title">Add New Order</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form role="form" @submit.prevent="validateData" autocomplete="off">
						<div class="modal-body">
							<div class="form-group" v-if="smsType == 'Package'">
								<label>Package Name</label>
								<multiselect
									placeholder="Select Package"
									v-model="selected_package"
									label="packageName"
									name="packageName"
									v-validate="'required'"
									data-vv-name="packageName"
									track-by="packageName"
									:loading="packageLoading"
									:options="packages"
									:searchable="true"
									:internal-search="false"
									@search-change="getPackages">
								</multiselect>

								<div class="text-danger" v-if="errors.has('packageName')">@{{ errors.first('packageName') }}</div>
							</div>

							<div class="form-group" v-if="smsType == 'USD'">
								<label>USD Amount</label>
								<input type="text" name="total_usd" :class="errors.has('total_usd') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" v-model="new_order.total_usd" v-validate="'required|numeric'"/>

								<div class="text-danger" v-if="errors.has('total_usd')">@{{ errors.first('total_usd') }}</div>
							</div>

							<div class="form-group">
								<label>Payment Status</label>
								<select :class="errors.has('status') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" name="status" v-model="new_order.status" v-validate="'required'">
									<option value="confirm">Confirm</option>
									<option value="paid">Paid</option>
								</select>
							</div>
						</div>

						<div class="modal-footer" >
							<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Save</button>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</user-order>
@endsection