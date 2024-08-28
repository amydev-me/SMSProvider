@extends('admin-layouts.company')

@section('title', 'Purchase')
@section('intl-purchase', 'active')

@section('stylesheet')
<style>
	#purchase_list_filter input {
		border: 1px solid #d2d6de !important;
	}

	@media (max-width: 575px) {
		.col-auto {
			width:100%;
		}
		
		.breadcrumb {
			margin-top: 10px;
		}
	}

	.breadcrumb {
		padding: 8px 10px;
		margin-bottom: 0px;
	}
</style>
@endsection

@section('content')
<intl-purchase inline-template>
	<div>
		<div class="row form-inline">
			<div class="col-auto">
				<button class="btn btn-primary" @click="showNewPurchase">
					<i class="fas fa-shopping-cart"></i>&nbsp; Add New Purchase
				</button>
			</div>

			<div class="col-auto">
				<div class="form-group">
					<label style="padding-right: 10px;">Purchase Date:</label>
					<date-picker v-model="f_purchase_date" id="f-purchase-date" :config="options" placeholder="Select Purchase Date" @dp-change="changeDate"></date-picker>
				</div>
			</div>
			
			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item active" aria-current="page">Purchase List</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="top-campaign mt-3">
			<div class="table-responsive no-padding">
				<table id="purchase_list" class="table table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>Amount (MMK)</th>
							<th>Purchase Date</th>
							<th>Balance (MMK)</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
					</tbody>
				</table>
			</div>
		</div>

		<div class="modal fade-in" id="purchase_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">

					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Create New Purchase</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form role="form" @submit.prevent="validateData" autocomplete="off">
						<div class="modal-body">
							<div class="form-group" v-if="edit == false">
								<label>Amount</label>
								<input type="text" :class="errors.has('amount') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" name="amount" v-model="purchase.amount" v-validate="'required|numeric'"/>

								<div class="text-danger" v-if="errors.has('amount')">@{{ errors.first('amount') }}</div>
							</div>

							<div class="form-group">
								<label>Purchase Date</label>
								<date-picker :config="options" :class="errors.has('purchase_date') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" name="purchase_date" v-model="purchase.purchase_date" v-validate="'required'" onkeydown="return false"></date-picker>
							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Save</button>
						</div>
					</form>

				</div>
			</div>
		</div>

		<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Delete Purchase</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						Are you sure you want to delete?</strong>?
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary" @click="removePurchase">Save</button>
					</div>
				</div>
			</div>
		</div>

	</div>
</intl-purchase>

@endsection