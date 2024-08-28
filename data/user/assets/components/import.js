module.exports= {
    data: function () {

        return {
            groups: [],
            selected_groups: [],
            collect_groups:[]
        }

    },

    methods: {
        asyncGroup() {
            axios.get('/list/async-group').then(({data}) => {
                this.groups = data;
            });
        },
    },
    watch: {
        'selected_groups': function (val) {
            this.collect_groups = [];

            if (val.length > 0) {
                var that = this;
                val.map(function (e) {
                    that.collect_groups.push(e.id);
                })
            }
        }
    },
    mounted() {
        this.asyncGroup();
    }
}