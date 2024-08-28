@extends('admin-layouts.company')

@section('title', 'Package Orders')
@section('order', 'active')

@section('stylesheet')

<style>
	#order_list_filter input {
		border: 1px solid #d2d6de !important;
	}

	.form-group {
		margin-bottom: 0px;
	}

	label {
		padding: 10px;
	}

	@media (max-width: 575px) {
		.col-auto {
			width:100%;
		}
		
		.breadcrumb {
			margin-top: 10px;
		}
	}

	.breadcrumb{
		padding: 8px 10px;
		margin-bottom: 0px;
	}
</style>

@endsection

@section('content')

<order inline-template v-cloak>
	<div>
		<div class="row form-inline">
			<div class="col-auto">
				<button class="btn btn-primary" @click="showNewOrder">
					<i class="fas fa-shopping-bag"></i>&nbsp; Add New Order
				</button>
			</div>

			<div class="col-auto">
				<div class="form-group">
					<label>Order Date:</label>
					<div class="form-group">
						<date-picker v-model="orderDate" :config="options" placeholder="Select Order Date" @dp-change="changeDate"></date-picker>
					</div>
				</div>
			</div>

			<div class="col-auto">
				<div class="form-group">
					<label>Package:</label>
					<select class="form-control mr-sm-2" v-model="packageId" @change="changeStatus">
						<option value="">All</option>
						<option v-for="package in packages" :value="package.id">@{{ package.packageName }}</option>
					</select>
				</div>
			</div>

			<div class="col-auto">
				<div class="form-group">
					<label>Status:</label>
					<select class="form-control mr-sm-2" v-model="status" @change="changeStatus">
						<option value="">All</option>
						<option value="pending">Pending</option>
						<option value="confirm">Confirm</option>
						<option value="paid">Paid</option>
						<option value="cancel">Cancel</option>
					</select>
				</div>
			</div>
			
			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item active" aria-current="page" style="font-size: 14px;">Order List</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="top-campaign mt-3">
			<div class="table-responsive no-padding">
				<table id="order_list" class="table table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>Invoice No.</th>
							<th>Username</th>
							<th>Package</th>
							<th>Cost</th>
							<th>Credit</th>
							<th>Extra Credit</th>
							<th>Total Credit</th>
							<th>Payment Date</th>
							<th>Order Date</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
					</tbody>
				</table>
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

		<div class="modal animation fadeIn" id="new_order" tabindex="-1" role="dialog" aria-hidden="true">
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
							<div class="form-group">
								<label>User</label>
								<multiselect
									placeholder="Select User"
									v-model="selected_user"
									label="username"
									name="username"
									v-validate="'required'"
									data-vv-name="username"
									track-by="username"
									:loading="userLoading"
									:options="asyncUsers"
									:searchable="true"
									:internal-search="false"
									@search-change="getUsers">
								</multiselect>

								<div class="text-danger" v-if="errors.has('username')">Select a user.</div>
							</div>

							<div class="form-group">
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
									:options="asyncPackages"
									:searchable="true"
									:internal-search="false"
									@search-change="getPackages">
								</multiselect>

								<div class="text-danger" v-if="errors.has('packageName')">@{{ errors.first('packageName') }}</div>
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
</order>
@endsection