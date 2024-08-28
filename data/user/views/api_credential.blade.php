@extends('layouts.user-master')

@section('title', 'API Tokens')
@section('apikey','active')

@section('content')
<token-list inline-template>
	<div v-cloak>
		<div class="row" style="padding-bottom: 25px;">
			<div class="col">
				<div class="float-right"><button class="btn btn-primary btn-sm" @click="showAddModal"><i class="fas fa-key"></i>&nbsp; Add API Key</button></div>
			</div>
		</div>

		<!-- TOP CAMPAIGN-->
		<div class="top-campaign">
			<div class="table-responsive">
				<table class="table table-top-campaign">
					<thead>
						<tr>
							<th scope="col">Name</th>
							<th scope="col">API KEY</th>
							<th scope="col">Actions</th>
						</tr>
					</thead>

					<tbody>
						<tr v-for="token in tokens">
							<td>@{{token.app_name}}</td>
							<td>@{{token.api_secret}}</td>
							<td>
								<button class="item" data-toggle="tooltip" data-placement="top" title="delete" @click="showDeleteModal(token.id)">
									<i class="zmdi zmdi-delete" style="font-size: 25px;"></i>
								</button>
								{{--<button @click="showDeleteModal(token.id)" class="btn btn-danger">Delete</button>--}}
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="modal fade-in" id="addapi" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Generate Api Key</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form role="form" @submit.prevent="validateData">
						<div class="modal-body">
							<div class="form-group">
								<input type="text" :class="errors.has('app_name')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control is-valid'" placeholder="App Name" name="app_name" v-model="user_toke.app_name" v-validate="'required'">
								<div class="text-danger" v-if="errors.has('app_name')">App Name field required.</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Generate Api Key</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<delete-modal @input="successdelete" :inputid="token_id" :inputurl="removeUrl"></delete-modal>
	</div>
</token-list>
@endsection