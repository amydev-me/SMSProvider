@extends('admin-layouts.company')

@section('title', 'Sender IDs')
@section('user-sender', 'active')

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
<user-sender inline-template>
	<div v-cloak>
		<div class="row form-inline">
			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item active" aria-current="page">Sender IDs</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="row mt-3">
			<div class="col">
				<button class="btn btn-primary" @click="showAddModal">
					<i class="fas fa-shopping-bag"></i>&nbsp; Add New Sender ID
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
							<th>Sender Name</th>
							<th>Username</th>
							<th>Operator</th>
							<th>Register Date</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
						<tr v-for="sender in senders">
							<td>@{{ sender.sender_name }}</td>
							<td>@{{ sender.user.username }}</td>
							<td>@{{ sender.operator_id != null ? sender.operator.name : 'Foreign' }}</td>
							<td>@{{ sender.register_at }}</td>
							<td>
								<a @click="showEditModal(sender)" title="Edit"><i class="fa fa-edit text-primary"></i></a>
								| <a @click="showDeleteModal(sender.id)" title="Delete"><i class="fa fa-trash text-danger"></i></a>
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

		<div class="modal fade-in" id="senderModal" @submit.prevent="validateData">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">@{{ is_edit == false ? 'Add Sender ID' : 'Edit Sender ID' }}</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form autocomplete="off">
						<div class="modal-body">
							<div class="form-group">
								<label>Sender Name:</label>
								<input type="text" name="sender_name" id="sender_name" v-validate="'required'" v-model="sender.sender_name" :class="errors.has('sender_name') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" autofocus> 
								<span v-if="errors.has('sender_name')" style="color: #dd4b39">@{{ errors.first('sender_name') }}</span>
							</div>

							<div class="form-group">
								<label>Username:</label>
								<multiselect
									placeholder="Search User"
									v-model="selected_user"
									label="username"
									name="username"
									v-validate="'required'"
									v-bind="usernameTab"
									data-vv-name="username"
									track-by="username"
									:loading="userLoading"
									:options="users"
									:searchable="true"
									:internal-search="false"
									
									@search-change="getUsers">
								</multiselect>

								<span v-if="errors.has('username')" style="color: #dd4b39">Please select a user.</span>
							</div>

							<div class="form-group">
								<label>Operator</label>

								<multiselect
									placeholder="Select Operator"
									v-model="selected_operator"
									label="name"
									name="name"
									v-validate="'required'"
									v-bind="operatorTab"
									data-vv-name="name"
									track-by="name"
									:options="operators"
									:searchable="true">
								</multiselect>

								<span v-if="errors.has('name')" style="color: #dd4b39">Please select an operator.</span>
							</div>

							<div class="form-group">
								<label>Register Date:</label>
								<date-picker name="register_at" v-model="sender.register_at" :config="options" v-validate="'required'"></date-picker>
								<span v-if="errors.has('register_at')" style="color: #dd4b39">@{{ errors.first('register_at') }}</span>
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
						<h5 class="modal-title">Delete Sender ID</h5>

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
</user-sender>
@endsection