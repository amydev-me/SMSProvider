@extends('layouts.user-master')

@section('title', 'View Contact')
@section('contact-list','active')

@section('content')
<contact-list inline-template>
	<div v-cloak>
		<div class="row" style="padding-bottom: 25px;">
			<div class="col-sm-6">
				<form class="form-inline mb-4 mb-sm-0" style="flex-flow: row;" @submit.prevent="filterContact">
					<input class="form-control my-sm-0" type="search" placeholder="Search" aria-label="Search" v-model="filtertext">
					<button class="btn btn-light ml-1" type="submit"><i class="fas fa-search"></i></button>
				</form>
			</div>
			<div class="col text-right">
				<a href="{{route('contact.create')}}"><button class="btn btn-primary btn-sm"><i class="fa fa-user-plus"></i>&nbsp; Create Contact</button></a>
				<a class="btn btn-primary btn-sm" href="{{route('contact.import')}}" style="margin-right: 3px;"> <i class="fas  fa-upload"></i>&nbsp; Import Contacts</a>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<div class="top-campaign">
					<div class="table-responsive">
						<table class="table table-top-campaign  table-hover">
							<thead>
								<tr>
									<th scope="col">No.</th>
									<th scope="col">Name</th>
									<th scope="col">Phone Number</th>
									<th scope="col">Company Name</th>
									<th scope="col">Actions</th>
								</tr>
							</thead>

							<tbody>
								<tr v-for="(contact, index) in contacts" class="clickable-row">
									<td>@{{ pagination.from + index }}</td>
									<td>@{{ contact.contactName }}</td>
									<td>@{{ contact.mobile }}</td>
									<td>@{{ contact.companyName }}</td>
									<td>
										<a :href="'/contact/view?contact_id='+contact.id"><i class="fa fa-edit"></i></a>
										<button @click="showDeleteModal(contact.id)" class="text-danger"><i class="fa fa-trash"></i></button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<vue-pagination  :length.number="pagination.last_page" v-model="pagination.current_page" @input="paginationClick"></vue-pagination>
			</div>
		</div>

		<delete-modal @input="successdelete" :inputid="contact_id" :inputurl="removeUrl"></delete-modal>
	</div>
</contact-list>
@endsection