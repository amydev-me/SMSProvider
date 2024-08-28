
Vue.directive('uppercase', {
    update (el) {
        el.value = el.value.toUpperCase()
    },
});

module.exports = {

    data: function () {
        return {
            telecoms:[],
            is_edit:false,
            telecom: {
                id:null,
                name:null,
                description:null,
                end_point: null,
                username: null,
                secret: null,
                inactive:false
            },
            options: [
                { text: 'Active', value: false },
                { text: 'Inactive', value: true }
            ]
        }
    },
    methods: {
        getTelecoms:function(){
            axios.get('/admin/telecom/list').then(({data})=>{
               this.telecoms = data;
            });
        },

        showAddModal(){
            this.is_edit=false;
            this.clearData();
            $('#telecomModal').modal('show');
        },

        showEditModal(telecom){
            this.is_edit=true;
            this.telecom = Object.assign({}, telecom);
            $('#telecomModal').modal('show');
        },

        showDeleteModal(id){
            this.clearData();
            this.telecom.id = id;
            $('#deleteModal').modal('show');
        },

        clearData(){
            this.is_edit = false;
            this.telecom.id = null;
            this.telecom.name = null;
            this.telecom.description = null;
            this.telecom.end_point = null;
            this.telecom.username = null;
            this.telecom.secret = null;
            this.telecom.inactive = false;
            this.$validator.reset();
        },

        validateData() {
            this.$validator.validateAll().then(successsValidate => {
                if (successsValidate) {
                    let _meth = !this.is_edit ? 'create' : 'update';

                    axios.post('/admin/telecom/'+_meth, this.telecom).then(({data}) => {
                        this.getTelecoms();
                        $('#telecomModal').modal('hide');
                    }).catch(error => {
                        Notification.error('Error occurred while creating/updating data.');
                    });
                }
            }).catch(error => {
                console.log(error);
            });
        },
        performDelete(){
            axios.post('/admin/telecom/delete/' + this.telecom.id, this.telecom).then(({data}) => {
                if (data.success) {
                    this.getTelecoms();
                    $('#deleteModal').modal('hide');
                    Notification.success('Success');
                } else {
                    Notification.warning('Oops! Something Went Wrong!');
                }
            }).catch(error => {
                Notification.error('Error occurred while deleting data.');
            });
        }
    },
    mounted() {
        this.getTelecoms();
    }
};