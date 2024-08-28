@extends('admin-layouts.company')

@section('title', 'Countries')
@section('country', 'active')

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
<country inline-template>
	<div v-cloak>
		<div class="row form-inline">
			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item active" aria-current="page">Countries</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="row mt-3">
			<div class="col">
				<button class="btn btn-primary" @click="showAddModal">
					<i class="fas fa-shopping-bag"></i>&nbsp; Add New Country
				</button>
			</div>

			<div class="col-auto">
				<div class="input-group float-right">
					<input type="search" class="form-control" placeholder="Search" v-model="search" v-on:keyup.enter="searchClick"/>
					<span class="input-group-addon">
						<button type="submit" @click="searchClick">
							<span class="fa fa-search"></span>
						</button>
					</span>
				</div>
			</div>
		</div>

		<div class="top-campaign mt-3">
			<div class="table-responsive">
				<table class="table table-top-campaign">
					<thead>
						<tr>
							<th>Country Name</th>
							<th>ISO</th>
							<th>Code</th>
							<th>Prefix</th>
							<th>Rate (Credit)</th>
							<th>Cost (MMK)</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
						<tr v-for="country in countries">
							<td>@{{ country.name }}</td>
							<td>@{{ country.iso }}</td>
							<td>@{{ country.code }}</td>
							<td>@{{ country.prefix }}</td>
							<td>@{{ country.rate }}</td>
							<td>@{{ country.cost }}</td>
							<td>
								<a :href="'/admin/operator?country_id=' + country.id" title="View Operators"><i class="far fa-eye text-info"></i></a>
								| <a @click="showStatusModal(country)" title="Status"><i :class="country.status == '1' ? 'fa fa-exclamation-circle text-success' : 'fa fa-exclamation-circle text-warning'"></i></a>
								| <a @click="showEditModal(country)" title="Edit"><i class="fa fa-edit text-primary"></i></a>
								| <a @click="showDeleteModal(country.id)" title="Delete"><i class="fa fa-trash text-danger"></i></a>
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

		<div class="modal fade-in" id="countryModal" @submit.prevent="validateData">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">@{{ is_edit == false ? 'Add Country' : 'Edit Country' }}</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form autocomplete="off">
						<div class="modal-body">
							<div class="form-group">
								<label>Country Name:</label>
								<input type="text" name="name" v-validate="'required'" v-model="country.name" :class="errors.has('name') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'"> 
								<span v-if="errors.has('name')" style="color: #dd4b39">@{{ errors.first('name') }}</span>
							</div>

							<div class="form-group">
								<label>ISO:</label>
								<input type="text" name="iso" v-validate="'required|alpha|length:3'" v-model="country.iso" :class="errors.has('iso') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'"> 
								<span v-if="errors.has('iso')" style="color: #dd4b39">@{{ errors.first('iso') }}</span>
							</div>

							<div class="form-group">
								<label>Code:</label>
								<input type="text" name="code" v-validate="'required|alpha|length:2'" v-model="country.code" :class="errors.has('code') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'"> 
								<span v-if="errors.has('code')" style="color: #dd4b39">@{{ errors.first('code') }}</span>
							</div>

							<div class="form-group">
								<label>Prefix:</label>
								<input type="text" name="prefix" v-validate="'required|integer|min:1|max:3'" v-model="country.prefix" :class="errors.has('prefix') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'"> 
								<span v-if="errors.has('prefix')" style="color: #dd4b39">@{{ errors.first('prefix') }}</span>
							</div>

							<div class="form-group">
								<label>Rate (Credit):</label>
								<input type="text" name="rate" v-validate="'required|decimal'" v-model="country.rate" :class="errors.has('rate') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'"> 
								<span v-if="errors.has('rate')" style="color: #dd4b39">@{{ errors.first('rate') }}</span>
							</div>

							<div class="form-group">
								<label>Cost (MMK):</label>
								<input type="text" name="cost" v-validate="'required|decimal'" v-model="country.cost" :class="errors.has('cost') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'"> 
								<span v-if="errors.has('cost')" style="color: #dd4b39">@{{ errors.first('cost') }}</span>
							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal" @click="clearData">Close</button>
							<button type="submit" class="btn btn-primary">Save</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="modal fade-in" id="statusModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">@{{ country.status == '1' ? 'Deactivate' : 'Activate' }} Country</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						Are you sure you want to @{{ country.status == '1' ? 'Deactivate' : 'Activate' }}?</strong>?
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
						<button type="submit" :class="country.status == '1' ? 'btn btn-danger' : 'btn btn-success'" @click="changeStatus">@{{ country.status == '1' ? 'Deactivate' : 'Activate' }}</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade-in" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Delete Country</h5>

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
</country>
@endsection