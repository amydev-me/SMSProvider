@extends('admin-layouts.company')

@section('title', 'Users')
@section('user', 'active')

@section('stylesheet')
<link href="{{ asset('css/intlTelInput.min.css') }}" media="all" rel="stylesheet" type="text/css" />

<style>
	#user_list_filter input {
		border: 1px solid #d2d6de !important;
	}

	.intl-tel-input {
		width:100%;
	}

	.intl-tel-input .selected-flag {
		z-index: 4;
	}

	.intl-tel-input .country-list {
		z-index: 5;
	}

	.input-group .intl-tel-input .form-control {
		border-top-left-radius: 4px;
		border-top-right-radius: 0;
		border-bottom-left-radius: 4px;
		border-bottom-right-radius: 0;
	}

	.iti-flag {
		background-image: url("/img/flags.png");
	}

	.dataTables_wrapper{
		margin-bottom: 10px;
	}

	@media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2 / 1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {
		.iti-flag {background-image: url("/img/flags@2x.png");}
	}

	.form-group {
		margin-bottom: 0px;
	}

	label {
		padding-top: 10px;
	}

	@media (max-width: 575px){
		.col-auto{
			width:100%;
		}

		.breadcrumb{
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
<user inline-template>
	<div>
		<div class="row form-inline">
			<div class="col-auto">
				<button class="btn btn-primary" @click="showNewUser">
					<i class="fas fa-user"></i>&nbsp; Add New User
				</button>
			</div>

			<div class="col-auto">
				<div class="form-group">
					<label>From:</label>
					<select class="form-control mr-sm-2" id="f-account-type" @change="changeType">
						<option value="">All</option>
						<option value="Free" {{ $user == 'free' ? 'selected' : '' }}>Free</option>
						<option value="Premium" {{ $user == 'premium' ? 'selected' : '' }}>Premium</option>
					</select>
				</div>
			</div>

			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item active" aria-current="page">Manage Users</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="top-campaign mt-3">
			<div class="table-responsive no-padding">
				<table id="user_list" class="table table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>Username</th>
							<th>User Type</th>
							<th>Phone</th>
							<th>Email</th>
							<th>SMS Type</th>
							<th>Remaining Credit</th>
							<th>Unpaid Credit</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
					</tbody>
				</table>
			</div>
		</div>

		<div class="modal fade-in" id="user_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Create New User</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form role="form" @submit.prevent="validateData" autocomplete="off">
						<div class="modal-body">
							<div class="form-group">
								<label>Username</label>
								<input type="text" :class="errors.has('username') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" placeholder="Username" name="username" v-model="user.username" v-validate="'required'"/>

								<div class="text-danger" v-if="errors.has('username')">@{{ errors.first('username') }}</div>
							</div>

							<div class="form-group">
								<label>Full Name</label>
								<input type="text" :class="errors.has('full_name') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" placeholder="Full Name" name="full_name" v-model="user.full_name" v-validate="'required'"/>

								<div class="text-danger" v-if="errors.has('full_name')">@{{ errors.first('full_name') }}</div>
							</div>

							<div class="form-group">
								<label>Email</label>
								<input type="text" :class="errors.has('email') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" placeholder="Email" name="email" v-model="user.email" v-validate="'required'"/>

								<div class="text-danger" v-if="errors.has('email')">@{{ errors.first('email') }}</div>
							</div>

							<div class="form-group">
								<label>Mobile</label>
								<input type="text" :class="errors.has('mobile') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" placeholder="Mobile" name="mobile" id="phone" v-model="user.mobile" v-validate="'required|verify_phone'"/>

								<div class="text-danger" v-if="errors.has('mobile')">@{{ errors.first('mobile') }}</div>
							</div>

							<div class="form-group" v-if="edit == false">
								<label>Password</label>
								<input type="text" :class="errors.has('password') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" placeholder="Password" name="password" v-model="user.password" v-validate="'required'"/>

								<div class="text-danger" v-if="errors.has('password')">@{{ errors.first('password') }}</div>
							</div>

							<div class="form-group">
								<label>SMS Type</label>
								<select :class="errors.has('sms_type') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" name="sms_type" v-model="user.sms_type" v-validate="'required'">
									<option value="Package">Package</option>
									<!-- <option value="USD">USD</option> -->
									<option value="PAYG">Pay As You Go</option>
								</select>

								<div class="text-danger" v-if="errors.has('sms_type')">@{{ errors.first('sms_type') }}</div>
							</div>

							<div class="form-group">
								<label>Company Name</label>
								<input type="text" :class="errors.has('company') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" placeholder="Company Name" name="company" v-model="user.company"/>

								<div class="text-danger" v-if="errors.has('company')">@{{ errors.first('company') }}</div>
							</div>

							<div class="form-group">
								<label>Address</label>
								<input type="text" :class="errors.has('address') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" placeholder="Address" name="address" v-model="user.address"/>

								<div class="text-danger" v-if="errors.has('address')">@{{ errors.first('address') }}</div>
							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Save</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="modal fadeIn" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Delete User</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						Are you sure you want to delete <strong>@{{ remove_user }}</strong>?
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-danger" @click="removeUser">Delete</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fadeIn" id="block_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-if="block_status == '1'">Unblock User</h5>
						<h5 class="modal-title" v-if="block_status == '0'">Block User</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						Are you sure you want to @{{ block_status == '1' ? 'unblock' : 'block' }} <strong>@{{ remove_user }}</strong>?
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="submit" :class="block_status == '1' ? 'btn btn-success' : 'btn btn-danger'" @click="removeUser">@{{ block_status == '1' ? 'Unblock' : 'Block' }}</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</user>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/intlTelInput.min.js') }}"></script>
@endsection