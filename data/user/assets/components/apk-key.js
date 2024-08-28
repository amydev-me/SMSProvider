const DeleteModal = resolve => require(['../core/DeleteModal'], resolve);
module.exports= {
    components:{DeleteModal},
    data: function () {
        return {
            removeUrl:'/rest_api/delete/',
            user_toke: {
                api_key: null,
                api_secret: null,
                app_name: null,
                user_id: null,
            },
            tokens: [],
            token_id:null
        }
    },
    methods: {
        clearData(){
            this.user_toke.api_key=null;
            this.user_toke.api_secret=null;
            this.user_toke.app_name=null;
            this.user_toke.user_id=null;
            this.$validator.reset();
        },

        showAddModal(){
            this.clearData();
            //reset validator
            $('#addapi').modal('show');
        },

        showDeleteModal (id) {
            this.token_id = id;
            $('#deleteModal').modal('show');
        },
        successdelete(){
            this.getTokenList();
            this.token_id=null;
            $('#deleteModal').modal('hide');
        },
        getTokenList() {
            axios.get('/rest_api/tokens').then(({data}) => {
                this.tokens = data;
            });
        },
        validateData() {
            this.$validator.validateAll().then(successsValidate => {
                if (successsValidate) {
                    this.submit();
                }

            }).catch(error=>{
                    Notification.warning('Opps!Something went wrong.');
            });
        },
        submit() {
            axios.post('/rest_api/create', this.user_toke).then(({data}) => {
                if (data.status == true) {
                    this.getTokenList();
                    $('#addapi').modal('hide');
                     Notification.success('Success');
                }else{
                    Notification.error('Error occurs while creating data.');
                }
            }).catch(error => {
                if (error.response.status == 401 || error.response.status == 419) {
                  window.location.href = '/login';
                } else {
                  Notification.error('Error occured while deleting data.');
                }
            });
        }
    },
    mounted() {
        this.getTokenList();
    }
}