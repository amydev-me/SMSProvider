const VuePagination = resolve => require(['../core/VuePagination'], resolve);

module.exports = {
    components: {VuePagination},
    data: function () {
        return {
            articles: [],
            article: {
                id: null,
                title: null,
                questions: null,
                answers: null
            },
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1,
                last_page: 1,
            }
        }
    },
    methods: {
        getArticles: function () {
            axios.get('/admin/article/list?page=' + this.pagination.current_page).then(({data}) => {
                this.articles = data.articles;
                this.pagination = data.pagination;
            });
        },

        showDeleteModal(id) {
            this.article.id = id;
            $('#deleteModal').modal('show');
        },

        performDelete() {
            axios.post('/admin/article/delete/' + this.article.id).then(({data}) => {
                if (data.success) {
                    this.pagination.current_page = 1;
                    this.getArticles();

                    $('#deleteModal').modal('hide');
                    Notification.success('Success');
                } else {
                    Notification.warning(data.message);
                }
            }).catch(error => {
                Notification.error('Error occurred while deleting data.');
            });
        }
    },
    mounted() {
        this.getArticles();
    }
}