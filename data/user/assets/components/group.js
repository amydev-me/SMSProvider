module.exports= {
    data: function () {
        return {
            groups: [],
            group: {
                id: null,
                user_id: null,
                groupName: null,
                description: null
            }
        }
    },
    methods: {
        showContactModal() {
            $('#contact_modal').modal('show');
        },
        cleanData() {
            this.group.id = null;
            this.group.user_id = null;
            this.group.groupName = null;
            this.$validator.reset();
        },
        showAddModal() {
            this.cleanData();
            $('#goupmodal').modal('show');
        },
        validateData() {
            this.$validator.validateAll().then(successsValidate => {
                if (successsValidate) {
                    this.submit();
                }
            }).catch(error => {
                Notification.warning('Opps!Something went wrong.');
            });
        },
        submit() {
            axios.post('/group/create', this.group).then(({data}) => {
                if (data.status == true) {
                    this.groups.push(data.group);
                    $('#goupmodal').modal('hide');
                    Notification.success('Success');
                } else {
                    Notification.error('Error occurs while creating data.');
                }
            }).catch(error => {
                if (error.response.status == 401 || error.response.status == 419) {
                    window.location.href = '/login';
                } else {
                    Notification.error('Error occured while deleting data.');
                }
            });
        },
        getGroups() {
            axios.get('/list/groups').then(({data}) => {
                this.groups = data;
            });
        },
    },
    mounted() {
        this.getGroups();
    }
}