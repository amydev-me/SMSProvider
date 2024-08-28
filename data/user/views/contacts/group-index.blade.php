@extends('layouts.user-master')

@section('title', 'Address Book')
@section('address-book', 'active')

@section('style')
<style type="text/css">
	@media (max-width: 436px) {
		.importContact{
			margin-top: 12px;
		}
	}
</style>
@endsection

@section('content')
<group-list inline-template>
	<div v-cloak>
		<!-- Modal -->
		<div class="modal fade" id="goupmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Create Group</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form role="form" @submit.prevent="validateData">
						<div class="modal-body">
							<div class="form-group">
								<input type="text" :class="errors.has('groupName')?'au-input au-input--full form-control is-invalid':'au-input au-input--full form-control'" placeholder="Enter Group Name" name="groupName" v-model="group.groupName" v-validate="'required'">
								<div class="text-danger" v-if="errors.has('groupName')">Group Name field required.</div>
							</div>
							<div class="form-group">
								<textarea class="au-input au-input--full form-control form-control" v-model="group.description"> </textarea>


							</div>
						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Create</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="row" style="padding-bottom: 25px;">
			<div class="col">
				<a class="btn btn-primary btn-sm float-right"  href="{{route('contact.create')}}">
					<i class="fa fa-user-plus"></i>&nbsp; Create Contact
				</a>

				<button class="btn btn-primary btn-sm float-right mr-1" @click="showAddModal"><i class="fas fa-plus"></i>&nbsp; Create Group</button>

				<a class="btn btn-primary btn-sm float-right importContact" href="{{route('contact.import')}}" style="margin-right: 3px;"> <i class="fas  fa-upload"></i>&nbsp; Import Contacts</a>
			</div>
		</div>

		<div class="row folder">
			<div class="col-sm-6 col-md-4 col-xl-3" v-for="group in groups">
				<a :href="'/group/detail?group_id='+group.id" class="card">
					<div class="card-body">
						<div class="folder-text"><i class="fas fa-address-book fa-lg"></i><span>@{{ group.groupName }}</span>
							<span class="badge badge-info" v-if="group.contact_count>0"> @{{ group.contact_count }}</span>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</group-list>
@endsection