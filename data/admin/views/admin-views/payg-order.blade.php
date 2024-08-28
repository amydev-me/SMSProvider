@extends('admin-layouts.company')

@section('title', 'Pay As You Go Orders')
@section('payg-order', 'active')

@section('stylesheet')
<style>
	@media (max-width: 330px) {
		.col {
			max-width: 34%;
		}
	}

	.breadcrumb {
		padding: 8px 10px;
		margin-bottom: 0px;
	}

	label {
		margin-right: 10px;
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
<payg-order inline-template>
	<div v-cloak>
		<div class="row form-inline">
			<div class="col-auto">
				<div class="form-group">
					<label>Invoice Date:</label>
					<div class="form-group">
						<date-picker v-model="invoiceDate" :config="options" placeholder="Select Invoice Date" @dp-change="filterInvoice"></date-picker>
					</div>
				</div>
			</div>

			<div class="col-auto">
				<div class="form-group">
					<label>Status:</label>
					<select class="form-control mr-sm-2" v-model="status" @change="filter">
						<option value="all">All</option>
						<option value="pending">Pending</option>
						<option value="paid">Paid</option>
						<option value="cancel">Cancel</option>
					</select>
				</div>
			</div>
			
			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item active" aria-current="page">Order List</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="top-campaign mt-3">
			<div class="form-group float-left">
				<select v-model="pageSize" class="form-control" @change="filterSearch">
					<option value="10">10</option>
					<option value="25">25</option>
					<option value="50">50</option>
				</select>
			</div>

			<div class="form-group search-container float-right">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Search" v-model="search" v-on:keyup.enter="filterSearch">

					<div class="input-group-append">
						<button class="btn btn-primary" type="button" @click="filterSearch"><span class="fa fa-search"></span></button>
					</div>
				</div>
			</div>

			<div class="table-responsive">
				<table class="table table-top-campaign" v-cloak>
					<thead>
						<tr>
							<th>ID</th>
							<th>Invoice No.</th>
							<th>Username</th>
							<th>Cost</th>
							<th>Total Credit</th>
							<th>Payment Date</th>
							<th>Invoice Date</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
						<tr v-for="order in orders">
							<td>@{{ order.id }}</td>
							<td>@{{ order.invoice_no }}</td>
							<td>@{{ order.user.username }}</td>
							<td>@{{ order.cost }}</td>
							<td>@{{ order.total_credit }}</td>
							<td>@{{ formatPrettyDate(order.payment_date) }}</td>
							<td>@{{ formatPrettyDate(order.invoice_date) }}</td>
							<td>@{{ changeUppercase(order.status) }}</td>
							<td>
								<span v-if="order.status != 'paid' && order.status != 'cancel'">
									<a @click="showPaymentModal(order.id)" title="Receive Payment"><i class="fas fa-hand-holding-usd text-primary"></i></a>
									|
									<a @click="showCancelModal(order.id)" title="Cancel Order"><i class="far fa-times-circle text-danger"></i></a>
								</span>
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

		<div class="modal animation fadeIn" id="order_modal" tabindex="-1" role="dialog" aria-hidden="true">
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
	</div>
</payg-order>
@endsection