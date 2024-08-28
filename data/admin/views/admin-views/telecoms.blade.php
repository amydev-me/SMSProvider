@extends('admin-layouts.company')

@section('title', 'Telecoms')
@section('telecom', 'active')

@section('content')
    <manage-telecom inline-template>
        <div v-cloak>
            <div class="row mt-3">
                <div class="col">
                    <button class="btn btn-primary" @click="showAddModal">
                        <i class="fas fa-shopping-bag"></i>&nbsp; Add New Telecom
                    </button>
                </div>

            </div>
            <div class="top-campaign mt-3">
                <div class="table-responsive">
                    <table class="table table-top-campaign">
                        <thead>
                        <tr>
                            <th>Telecom</th>
                            <th>Key</th>
                            <th>Secret</th>
                            <th>End Point</th>
                            <th>Inactive</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for="telecom in telecoms">
                            <td>@{{telecom.name}}</td>
                            <td>@{{telecom.username}}</td>
                            <td>@{{telecom.secret}}</td>
                            <td>@{{telecom.end_point}}</td>
                            <td>@{{telecom.inactive?'Inactive':'Active'}}</td>
                            <td>
                                 <a @click="showEditModal(telecom)" title="Edit"><i class="fa fa-edit text-primary"></i></a>
                            </td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade-in" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Operator</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">Are you sure you want to delete?</div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger" @click="performDelete">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade-in" id="telecomModal" @submit.prevent="validateData">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@{{ is_edit == false ? 'Add Telecom' : 'Edit Telecom' }}</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form autocomplete="off">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="telecom.name" name="name" v-validate="'required'" v-uppercase>
                                    <span v-if="errors.has('name')" style="color: #dd4b39">@{{ errors.first('name') }}</span>
                                </div>
                                <div class="form-group">
                                    <label>Key<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="telecom.username" name="username" v-validate="'required'">
                                    <span v-if="errors.has('username')" style="color: #dd4b39">@{{ errors.first('username') }}</span>
                                </div>
                                <div class="form-group">
                                    <label>Secret<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="telecom.secret" autocomplete="new-password" name="secret" v-validate="'required'">
                                    <span v-if="errors.has('secret')" style="color: #dd4b39">@{{ errors.first('secret') }}</span>
                                </div>
                                <div class="form-group">
                                    <label>End Point<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" v-model="telecom.end_point" name="endpoint" v-validate="'required'">
                                    <span v-if="errors.has('endpoint')" style="color: #dd4b39">@{{ errors.first('endpoint') }}</span>
                                </div>
                                <div class="form-group">
                                    <label>Description<span class="text-danger">*</span></label>
                                    <textarea  class="form-control" v-model="telecom.description"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select :class="errors.has('active') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" name="active" v-model="telecom.inactive" v-validate="'required'">
                                        <option v-for="option in options" :value="option.value">@{{ option.text}}</option>

                                    </select>

                                    <div class="text-danger" v-if="errors.has('active')">@{{ errors.first('active') }}</div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" @click="clearData">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </manage-telecom>
@endsection