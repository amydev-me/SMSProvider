@extends('admin-layouts.company')

@section('title', 'Gateway')
@section('gateway', 'active')

@section('content')
    <manage-gateway inline-template>
        <div v-cloak>
            <div class="row mt-3">
                <div class="col">
                    <button class="btn btn-primary" @click="showAddModal">
                        <i class="fas fa-shopping-bag"></i>&nbsp; Add New Gateway
                    </button>
                </div>

            </div>
            <div class="top-campaign mt-3">
                <div class="table-responsive">
                    <table class="table table-top-campaign">
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Operator</th>
                                <th>Encoding</th>
                                <th>Sender</th>
                                <th>Inactive</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        <tr v-for="gate in gateways">
                            <td>@{{gate.country?gate.country.name:''}}</td>
                            <td>@{{gate.operator?gate.operator.name:'-'}}</td>
                            <td>@{{gate.encoding}}</td>
                            <td>@{{gate.sender}}</td>
                            <td>@{{gate.inactive?'Inactive':'Active'}}</td>
                            <td>
                                <a @click="showEditModal(gate)" title="Edit"><i class="fa fa-edit text-primary"></i></a>
                                |<a :href="'/admin/default-endpoint?gateway_id=' + gate.id" title="View Operators"><i class="far fa-eye text-info"></i></a>

                            </td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade-in" id="gatewayModal" @submit.prevent="validateData">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@{{ is_edit == false ? 'Add Gateway' : 'Edit Gateway' }}</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form autocomplete="off">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Country</label>
                                    <multiselect
                                            placeholder="Select Country"
                                            v-model="selected_country"
                                            label="name"
                                            name="country"
                                            v-validate="'required'"
                                            data-vv-name="country"
                                            track-by="name"  :allow-empty="true"
                                            :options="countries"
                                            :searchable="true" @input="selectedCountryChanged"
                                            :internal-search="true">
                                    </multiselect>

                                    <span v-if="errors.has('country')" style="color: #dd4b39">@{{ errors.first('country') }}</span>
                                </div>
                                <div class="form-group">
                                    <label>Operator<span class="text-danger">*</span></label>
                                    <multiselect
                                            placeholder="Select Operator"
                                            v-model="selected_operator"
                                            label="name"
                                            name="operator"
                                            
                                            data-vv-name="operator"
                                            track-by="name"
                                            :options="operators"
                                            :searchable="true"
                                            :internal-search="false">
                                    </multiselect>
                                    <span v-if="errors.has('operator')" style="color: #dd4b39">@{{ errors.first('operator') }}</span>
                                </div>
                                <div class="form-group">
                                    <label>Encoding<span class="text-danger">*</span></label>
                                    <select :class="errors.has('encoding') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'"
                                            name="encoding"
                                            v-model="gateway.encoding"
                                            v-validate="'required'">
                                        <option v-for="enco in encodings" :value="enco">@{{ enco}}</option>
                                    </select>
                                    <span v-if="errors.has('encoding')" style="color: #dd4b39">@{{ errors.first('encoding') }}</span>
                                </div>
                                <div class="form-group">
                                    <label>Sender<span class="text-danger">*</span></label>
                                    <input type="text" name="sender" :class="errors.has('sender') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'"
                                           v-model="gateway.sender"
                                           v-validate="'required'">
                                    <span v-if="errors.has('sender')" style="color: #dd4b39">@{{ errors.first('sender') }}</span>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select :class="errors.has('active') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" name="active" v-model="gateway.inactive" v-validate="'required'">
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
    </manage-gateway>
@endsection