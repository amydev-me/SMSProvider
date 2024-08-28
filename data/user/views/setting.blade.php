@extends('layouts.user-master')

@section('title', 'Account Setting')

@section('content')
<user-setting inline-template>
	<div class="row">
		<div class="col">
			<div class="card contact">
				<div class="card-header">
					<h5 class="mb-0">Settings</h5>
				</div>

				<ul class="list-group list-group-flush">
					<li class="list-group-item">
						<div class="row">
							<div class="col m-auto">
								<i class="fa fa-envelope"></i>Newsletter and Promotions
							</div>

							<div class="col-md-2 text-right">
								<toggle-button class="m-auto" :value="newsletter_alert" :sync="true" :labels="{checked: 'On', unchecked: 'Off'}" :width="60" :height="25" @change="toggleNewsletter"/>
							</div>
						</div>
					</li>

					@if (Auth::guard('web')->user()->sms_type != 'PAYG')

					<li class="list-group-item">
						<div class="row">
							<div class="col m-auto">
								@if (Auth::guard('web')->user()->sms_type == 'Package')

								<i class="fa fa-exclamation-circle"></i>Minimum Credit Alert

								@elseif (Auth::guard('web')->user()->sms_type == 'USD')

								<i class="fa fa-exclamation-circle"></i>Minimum USD Alert

								@endif
							</div>

							<div class="col-md-4">
								<input type="text" class="form-control form-control-sm" v-model="setting.minimum_credit"/>
							</div>
						</div>

						<br/>

						<div class="row">
							<div class="col m-auto">
								<i class="fa fa-exclamation-circle"></i>Email Alert
							</div>

							<div class="col-md-2 text-right">
								<toggle-button class="m-auto" :value="credit_email_alert" :sync="true" :labels="{checked: 'On', unchecked: 'Off'}" :width="60" :height="25" @change="toggleEmailAlert"/>
							</div>
						</div>

						<br/>

						<div class="row">
							<div class="col m-auto">
								<i class="fa fa-exclamation-circle"></i>SMS Alert
							</div>

							<div class="col-md-2 text-right">
								<toggle-button class="m-auto" :value="credit_sms_alert" :sync="true" :labels="{checked: 'On', unchecked: 'Off'}" :width="60" :height="25" @change="toggleSmsAlert"/>
							</div>
						</div>
					</li>

					@endif

					<li class="list-group-item text-right">
						<button class="btn btn-primary btn-sm" @click="saveSetting">Save</button>
					</li>
				</ul>
			</div>
		</div>
	</div>
</user-setting>
@endsection