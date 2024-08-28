@extends('admin-layouts.company')

@section('title', 'Admins')
@section('admin', 'active')

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
<admin inline-template>
	<div v-cloak>
		<div class="row form-inline">
			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item active" aria-current="page">Manage Admins</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="row mt-3">
			<div class="col">
				<button class="btn btn-primary" @click="showAddModal">
					<i class="fas fa-user-tie"></i>&nbsp; Add New Admin
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
			<div class="table-responsive no-padding">
				<table class="table table-top-campaign table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>Username</th>
							<th>Full Name</th>
							<th>Level</th>
							<th>Last Login</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
						<tr v-for="admin in admins" v-if="admin.username != 'logadmin'">
							<td>@{{ admin.id }}</td>
							<td>@{{ admin.username }}</td>
							<td>@{{ admin.full_name }}</td>
							<td>@{{ admin.role }}</td>
							<td>@{{ admin.last_login }}</td>
							<td>
								<a @click="showEditModal(admin)" title="Edit"><i class="fa fa-edit text-primary"></i></a>
								| <a @click="showDeleteModal(admin.id, admin.username)" title="Delete"><i class="fa fa-trash text-danger"></i></a>
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

		<div class="modal fade-in" id="adminModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Create New Admin</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form role="form" @submit.prevent="validateData" autocomplete="off">
						<div class="modal-body">
							<div class="form-group">
								<label>Username</label>
								<input type="text" :class="errors.has('username') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" placeholder="Username" name="username" v-model="admin.username" v-validate="'required'"/>

								<div class="text-danger" v-if="errors.has('username')">@{{ errors.first('username') }}</div>
							</div>

							<div class="form-group">
								<label>Full Name</label>
								<input type="text" :class="errors.has('full_name') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" placeholder="Full Name" name="full_name" v-model="admin.full_name" v-validate="'required'"/>

								<div class="text-danger" v-if="errors.has('full_name')">@{{ errors.first('full_name') }}</div>
							</div>

							<div class="form-group">
								<label>Role Level</label>
								<select :class="errors.has('role') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" name="role" v-model="admin.role" v-validate="'required'">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
								</select>

								<div class="text-danger" v-if="errors.has('role')">@{{ errors.first('role') }}</div>
							</div>

							<div class="form-group" v-if="is_edit == false">
								<label>Password</label>
								<input type="text" :class="errors.has('password') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" placeholder="Password" name="password" v-model="admin.password" v-validate="'required'"/>

								<div class="text-danger" v-if="errors.has('password')">@{{ errors.first('password') }}</div>
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
						<h5 class="modal-title">Delete Admin</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						Are you sure you want to delete <strong>@{{ remove_admin }}</strong>?
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-danger" @click="performDelete">Delete</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</admin>

@endsection