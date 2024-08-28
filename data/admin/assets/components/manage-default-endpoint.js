Vue.component('multiselect', require('vue-multiselect').default);
module.exports = {

    data: function () {
        return {
            gateway_id:null,
            is_edit: false,
            endpoints: [],

            telecoms: [],
            endpoint: {
                id: null,
                gateway_id: null,
                telecom_id: null,
                sor_col: 0,
                active_endpoint: false
            },
            selected_telecom: null
        }
    },

    methods: {
        onToggleChanged(end_point){
            axios.post('/admin/default-endpoint/set-point',{
                id:end_point.id,
                gateway_id:end_point.gateway_id,
                active_endpoint:end_point.active_endpoint
            }).then(({data})=>{
                this.getEndpoints();
            })
        },

        asyncTelecom:function() {
            axios.get('/admin/telecom/select').then(({data}) => {
                this.telecoms = data;
            });
        },

        getEndpoints:function(){
            if(this.endpoint.gateway_id ==null){
                Notification.error('Gatway id field required.');
                return;
            }
            axios.get('/admin/default-endpoint/list?gateway_id='+this.endpoint.gateway_id).then(({data})=>{
                this.endpoints = data;
            }).catch(error=>{
                Notification.error('Error occurred while creating/updating data.');
            })
        },

        showAddModal(){
            this.is_edit=false;
            this.clearData();
            $('#gatewayModal').modal('show');
        },

        showEditModal(endpoint){

            this.clearData();
            this.is_edit=true;
            let tmp = Object.assign({}, endpoint);
            this.endpoint.id = tmp.id;
            this.endpoint.telecom_id = tmp.telecom_id;
            this.endpoint.active_endpoint=tmp.active_endpoint;
            if(tmp.telecom) {
                this.selected_telecom=Object.assign({},{id:tmp.telecom.id,name:tmp.telecom.name});
            }
            $('#gatewayModal').modal('show');
        },

        clearData(){
            this.endpoint.id=null;
            this.endpoint.telecom_id=null;
            this.selected_telecom = null;
            this.$validator.reset();
        },

        validateData() {
            this.$validator.validateAll().then(successsValidate => {
                if (successsValidate) {
                    if(this.selected_telecom != null) {
                        this.endpoint.telecom_id = this.selected_telecom.id;
                    }

                    let _meth = !this.is_edit ? 'create' : 'update';

                    axios.post('/admin/default-endpoint/'+_meth, this.endpoint).then(({data}) => {
                        this.getEndpoints();
                        $('#gatewayModal').modal('hide');
                    }).catch(error => {

                        if(error.response.data.message){
                            Notification.error(error.response.data.message);
                        }
                    });
                }
            }).catch(error => {
                Notification.error('Opps! Something Went Wrong!');
            });
        },
        getUrlParam() {
            var gateway_id = Helper.getUrlParameter('gateway_id');
            if (gateway_id) {
                this.endpoint.gateway_id = gateway_id;
                this.getEndpoints();
            }
        }
    },
    mounted() {
        this.asyncTelecom();
        this.getUrlParam();
    }
};