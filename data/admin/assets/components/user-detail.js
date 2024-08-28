Vue.component('multiselect', require('vue-multiselect').default);

module.exports = {
	data: function() {
		return {
			user: {
				user_id: null,
				new_password: null,
				confirm_password: null,
			},

			credit: null,
			credit_error: null,

			max: null,
			unpaid_mmk: null,
			unpaid_credit: null,
			unpaid_credit_error: null,
			loading: false,

			rate: null,
			usd_rate: null,
			usd_error: false,

			search: '',
			user_rates: [],

			countries: [],
			selected_country: null,

			country_error: false,
			country_rate_error: false,

			is_edit: false,

			user_rate: {
				id: null,
				user_id: null,
				country_id: null,
				rate: null
			}
		}
	},

	methods: {
		getUrlParam() {
			var uri = window.location.pathname;
			var res = uri.split("/");
			this.user.user_id = res[4];
			this.user_rate.user_id = res[4];

			axios.get('/admin/user/get-usd?id=' + this.user.user_id).then(({data}) => {
				this.rate = data;
			}).catch(error => {
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/admin/login';
				} else {
					Notification.error('Error occured while getting data.');
				}
			});
		},

		addCredit() {
			this.credit_error = false;
			$('#credit_modal').modal('show');
		},

		saveCredit() {
			if (this.credit == '' || this.credit == null) {
				this.credit_error = true;
				return false;
			} else {
				axios.post('/admin/user/add-credit', {user_id: this.user.user_id, credit: this.credit}).then(({data}) => {
					if (data.status == true) {
						window.location.href = '/admin/user/view/' + this.user.user_id;
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
		},

		setRate() {
			this.usd_error = false;
			$('#usd_modal').modal('show');
		},

		saveRate() {
			if (this.usd_rate == '' || this.usd_rate == null) {
				this.usd_error = true;
				return false;
			} else {
				axios.post('/admin/user/update-usd', {id: this.user.user_id, usd_rate: this.usd_rate}).then(({data}) => {
					if (data.status == true) {
						this.rate = this.usd_rate;
						this.usd_error = false;
						$('#usd_modal').modal('hide');
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
						window.location.href = '/admin/login';
					} else {
						Notification.error('Error occured while creating data.');
					}
				});
			}
		},

		showPasswordModal() {
			this.cleanData();
			$('#user_password_modal').modal('show');
		},

		cleanData() {
			this.user.new_password = null;
			this.user.confirm_password = null;
			this.$validator.reset();
		},

		validateData() {
			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					this.submit();
				}
			}).catch(error => {
				console.log(error);
			});
		},

		submit() {
			axios.post('/admin/user/password/change', this.user).then(({data}) => {
				if (data.status == true) {
					$('#user_password_modal').modal('hide');
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
					window.location.href = '/admin/login';
				} else {
					Notification.error('Error occured while creating data.');
				}
			});
		},

		getUserRates() {
			axios.get('/admin/user/rate/get?id=' + this.user.user_id).then(({data}) => {
				this.user_rates = data;
			});
		},

		getCountries() {
			axios.get('/admin/country/async').then(({data}) => {
				this.countries = data;
			});
		},

		showCountryModal() {
			this.cleanUserRate();
			$('#country_modal').modal('show');
		},

		saveCountryRate() {
			if (this.selected_country != null) {
				this.user_rate.country_id = this.selected_country.id;
			}

			if (this.user_rate.country_id == '' || this.user_rate.country_id == null) {
				this.country_error = true;
			} else {
				this.country_error = false;
			}

			if (this.user_rate.rate == '' || this.user_rate.rate == null) {
				this.country_rate_error = true;
			} else {
				this.country_rate_error = false;
			}

			if (this.country_error || this.country_rate_error) {
				return false;
			} else {
				let url = this.is_edit ? 'update' : 'add';

				axios.post('/admin/user/rate/' + url, this.user_rate).then(({data}) => {
					if (data.status) {
						this.getUserRates();
						$('#country_modal').modal('hide');
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
					console.log(error);
					if (error.response.status == 401 || error.response.status == 419) {
						window.location.href = '/admin/login';
					} else {
						Notification.error('Error occured while creating data.');
					}
				});
			}
		},

		showEditModal(rate) {
			this.cleanUserRate();

			this.is_edit = true;
			this.user_rate.id = rate.id;
			this.user_rate.country_id = rate.country_id;
			this.user_rate.rate = rate.rate;
			$('#country_modal').modal('show');
		},

		showDeleteModal(id) {
			this.cleanUserRate();
			this.user_rate.id = id;
			$('#deleteModal').modal('show');
		},

		performDelete() {
			axios.post('/admin/user/rate/delete/' + this.user_rate.id).then(({data}) => {
				if (data.success) {
					this.getUserRates();
					$('#deleteModal').modal('hide');
					Notification.success('Success');
				} else {
					Notification.warning('Oops! Something went wrong.');
				}
			}).catch(error => {
				Notification.error('Error occurred while deleting data.');
			});
		},

		cleanUserRate() {
			this.user_rate.id = null,
			this.user_rate.country_id = null,
			this.user_rate.rate = null,

			this.selected_country = null,
			this.country_error = false;
			this.country_rate_error = false;
			this.is_edit = false;
		},

		showInvoice() {
			this.credit_error = false;

			axios.get('/admin/user/unpaid/get?user_id=' + this.user.user_id).then(({data}) => {
				this.unpaid_mmk = (data * 6).toLocaleString();
				this.unpaid_credit = data;
				this.max = data.toLocaleString();
			});

			$('#invoice_modal').modal('show');
		},

		calculateMMK() {
			this.unpaid_mmk = (this.unpaid_credit * 6).toLocaleString();
		},

		sendInvoice() {
			if (this.unpaid_credit == '' || this.unpaid_credit == null) {
				this.unpaid_credit_error = true;
				return false;
			} else {
				this.loading = true;
				
				axios.post('/admin/user/unpaid/send-invoice', {user_id: this.user.user_id, unpaid_credit: this.unpaid_credit}).then(({data}) => {
					if (data.status == true) {
						window.location.href = '/admin/user/view/' + this.user.user_id;
					} else {
						var error_messages = '';

						for (var key in data.message) {
							if (data.message.hasOwnProperty(key)) {
								error_messages += data.message[key] + '<br/>';
							}
						}

						Notification.error(error_messages);
					}

					this.loading = false;
				}).catch(error => {
					if (error.response.status == 401 || error.response.status == 419) {
						window.location.href = '/admin/login';
					} else {
						Notification.error('Error occured while creating data.');
					}

					this.loading = false;
				});
			}
		}
	},

	mounted() {
		this.getUrlParam();
		this.getUserRates();
		this.getCountries();
	},
}