@extends('admin-layouts.company')

@section('title', 'Default Endpoint')
@section('gateway', 'active')

@section('content')
    <manage-endpoint inline-template>
        <div v-cloak>
            <div class="row mt-3">
                <div class="col">
                    <button class="btn btn-primary" @click="showAddModal">
                        <i class="fas fa-shopping-bag"></i>&nbsp; Add New Endpoint
                    </button>
                </div>
            </div>
            <div class="top-campaign mt-3">
                <div class="table-responsive">
                    <table class="table table-top-campaign">
                        <thead>
                        <tr>
                            <th>Country</th>
                            <th>Telecom</th>
                            <th>Operator</th>
                            <th>Encoding</th>
{{--                            <th style="text-align: center;">Sorting</th>--}}
                            <th>Endpoint</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                            <tr v-for="endpoint in endpoints">
                                <td>@{{endpoint.gateway?(endpoint.gateway?endpoint.gateway.country.name:''):''}}</td>
                                <td>@{{endpoint.telecom?endpoint.telecom.name:''}}</td>
                                <td>@{{endpoint.gateway?(endpoint.gateway.operator?endpoint.gateway.operator.name:'-'):''}}</td>

                                <td>@{{endpoint.gateway?endpoint.gateway.encoding:''}}</td>
{{--                                <td style="text-align: center;">@{{endpoint.sort_col}}</td>--}}
                                <td>
                                    <toggle-button @change="onToggleChanged(endpoint)" v-model="endpoint.active_endpoint"
                                            color="rgb(36, 171, 82)"
                                            class="m-auto" :sync="true" :labels="{checked: 'On', unchecked: 'Off'}" :width="60" :height="25"/>
                                </td>
                                <td>
                                    <a @click="showEditModal(endpoint)" title="Edit"><i class="fa fa-edit text-primary"></i></a>
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
                            <h5 class="modal-title">@{{ is_edit == false ? 'Add Endpoint' : 'Edit Endpoint' }}</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form autocomplete="off">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Telecom</label>
                                    <multiselect
                                            placeholder="Select Telecom"
                                            v-model="selected_telecom"
                                            label="name"
                                            name="country"
                                            v-validate="'required'"
                                            data-vv-name="telecom"
                                            track-by="name"
                                            :options="telecoms"
                                            :searchable="true"
                                            :internal-search="true">
                                    </multiselect>

                                    <div class="text-danger" v-if="errors.has('telecom')">Select a telecom.</div>
                                </div>

{{--                                <div class="form-group">--}}
{{--                                    <label>Status</label>--}}
{{--                                    <select :class="errors.has('active') ? 'au-input au-input--full form-control is-invalid' : 'au-input au-input--full form-control'" name="active" v-model="gateway.inactive" v-validate="'required'">--}}
{{--                                        <option v-for="option in options" :value="option.value">@{{ option.text}}</option>--}}
{{--                                    </select>--}}
{{--                                    <div class="text-danger" v-if="errors.has('active')">@{{ errors.first('active') }}</div>--}}
{{--                                </div>--}}
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
    </manage-endpoint>
@endsection