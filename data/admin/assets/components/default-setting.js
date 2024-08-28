module.exports = {
	data: function () {
		return {
			default_setting:{
				sender: null,
				facebook_url: null,
				twitter_url: null,
				linkedin_url: null,
				phones: null,
				email: null
			}
		}
	},

	methods: {
		getData:function() {
			axios.get('setting/default').then(({data})=>{
				if (data.default_setting) {
					this.default_setting = data.default_setting;
				}

			});
		},

		validateDefaultSetting:function() {
			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					axios.post('setting/default', this.default_setting).then(({data}) => {
						Notification.success('Success');
					});
				}
			});
		}
	},

	mounted() {
		this.getData();
	}
};