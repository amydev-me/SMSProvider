@extends('layouts.user-master')

@section('title', 'Import Contacts')
@section('contacts','active')


@section('style')

    <style>
        .pagination a.page-link.active {
            background-color: #2a7aeb;
            color: white;
        }
    </style>
    @endsection
@section('content')


    <import-contact inline-template>
        <div class="row" v-cloak>
            <div class="col">
                <div class="card contact">
                    <div class="card-header">
                        <h5 class="mb-0">Import Excel File </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('contact.import')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" v-model="collect_groups" name="groups">
                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Select the Excel file:</label>
                                    <input type="file" class="form-control-file" id="exampleFormControlFile1" name="import_file">
                                    @if ($errors->has('import_file'))
                                        <div class="text-danger"> {{ $errors->first('import_file') }}</div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="sel1">Select Group:</label>
                                    <multiselect
                                            placeholder="Select Groups"
                                            v-model="selected_groups"
                                            label="groupName"
                                                name="groupName"
                                            track-by="groupName"
                                            :options="groups"
                                            :multiple="true"
                                            :searchable="true"
                                            :internal-search="false"
                                            :taggable="true">
                                    </multiselect>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a type="button" class="btn btn-light" href='javascript:window.location = document.referrer;'>Cancel</a>
                                <button type="submit" class="btn btn-primary">Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </import-contact>
@endsection