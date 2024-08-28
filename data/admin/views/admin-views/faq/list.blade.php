@extends('admin-layouts.company')

@section('title', 'Article')
@section('article', 'active')

@section('content')
    <manage-article inline-template>
        <div v-cloak>
            <div class="modal fade-in" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Country</h5>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            Are you sure you want to delete?
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger" @click="performDelete">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <a class="btn btn-primary" href="article/create" role="button">
                        <i class="fas fa-shopping-bag"></i>&nbsp; Add New FAQ
                    </a>
                </div>

            </div>
            <div class="top-campaign mt-3">
                <div class="table-responsive">
                    <table class="table table-top-campaign">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="article in articles">
                                <td>@{{article.title}}</td>
                                <td>@{{article.inactive?'Inactive':'Active'}}</td>
                                <td>
                                    <a :href="'article/edit/'+article.id"><i class="fa fa-edit text-primary"></i></a>
                                    | <a @click="showDeleteModal(article.id)" title="Delete"><i class="fa fa-trash text-danger"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <vue-pagination :length.number="pagination.last_page" v-model="pagination.current_page" @input="getArticles"></vue-pagination>
                </div>
            </div>
        </div>
    </manage-article>
@endsection