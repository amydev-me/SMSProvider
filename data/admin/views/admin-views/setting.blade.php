@extends('admin-layouts.company')

@section('title', 'Setting')
@section('setting', 'active')

@section('content')
<default-setting inline-template>
	<div class="card" v-cloak>
		<div class="card-header">
			<h4>Default Tab</h4>
		</div>

		<div class="card-body">
			<div class="default-tab">
				<nav>
					<div class="nav nav-tabs" id="nav-tab" role="tablist">
						<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home"
						   aria-selected="true">Default</a>
						<!-- <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile"
						   aria-selected="false">Bank</a>
						<a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact"
						   aria-selected="false">Phone No.</a>
						<a class="nav-item nav-link" id="nav-email-tab" data-toggle="tab" href="#nav-email" role="tab" aria-controls="nav-email"
						   aria-selected="false">Email Settings</a> -->
					</div>
				</nav>

				<div class="tab-content pl-3 pt-2" id="nav-tabContent">
					<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
						<form autocomplete="off" @submit.prevent="validateDefaultSetting">
							<div class="modal-body">
								<div class="form-group">
									<label>Sender<span class="text-danger">*</span></label>
									<input type="text" name="sender" :class="errors.has('sender') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" v-model="default_setting.sender" v-validate="'required'">
									<span v-if="errors.has('sender')" style="color: #dd4b39">@{{ errors.first('sender') }}</span>
								</div>

								<div class="form-group">
									<label>Email<span class="text-danger">*</span></label>
									<input type="text" name="email" :class="errors.has('email') ? 'au-input au-input--full form-control is-invalid phone' : 'au-input au-input--full form-control phone'" v-model="default_setting.email"  v-validate="'required|email'">
									<span v-if="errors.has('email')" style="color: #dd4b39">@{{ errors.first('email') }}</span>
								</div>

								<div class="form-group">
									<label>Facebook<span class="text-danger"></span></label>
									<input type="text" name="facebook" :class="errors.has('facebook') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" v-model="default_setting.facebook_url" v-validate="{url: {require_protocol: true }}">
									<span v-if="errors.has('facebook')" style="color: #dd4b39">@{{ errors.first('facebook') }}</span>
								</div>

								<div class="form-group">
									<label>Twitter<span class="text-danger"></span></label>
									<input type="text" name="twitter" :class="errors.has('twitter') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" v-model="default_setting.twitter_url" v-validate="{url: {require_protocol: true }}">
									<span v-if="errors.has('twitter')" style="color: #dd4b39">@{{ errors.first('twitter') }}</span>
								</div>

								<div class="form-group">
									<label>LinkedIn<span class="text-danger"></span></label>
									<input type="text" name="linkedin" :class="errors.has('linkedin') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" v-model="default_setting.linkedin_url" v-validate="{url: {require_protocol: true }}">
									<span v-if="errors.has('linkedin')" style="color: #dd4b39">@{{ errors.first('linkedin') }}</span>
								</div>

								<div class="form-group">
									<label>Phones<span class="text-danger">*</span><span style="font-size: 12px;color: #a2a2a2"> ( eg.0920xxxxx,0950xxxxxx )</span></label>
									<input type="text" name="contact_phone" :class="errors.has('contact_phone') ? 'au-input au-input--full form-control is-invalid phone' : 'au-input au-input--full form-control phone'" v-model="default_setting.phones"  v-validate="'required'">
									<span v-if="errors.has('contact_phone')" style="color: #dd4b39">@{{ errors.first('contact_phone') }}</span>
								</div>
							</div>

							<div class="modal-footer">
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</form>
					</div>

					<!-- <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
						<p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth
							master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh
							dreamcatcher synth. Cosby sweater eu banh mi, irure terry richardson ex sd. Alip placeat salvia cillum iphone.
							Seitan alip s cardigan american apparel, butcher voluptate nisi .</p>
					</div>

					<div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
						<p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth
							master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh
							dreamcatcher synth. Cosby sweater eu banh mi, irure terry richardson ex sd. Alip placeat salvia cillum iphone.
							Seitan alip s cardigan american apparel, butcher voluptate nisi .</p>
					</div>

					<div class="tab-pane fade" id="nav-email" role="tabpanel" aria-labelledby="nav-email-tab">
						<p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth
							master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh
							dreamcatcher synth. Cosby sweater eu banh mi, irure terry richardson ex sd. Alip placeat salvia cillum iphone.
							Seitan alip s cardigan american apparel, butcher voluptate nisi .</p>
					</div> -->
				</div>
			</div>
		</div>
	</div>
</default-setting>
@endsection