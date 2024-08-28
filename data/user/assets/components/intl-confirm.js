module.exports = {
	data: function () {
		return {
			credit: 10000,
			total: '10,000',
		}
	},

	methods: {
		formatPrice() {
			this.total = (this.credit).replace(/(\d)(?=(\d{3})+(?:\.\d+)?$)/g, "$1,");
		},

		showConfirm() {
			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					$('#confirm_modal').modal('show');
				}
			}).catch(error => {
				Notification.warning('Opps! Something went wrong.');
			});
		},

		submit() {
			axios.post('/buy-intl/confirm', {amount: this.credit}).then(({data}) => {
				if (data.status == true) {
					window.location.href = '/order-success';
				} else {
					var error_messages = '';

					for (var key in data.message) {
						if (data.message.hasOwnProperty(key)) {
							error_messages += data.message[key] + '<br/>';
						}
					}

					Notification.error(error_messages);
				}
			}).catch(error => {
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/admin/login';
				} else {
					Notification.error('Error occured while creating data.');
				}
			});
		}
	}
}