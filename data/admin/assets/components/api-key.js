const DeleteModal = resolve => require(['../core/DeleteModal'], resolve);

module.exports = {
	components: { DeleteModal },

	data: function() {
		return {
			admin_token: {
				api_key: null,
				api_secret: null,
				app_name: null,
				user_id: null,
			},

			removeUrl: '/admin/api/delete/',
			tokens: [],
			token_id: null,
		}
	},

	methods: {
		getTokenList() {
			axios.get('/admin/api/tokens').then(({data}) => {
				this.tokens = data;
			});
		},

		showAddModal() {
			this.cleanData();
			$('#addapi').modal('show');
		},

		cleanData() {
			this.admin_token.api_key = null;
			this.admin_token.api_secret = null;
			this.admin_token.app_name = null;
			this.admin_token.user_id = null;
			this.$validator.reset();
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
			axios.post('/admin/api/create', this.admin_token).then(({data}) => {
				if (data.status == true) {
					this.getTokenList();
					$('#addapi').modal('hide');
					Notification.success('Success');
				} else {
					Notification.error('Error occurs while creating data.');
				}
			}).catch(error => {
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/admin/login';
				} else {
					Notification.error('Error occured while deleting data.');
				}
			});
		},

		showDeleteModal (id) {
			this.token_id = id;
			$('#deleteModal').modal('show');
		},

		successDelete() {
			this.getTokenList();
			this.token_id = null;
			$('#deleteModal').modal('hide');
		},
	},

	mounted() {
		this.getTokenList();
	}
}