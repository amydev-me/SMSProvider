@extends('admin-layouts.company')

@section('title', 'Operator Prices')
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
</style>
@endsection

@section('content')
<operator inline-template country-id="{{ $country->id }}">
	<div v-cloak>
		<div class="row form-inline">
			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.country') }}">Countries</a></li>
						<li class="breadcrumb-item active" aria-current="page">{{ $country->name }}</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="row mt-3">
			<div class="col">
				<button class="btn btn-primary" @click="showAddModal">
					<i class="fas fa-shopping-bag"></i>&nbsp; Add New Operator
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
							<th scope="col">Operator Name</th>
							<th scope="col">Numbers</th>
							<th scope="col">Action</th>
						</tr>
					</thead>

					<tbody>
						<tr v-for="operator in operators">
							<td>@{{ operator.name }}</td>
							<td>@{{ operator.operator_detail.length }} <a href="javascript:void(0)" @click="showNumbers(operator.id)">[ View ]</a></td>
							<td>
								<!-- <a href="javascript:void(0)" @click="showStatusModal(operator)" title="Status"><i :class="operator.status == '1' ? 'fa fa-exclamation-circle text-success' : 'fa fa-exclamation-circle text-warning'"></i></a> -->
								<a @click="showAddNumber(operator)" title="Add Number"><i class="fa fa-phone text-info"></i></a>
								| <a @click="showEditModal(operator)" title="Edit"><i class="fa fa-edit text-primary"></i></a>
								| <a @click="showDeleteModal(operator.id)" class="text-danger"><i class="fa fa-trash"></i></a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="modal fade-in" id="operatorModal" @submit.prevent="validateData">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">@{{ is_edit == false ? 'Add Operator' : 'Edit Operator' }}</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form autocomplete="off">
						<div class="modal-body">
							<div class="form-group">
								<label>Operator Name:</label>
								<input type="text" name="name" v-validate="'required'" v-model="operator.name" :class="errors.has('name') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'"> 
								<span v-if="errors.has('name')" style="color: #dd4b39">@{{ errors.first('name') }}</span>
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

		<div class="modal fade-in" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Delete Operator</h5>

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

		<div class="modal fade-in" id="numbersModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Numbers</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<ul class="list-group">
							<li class="list-group-item d-flex justify-content-between align-items-center" v-for="number in numbers">
								@{{ number.starting_number }}
								<button class="badge badge-danger badge-pill" @click="removeNumber(number)">Delete</button>
							</li>
						</ul>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade-in" id="addNumberModal" @submit.prevent="addNumber">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Add Number</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form>
						<div class="modal-body">
							<label>Starting Number:</label>

							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text {{ $country->prefix ? '' : 'bg-danger text-white' }}" id="basic-addon1">{{ $country->prefix ? $country->prefix : 'No Prefix' }}</span>
								</div>

								<input type="text" class="au-input au-input--full form-control" v-model="operator_detail.starting_number"/>
							</div>

							@if ($country->prefix == null)
							<span class="text-danger">Add country code first.</span>
							@endif
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							@if ($country->prefix != null)
							<button type="submit" class="btn btn-primary">Save</button>
							@endif
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</operator>
@endsection