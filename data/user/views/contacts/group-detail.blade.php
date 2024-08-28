@extends('layouts.user-master')

@section('title', 'Create Contact')
@section('contacts','active')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/pretty-checkbox.min.css') }}">

<style>
	.table label {
		cursor: pointer;
		margin: 0px;
	}

	#contact_multi_modal {
		z-index: 1000 !important;
	}

	.modal-backdrop {
		z-index: 990 !important;
	}

	#contact_delete_modal {
		z-index: 1050 !important;
	}

	#delete_background {
		z-index: 1040;
		position: fixed;
		height: 100%;
		width: 100%;
		background: rgba(0, 0, 0, 0.7);
		top: 0px;
		left: 0px;
	}

	@media (max-width: 387px) {
		.excelBtn {
			margin-bottom: 10px;
		}
	}

	@media (min-width: 388px) and (max-width: 984px) {
		.deleteBtn {
			margin-top: 10px;
		}
	}

	@media (min-width: 992px) and (max-width: 1224px) {
		.deleteBtn {
			margin-top: 10px;
		}
	}
</style>
@endsection

@section('content')
<group-detail inline-template>
	<div>

		<div id="delete_background" style="display: none;"></div>

		<div class="row" v-cloak>
			<div class="col">
				<div class="card">

					<div class="card-header">
						<h5 class="mb-0">@{{ group.groupName }}</h5>
					</div>

					<div class="card-body">
						<div class="card-title bold">Description</div>

						<p>@{{ group.description ? group.description : 'No Description' }}</p>
						<hr>

						<label class="card-title bold">Contacts</label>

						<a class="btn btn-link btn-sm" :href="'/contact/create?group_id=' + group_id"><i class="fa fa-user-plus"></i>&nbsp; Create contact and add to group</a>

						<button type="button" class="btn btn-warning btn-sm" @click="showDeleteMultiContactModal"><i class="fa fa-users"></i>&nbsp; Delete Contacts</button>

						<div class="contacts">
							<a v-for="contact in contacts" :href="'/contact/view?contact_id=' + contact.id + '&group_id=' + group_id" style="margin-right: 5px;">
								<span class="badge badge-primary">
									<span>@{{ contact.contactName }} <i class="fa fa-edit fa-sm"></i></span>
								</span>
							</a>
						</div>
						<hr>

						<div class="row">
							<div class="col-sm col-md-6">
								<a class="btn btn-sm btn-success excelBtn" :href="'/contact/export?group_id=' + group_id">
									<i class="fa fa-upload"></i>&nbsp; Export excel for this group
								</a>

								<button type="button" class="btn btn-info btn-sm" @click="editModal"><i class="fa fa-edit"></i>&nbsp; Edit Group</button>
								
								<button type="button" class="btn btn-danger btn-sm deleteBtn" @click="showDeleteModal"><i class="fa fa-trash"></i>&nbsp; Delete Group</button>

								
							</div>

							<div class="col-sm col-md-6">
								<hr class="d-block d-sm-none">
								<div class="float-right">
									<a href="{{route('address-book.index')}}"><button type="button" class="btn btn-secondary btn-sm">Back</button></a>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="modal fade" id="goupmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Edit Group</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form role="form" @submit.prevent="validateGroup('form-group')" data-vv-scope="form-group">
						<div class="modal-body">
							<div class="form-group">
								<input type="text" :class="errors.has('form-group.groupName')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'" placeholder="Enter Group Name" name="groupName" v-model="edit_group.groupName" v-validate="'required'">
								<div class="text-danger" v-if="errors.has('form-group.groupName')">Group Name field required.</div>
							</div>

							<div class="form-group">
								<textarea class="au-input au-input--full form-control form-control" v-model="edit_group.description"> </textarea>
							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Update</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<delete-modal @input="successdelete" :inputid="group_id" :inputurl="removeUrl"></delete-modal>

		<div class="modal fade" id="contact_multi_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-scrollable" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Contacts</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body" style="overflow-y: auto; max-height: 500px;">
						<div class="table-responsive">
							<table class="table table-sm table-hover">
								<thead>
									<tr>
										<th>
											<div class="pretty p-svg">
												<input type="checkbox" @change="checkContacts($event)"/>

												<div class="state p-primary">
													<!-- svg path -->
													<svg class="svg svg-icon" viewBox="0 0 20 20">
														<path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white; fill: white;"></path>
													</svg>

													<label></label>
												</div>
											</div>
										</th>

										<th>Name</th>
										<th>Mobile</th>
									</tr>
								</thead>

								<tbody>
									<tr v-for="(contact, index) in contacts">
										<td>
											<div class="pretty p-svg">
												<input type="checkbox" :value="contact.id" :id="contact.id" v-model="selected_contacts"/>

												<div class="state p-primary">
													<!-- svg path -->
													<svg class="svg svg-icon" viewBox="0 0 20 20">
														<path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white; fill: white;"></path>
													</svg>

													<label></label>
												</div>
											</div>
										</td>

										<td><label :for="contact.id">@{{ contact.contactName }}</label></td>

										<td><label :for="contact.id">@{{ contact.mobile }}</label></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-danger" @click="promptDelete">Delete</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="contact_delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Delete Contact</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						Are you sure you want to delete selected contacts?
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-danger" @click="confirmDelete">Delete</button>
					</div>
				</div>
			</div>
		</div>

	</div>
</group-detail>
@endsection