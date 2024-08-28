@extends('layouts.user-master')

@section('title','Create Contact')
@section('contacts','active')

@section('style')
<style>
	.multiselect__content-wrapper {
		max-height: 161px !important;
		position: relative;
	}

	/*.multiselect__tags {
		border-color: @{{ errors.has('grade') ? #e5e5e5 : #dc3545 }};
	}*/
</style>
@endsection

@section('content')
<create-contact inline-template>
	<div class="row" v-cloak>
		<div class="col">
			<div class="card contact">
				<div class="card-header">
					<h5 class="mb-0">Create Contact</h5>
				</div>

				<div class="card-body">
					<form role="form" @submit.prevent="validateData">
						<div class="modal-body">
							<div class="form-group">
								<label>Contact Name <span class="text-danger">*</span></label>
								<input type="text" :class="errors.has('contactName')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'" name="contactName" v-model="contact.contactName" >

								<div class="text-danger" v-if="errors.has('contactName')">@{{ errors.first('contactName') }}</div>
							</div>

							<div class="form-group">
								<label>Mobile <span class="text-danger">*</span></label>
								<input type="tel" v-model="contact.mobile" id="phone" name="mobile" :class="errors.has('mobile')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'" >

								<div class="text-danger" v-if="errors.has('mobile')">@{{ errors.first('mobile') }}</div>
							</div>

							<div class="form-group">
								<label for="sel1">Select Group <span class="text-danger">*</span></label>
								<multiselect
									placeholder="Select Group"
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
								<input type="email" name="email" :class="errors.has('email')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'" v-model="contact.email" >

								<div class="text-danger" v-if="errors.has('contactName')">@{{ errors.first('email') }}</div>
							</div>

							<div class="form-group">
								<label>Company Name</label>
								<input type="text" name="company" :class="errors.has('company')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'"  v-model="contact.companyName">

								<div class="text-danger" v-if="errors.has('company')">@{{ errors.first('company') }}</div>
							</div>

							<div class="form-group">
								<label>Address</label>
								<textarea class="form-control" name="address" v-model="contact.address"></textarea>
							</div>

							<div class="form-group">
								<label>Date of Birth</label>
								<date-picker v-model="contact.birthdate" :config="options"></date-picker>
							</div>
						</div>

						<div class="modal-footer">
							<a type="button" class="btn btn-light" href="{{URL::previous()}}">Cancel</a>
							<button type="submit" class="btn btn-primary">Save</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</create-contact>
@endsection