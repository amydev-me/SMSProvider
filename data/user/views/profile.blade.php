@extends('layouts.user-master')

@section('title', 'Profile')

@section('style')
<style>
	@media (max-width: 340px) {
		.changePasswordBtn {
			margin-left: 92px;
			margin-bottom: 10px;
		}
	}
</style>
@endsection

@section('content')
<user-profile inline-template>
	<div class="row" v-cloak>
		<div class="col">
			<div class="card contact">
				<div class="card-header">
					<h5 class="mb-0">@{{ isedit?'Edit Profile':'Profile' }}</h5>
				</div>

				<ul class="list-group list-group-flush" v-show="!isedit">
					<li class="list-group-item"><i class="fa fa-user"></i>Name: @{{ user.fullName }}</li>
					<li class="list-group-item">
						<i class="fa fa-envelope"></i>Email: @{{ user.email }}
						<a href="javascript:void(0)" @click="showEmailChange" title="Change Email"><i class="fa fa-edit text-info"></i></a>
					</li>
					<li class="list-group-item"><i class="fa fa-mobile-alt"></i>Mobile: @{{ user.mobile }}</li>
					<li class="list-group-item"><i class="fa fa-building "></i>Company: @{{ user.company }}</li>
					<li class="list-group-item"><i class="fa fa-map-marker-alt "></i>Address: @{{ user.address }}</li>

					@if (Auth::guard('web')->user()->sms_type == 'USD')
					<li class="list-group-item"><i class="fa fa-dollar-sign "></i>USD Rate: {{ Auth::guard('web')->user()->usd_rate }} USD / SMS</li>
					@endif

					@if ( count(Auth::guard('web')->user()->user_senders) > 0 )
					<li class="list-group-item">
						<i class="fa fa-user-friends"></i>Sender IDs

						<ul style="margin-left: 5%">
							@foreach (Auth::guard('web')->user()->user_senders as $sender)
							<li>{{ $sender->sender_name }}</li>
							@endforeach
						</ul>
					</li>
					@endif
				</ul>

				<div class="card-body">
					<div v-show="isedit">
						<form  @submit.prevent="validateData" autocomplete="off" ref="form" method="POST" action="{{ route('user.profile.edit') }}">
							@csrf
							<div class="modal-body">
								<div class="form-group">
									<label>Contact Name</label>
									<input type="text" :class="errors.has('full_name')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'" placeholder="Name" name="full_name" v-model="user.fullName" v-validate="'required'"/>

									<div class="text-danger" v-if="errors.has('full_name')">@{{ errors.first('full_name') }}</div>
								</div>

								<div class="form-group">
									<label>Company Name</label>
									<input type="text" placeholder="Company" name="company" class="au-input au-input--full form-control" v-model="user.company"/>
								</div>

								<div class="form-group">
									<label>Address</label>
									<textarea class="form-control" placeholder="Address" name="address" v-model="user.address"></textarea>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-light" @click="isedit=false">Cancel</button>
								<button type="submit" class="btn btn-primary">Update</button>
							</div>
						</form>
					</div>

					<div class="row" v-show="!isedit">
						<div class="col-sm">
							<button type="button" class="btn btn-danger changePasswordBtn" @click="showPasswordModal">Change Password</button>

							<div class="float-right">
								<a href='javascript:window.location = document.referrer;'><button type="button" class="btn btn-light btn-sm">Back</button></a>
								<button type="button" class="btn btn-primary btn-sm" @click="clickedit"><i class="fa fa-edit"></i>&nbsp; Edit</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal animation fadeIn" id="userPasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">

					<div class="modal-header">
						<h5 class="modal-title">Change Password</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div class="form-group">
							<label>Old Password</label>
							<input type="password" v-model="oldPassword" class="au-input au-input--full form-control"/>

							<div class="text-danger" id="old_password_error"></div>
						</div>

						<div class="form-group">
							<label>New Password</label>
							<input type="password" v-model="newPassword" class="au-input au-input--full form-control"/>

							<div class="text-danger" id="new_password_error"></div>
						</div>

						<div class="form-group">
							<label>Confirm Password</label>
							<input type="password" v-model="confirmPassword" class="au-input au-input--full form-control"/>

							<div class="text-danger" id="confirm_password_error"></div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="changePassword">Save</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal animation fadeIn" id="userEmailModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">

					<div class="modal-header">
						<h5 class="modal-title">Change Email</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<div class="form-group">
							<label>New Email</label>
							<input type="text" v-model="email" class="au-input au-input--full form-control"/>

							<div class="text-danger" id="email_error"></div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="changeEmail" :disabled="loading">
							<i v-if="loading" class="fas fa-spinner fa-spin"></i> Save
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</user-profile>
@endsection