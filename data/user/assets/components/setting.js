module.exports = {
	data: function () {
		return {
			setting: {
				newsletter_alert: null,
				credit_email_alert: null,
				credit_sms_alert: null,
				minimum_credit: null
			},

			newsletter_alert: null,
			credit_email_alert: null,
			credit_sms_alert: null,
		}
	},

	methods: {
		getUserSetting() {
			axios.get('/user/get-setting').then(({data}) => {
				if (data.newsletter_alert == 1) {
					this.newsletter_alert = true;
				} else {
					this.newsletter_alert = false;
				}

				if (data.credit_email_alert == 1) {
					this.credit_email_alert = true;
				} else {
					this.credit_email_alert = false;
				}

				if (data.credit_sms_alert == 1) {
					this.credit_sms_alert = true;
				} else {
					this.credit_sms_alert = false;
				}

				this.setting.newsletter_alert = data.newsletter_alert;
				this.setting.credit_email_alert = data.credit_email_alert;
				this.setting.credit_sms_alert = data.credit_sms_alert;
				this.setting.minimum_credit = data.minimum_credit;
			});
		},

		toggleNewsletter(e) {
			this.newsletter_alert = e.value;

			if (this.newsletter_alert) {
				this.setting.newsletter_alert = 1;
			} else {
				this.setting.newsletter_alert = 0;
			}
		},

		toggleEmailAlert(e) {
			this.credit_email_alert = e.value;

			if (this.credit_email_alert) {
				this.setting.credit_email_alert = 1;
			} else {
				this.setting.credit_email_alert = 0;
			}
		},

		toggleSmsAlert(e) {
			this.credit_sms_alert = e.value;

			if (this.credit_sms_alert) {
				this.setting.credit_sms_alert = 1;
			} else {
				this.setting.credit_sms_alert = 0;
			}
		},

		saveSetting() {
			axios.post('/user/setting/change', this.setting).then(({data}) => {
				if (data.status == true) {
					Notification.success('Success');
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
					window.location.href = '/login';
				} else {
					Notification.error('Error occured while creating data.');
				}
			});
		}
	},

	mounted() {
		this.getUserSetting();
	}
}