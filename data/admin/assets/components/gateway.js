Vue.component('multiselect', require('vue-multiselect').default);
module.exports = {

    data: function () {
        return {
            gateways:[],
            is_edit:false,
            gateway: {
                id:null,
                country_id:null,
                operator_id:null,
                encoding: 'Plain Text',
                sender:null,
                inactive:false
            },
            options: [
                { text: 'Active', value: false },
                { text: 'Inactive', value: true }
            ],
            countries:[],
            operators:[],
            selected_country:null,
            selected_operator:null,
            encodings: ['Plain Text','Unicode']
        }
    },

    methods: {
        selectedCountryChanged(){
            if(this.selected_country==null){
                return;
            }

            this.selected_operator=null;
            this.operators =[];

            this.asyncOperator(this.selected_country.id).then(({data}) => {
                this.operators = data;
            });
        },

        asyncOperator(country_id){
            return axios.get('/admin/operator/async-by-country/'+country_id);
        },

        asyncCountry:function() {


            axios.get('/admin/country/select').then(({data}) => {
                this.countries = data;
            });
        },

        getGateways:function(){
            axios.get('/admin/gateway/list').then(({data})=>{
                this.gateways = data;
            });
        },

        showAddModal(){
            this.is_edit=false;
            this.clearData();
            $('#gatewayModal').modal('show');
        },

        showEditModal(gateway){

            this.clearData();
            this.is_edit=true;
            let tmp = Object.assign({}, gateway);
            this.gateway.id=tmp.id;
            if(tmp.country) {
                this.selected_country=Object.assign({},{id:tmp.country.id,name:tmp.country.name});
                this.selectedCountryChanged();
            }
            this.selected_operator=tmp.operator?Object.assign({},{id:tmp.operator.id,name:tmp.operator.name}):null;
            this.gateway.encoding= tmp.encoding;
            this.gateway.inactive= tmp.inactive;
            this.gateway.sender = tmp.sender;
            $('#gatewayModal').modal('show');
        },

        clearData(){
            this.gateway.id=null;
            this.gateway.country_id=null;
            this.gateway.operator_id=null;
            this.gateway.encoding= "Plain Text";
            this.gateway.inactive=false;
            this.gateway.sender = null;
            this.selected_country=null;
            this.selected_operator=null;
            this.$validator.reset();
        },

        validateData() {
            this.$validator.validateAll().then(successsValidate => {
                if (successsValidate) {
                    this.gateway.country_id = this.selected_country.id;
                     if(this.selected_operator != null) {
                         this.gateway.operator_id = this.selected_operator.id;
                     }

                    let _meth = !this.is_edit ? 'create' : 'update';

                    axios.post('/admin/gateway/'+_meth, this.gateway).then(({data}) => {
                        this.getGateways();
                        $('#gatewayModal').modal('hide');
                    }).catch(error => {
                        if(error.response.data){
                            Notification.error(error.response.data.message);
                        }

                    });
                }
            }).catch(error => {
                Notification.error("Invalid Data");
            });
        }

    },
    mounted() {
        this.asyncCountry();
        this.getGateways();
    }
};