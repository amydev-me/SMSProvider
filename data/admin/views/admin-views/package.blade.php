@extends('admin-layouts.company')

@section('title', 'Packages')
@section('package', 'active')

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
</style>
@endsection

@section('content')
<package inline-template>
	<div>
		<div class="row form-inline">
			<div class="col-auto">
				<button class="btn btn-primary" @click="showAddModal">
					<i class="fas fa-shopping-bag"></i>&nbsp; Add New Package
				</button>
			</div>

			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item active" aria-current="page">Packages</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="top-campaign mt-3">
			<div class="table-responsive">
				<table class="table table-top-campaign" v-cloak>
					<thead>
						<tr>
							<th>Package Name</th>
							<th>Credit</th>
							<th>Cost</th>
							<th>Currency</th>
							<th>Promotion</th>
							<th>Promo Credit</th>
							<th>Maximum Purchase</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
						<tr v-for="package in packages">
							<td>@{{ package.packageName }}</td>
							<td>@{{ package.credit }}</td>
							<td>@{{ package.cost }}</td>
							<td>@{{ package.currency_type }}</td>
							<td>@{{ package.promotions.length == 1 ? 'Active' : '' }} <a class="text-primary" @click="viewPromotion(package.promotions[0])" v-if="package.promotions.length == '1'">[ Edit ]</a></td>
							<td>@{{ package.promotions.length == 1 ? package.promotions[0].promo_credit : '' }}</td>
							<td>@{{ package.promotions.length == 1 ? package.promotions[0].max_purchase : '' }}</td>
							<td>
								<a @click="showEditModal(package)" title="Edit"><i class="fa fa-edit text-primary"></i></a>
								<a @click="showPromotionModal(package)" title="Add Promotion" v-if="package.promotions.length == '0' && package.packageName != 'Free'">| <i class="fas fa-hand-holding-usd text-success"></i></a>
								<a @click="deletePromotionModal(package.promotions[0].id)" title="Delete Promotion" v-if="package.promotions.length == '1'">| <i class="fas fa-ban text-danger"></i></a>
							</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>

		<div class="modal fade-in" id="package_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-if="edit == false">Create Package</h5>
						<h5 class="modal-title" v-if="edit == true">Edit Package</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form role="form" @submit.prevent="validateData" autocomplete="off">
						<div class="modal-body">
							<div class="form-group" v-if="free == false">
								<label>Package Name</label>
								<input type="text" id="package_name" :class="errors.has('packageName') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" name="packageName" v-model="package.packageName" v-validate="'required'"/>

								<div class="text-danger" v-if="errors.has('packageName')">@{{ errors.first('packageName') }}</div>
							</div>

							<div class="form-group">
								<label>Credit</label>
								<input type="text" :class="errors.has('credit') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" name="credit" v-model="package.credit" v-validate="'required|decimal'"/>

								<div class="text-danger" v-if="errors.has('credit')">@{{ errors.first('credit') }}</div>
							</div>

							<div class="form-group">
								<label>Cost</label>
								<input type="text" :class="errors.has('cost') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" name="cost" v-model="package.cost" v-validate="'required|decimal'"/>

								<div class="text-danger" v-if="errors.has('cost')">@{{ errors.first('cost') }}</div>
							</div>

							<div class="form-group">
								<label>Currency</label>
								<input type="text" :class="errors.has('currency_type') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" name="currency_type" v-model="package.currency_type" v-validate="'required'"/>

								<div class="text-danger" v-if="errors.has('currency_type')">@{{ errors.first('currency_type') }}</div>
							</div>

							<div class="form-group">
								<label>Status</label>
								<select :class="errors.has('active') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" name="active" v-model="package.active" v-validate="'required'">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								</select>

								<div class="text-danger" v-if="errors.has('active')">@{{ errors.first('active') }}</div>
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

		<div class="modal fade-in" id="promotion_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-if="edit_promo == false">Create Promotion</h5>
						<h5 class="modal-title" v-if="edit_promo == true">Edit Promotion</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form role="form" @submit.prevent="validatePromotion" autocomplete="off">
						<div class="modal-body">
							<div class="form-group">
								<label>Promo Credit</label>
								<input type="text" class="au-input au-input--full form-control" v-model="promotion.promo_credit"/>

								<div class="text-danger" v-if="errors.has('promo_credit')">@{{ errors.first('promo_credit') }}</div>
							</div>

							<div class="form-group">
								<label>Maximum Purchase</label>
								<input type="text" class="au-input au-input--full form-control" v-model="promotion.max_purchase"/>

								<div class="text-danger" v-if="errors.has('max_purchase')">@{{ errors.first('max_purchase') }}</div>
							</div>

							<div class="form-group">
								<label>Promo Status</label>
								<input type="text" class="au-input au-input--full form-control" v-model="promotion.promo_status" placeholder="Rainy Season Promotion"/>

								<div class="text-danger" v-if="errors.has('promo_status')">@{{ errors.first('promo_status') }}</div>
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

		<div class="modal fade-in" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Delete Promotion</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						Are you sure you want to delete?
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-danger" @click="performDelete">Delete</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</package>
@endsection