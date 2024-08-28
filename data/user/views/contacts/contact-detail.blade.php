@extends('layouts.user-master')

@section('title', 'View Contact')
@section('contacts','active')

@section('style')
<style>
	.multiselect__content-wrapper {
		max-height: 161px !important;
		position: relative;
	}
</style>
@endsection

@section('content')
<contact-detail inline-template>
	<div class="row" v-cloak>
		<delete-modal @input="successdelete" :inputid="contact_id" :inputurl="removeUrl"></delete-modal>
		<div class="col">
			<div class="card contact">
				<div class="card-header">
					<h5 class="mb-0">@{{ isedit ? 'Edit Contact' : 'Contact Details' }}</h5>
				</div>

				<ul class="list-group list-group-flush" v-show="!isedit">
					<li class="list-group-item"><i class="fa fa-user"></i>Name: @{{ contact.contactName }}</li>

					<li class="list-group-item"><i class="fa fa-mobile-alt"></i>Mobile: @{{ contact.mobile }}</li>

					<li class="list-group-item"><i class="fa fa-address-book"></i>Group:
						<a v-for="group in selected_groups" :href="'/group/detail?group_id='+group.id" style="margin-right: 5px;">
							<span class="badge badge-primary">
								<span>@{{ group.groupName }}</span>
							</span>
						</a>
					</li>

					<li class="list-group-item"><i class="fa fa-envelope"></i>Email: @{{contact.email}}</li>
					
					<li class="list-group-item"><i class="fa fa-building "></i>Company: @{{contact.companyName}}</li>
					
					<li class="list-group-item"><i class="fa fa-map-marker-alt "></i>Address: @{{contact.address}}</li>
				</ul>

				<div class="card-body">
					<div v-show="isedit">
						<form role="form"  @submit.prevent="validateData">
							<div class="modal-body">
								<div class="form-group">
									<label>Contact Name <span class="text-danger">*</span></label>
									<input type="text" :class="errors.has('contactName')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'" placeholder="Name" name="contactName" v-model="contact.contactName" v-validate="'required'">
									<div class="text-danger" v-if="errors.has('contactName')">@{{ errors.first('contactName') }}</div>
								</div>

								<div class="form-group">
									<label>Mobile <span class="text-danger">*</span></label>
									<input type="tel" v-model="contact.mobile" id="phone" name="mobile" :class="errors.has('mobile')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'" v-validate="'required'">

									<div class="text-danger" v-if="errors.has('mobile')">@{{ errors.first('mobile') }}</div>
								</div>

								<div class="form-group">
									<label for="sel1">Select Group <span class="text-danger">*</span></label>
									<multiselect
											placeholder="Select Groups"
											v-model="selected_groups"
											label="groupName"
											name="groupName"
											v-validate="'verify_group'"
											v-bind="groupTab"
											data-vv-name="grade"
											track-by="groupName"
											:options="groups"
											:multiple="true"
											:searchable="true"
											:internal-search="false"
											:taggable="true">
									</multiselect>
									<div class="text-danger" v-if="errors.has('grade')">The group field required.</div>
								</div>

								<div class="form-group">
									<label>Email</label>
									<input type="email"  placeholder="someone@mail.com" name="email" :class="errors.has('email')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'" v-model="contact.email" >

									<div class="text-danger" v-if="errors.has('contactName')">@{{ errors.first('email') }}</div>
								</div>

								<div class="form-group">
									<label>Company Name</label>
									<input type="text"  placeholder="company" name="company" :class="errors.has('company')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'" v-model="contact.companyName">

									<div class="text-danger" v-if="errors.has('company')">@{{ errors.first('company') }}</div>
								</div>

								<div class="form-group">
									<label>Address</label>
									<textarea class="form-control" placeholder="Type address here" name="address" v-model="contact.address"></textarea>

								</div>
								<div class="form-group">
									<label>Birth Date</label>
									<date-picker v-model="contact.birthdate" :config="options"></date-picker>
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
							<div class="float-right">
								<a href='javascript:window.location = document.referrer;'><button type="button" class="btn btn-light btn-sm">
										Back</button></a>
								<button type="button" class="btn btn-light btn-sm" @click="showDeleteModal"><i class="fa fa-trash"></i>&nbsp; Delete</button>
								<button type="button" class="btn btn-primary btn-sm" @click="clickedit"><i class="fa fa-edit"></i>&nbsp; Edit</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</contact-detail>
@endsection