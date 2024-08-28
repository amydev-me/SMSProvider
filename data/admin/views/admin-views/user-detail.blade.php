@extends('admin-layouts.company')

@section('title', 'User Detail')
@section('user', 'active')

@section('stylesheet')
<style>
	@media (max-width: 368px) {
		.changePassword {
			margin-top: 10px;
		}
	}
</style>
@endsection

@section('content')
<user-detail inline-template v-cloak>
	<div>
		<div class="row">
			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Manage Users</a></li>
						<li class="breadcrumb-item active" aria-current="page">User Detail</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="card contact">
			<ul class="list-group list-group-flush">
				<li class="list-group-item">
					<i class="fa fa-user-circle"></i>Username: {{ $user->username }}
				</li>

				<li class="list-group-item">
					<i class="fa fa-user"></i>Full Name: {{ $user->full_name }}
				</li>

				<li class="list-group-item">
					<i class="fa fa-envelope"></i>Email: {{ $user->email }}
				</li>

				<li class="list-group-item">
					<i class="fa fa-mobile-alt"></i>Mobile: {{ $user->mobile }}
				</li>

				<li class="list-group-item">
					<i class="fa fa-building "></i>Company: {{ $user->company }}
				</li>

				<li class="list-group-item">
					<i class="fa fa-map-marker-alt "></i>Address: {{ $user->address }}
				</li>

				<li class="list-group-item">
					<i class="fa fa-address-card"></i>Account Type: {{ $user->account_type }}
				</li>

				<li class="list-group-item">
					<i class="fa fa-location-arrow"></i></i>SMS Type: {{ $user->sms_type }}
				</li>

				@if ($user->sms_type == 'Package')

				<li class="list-group-item">
					<i class="fa fa-hand-holding-usd"></i>Remaining Credit: {{ $credits }}
					<button type="button" class="btn btn-success" @click="addCredit">Add Credit</button>
				</li>

				@elseif ($user->sms_type == 'PAYG')

				<li class="list-group-item">
					<i class="fa fa-hand-holding-usd"></i>Unpaid Credit: {{ $credits }}
					<button type="button" class="btn btn-success" @click="showInvoice">Send Invoice</button>
				</li>

				@else ($user->sms_type == 'USD')

				<li class="list-group-item">
					<i class="fa fa-dollar-sign"></i></i>Rate: @{{ rate }} USD / SMS &nbsp;
					<button type="button" class="btn btn-success" @click="setRate">Set</button>
				</li>

				<li class="list-group-item">
					<i class="fa fa-hand-holding-usd"></i>Remaining USD: {{ $credits }}
				</li>

				@endif

				<li class="list-group-item">
					<a href="{{ route('admin.user.order', ['id' => $id]) }}"><button type="button" class="btn btn-info">View Order List</button></a>

					<button type="button" class="btn btn-danger changePassword" @click="showPasswordModal">Change Password</button>
				</li>
			</ul>
		</div>

		<div class="card">
			<div class="card-header">
				<span>Specific Country Rate</span>
				<button type="button" class="btn btn-success float-right" @click="showCountryModal">Add Country Rate</button>
			</div>

			<div class="card-body">
				<div class="row" v-if="user_rates.length > 0" style="margin-bottom: 20px;">
					<div class="col"></div>

					<div class="col-auto float-right">
						<div class="input-group">
							<input type="search" class="form-control" placeholder="Search" v-model="search"/>
							<span class="input-group-addon">
								<button type="submit">
									<span class="fa fa-search"></span>
								</button>
							</span>
						</div>
					</div>
				</div>

				<table class="table" v-if="user_rates.length > 0">
					<thead>
						<tr>
							<th>Country</th>
							<th>Rate (Credit)</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>
						<tr v-for="rate in user_rates">
							<td>@{{ rate.country.name }}</td>
							<td>@{{ rate.rate }}</td>
							<td>
								<a @click="showEditModal(rate)" title="Edit"><i class="fa fa-edit text-primary"></i></a>
								| <a @click="showDeleteModal(rate.id)" title="Delete"><i class="fa fa-trash text-danger"></i></a>
							</td>
						</tr>
					</tbody>
				</table>

				<span class="text-center" v-if="user_rates.length == 0">No Specific Country Rate for this user.</span>
			</div>
		</div>

		<div class="row" style="padding-bottom: 25px;">
			<div class="col">
				<a href="{{ url('/admin/user/index') }}" class="float-right">
					<button type="button" class="btn btn-primary">Back</button>
				</a>
			</div>
		</div>

		<div class="modal animation fade-in" id="credit_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Add Credit</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div class="form-group">
							<label>Credit</label>
							<input type="text" v-model="credit" v-on:keyup.enter="saveCredit" class="au-input au-input--full form-control"/>

							<div class="text-danger" v-if="credit_error">Credit is required.</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="saveCredit">Save</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal animation fade-in" id="invoice_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Invoice</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div class="form-group">
							<label>Credit</label>
							<input type="text" v-model="unpaid_credit" v-on:keyup.enter="sendInvoice" v-on:keyup="calculateMMK" class="au-input au-input--full form-control" style="margin-bottom: 10px;" />
							<small>MMK @{{ unpaid_mmk }} (Maximum - @{{ max }} Credit)</small>

							<div class="text-danger" v-if="unpaid_credit_error">Credit is required.</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="sendInvoice" :disabled="loading"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Send</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal animation fade-in" id="usd_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">SET USD Rate</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div class="form-group">
							<label>USD</label>
							<input type="text" v-model="usd_rate" v-on:keyup.enter="saveRate" class="au-input au-input--full form-control"/>

							<div class="text-danger" v-if="usd_error">USD Rate is required.</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="saveRate">Save</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal animation fade-in" id="user_password_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">

					<div class="modal-header">
						<h5 class="modal-title">Change Password</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form role="form" @submit.prevent="validateData" autocomplete="off">
						<div class="modal-body">
							<div class="form-group">
								<label>New Password</label>
								<input type="password" name="new_password" :class="errors.has('new_password') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" v-model="user.new_password" v-validate="'required'"/>

								<div class="text-danger" v-if="errors.has('new_password')">@{{ errors.first('new_password') }}</div>
							</div>

							<div class="form-group">
								<label>Confirm Password</label>
								<input type="password" name="confirm_password" :class="errors.has('confirm_password') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" v-model="user.confirm_password" v-validate="'required'"/>

								<div class="text-danger" v-if="errors.has('confirm_password')">@{{ errors.first('confirm_password') }}</div>
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

		<div class="modal animation fade-in" id="country_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Country Rate</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div class="form-group" v-if="is_edit == false">
							<label>Country</label>
							<multiselect
								placeholder="Select Country"
								label="name"
								name="id"
								track-by="id"
								data-vv-name="id"
								v-model="selected_country"
								:options="countries">
							</multiselect>

							<div class="text-danger" v-if="country_error">Country is required.</div>
						</div>

						<div class="form-group">
							<label>Rate</label>
							<input type="text" v-model="user_rate.rate" v-on:keyup.enter="saveCountryRate" class="au-input au-input--full form-control"/>

							<div class="text-danger" v-if="country_rate_error">Rate is required.</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="saveCountryRate">Save</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade-in" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Delete Country Rate</h5>

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
</user-detail>
@endsection