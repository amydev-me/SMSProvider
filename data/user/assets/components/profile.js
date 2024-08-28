module.exports = {
	data: function () {
		return {
			isedit: false,

			user: {
				email: null,
				mobile: null,
				fullName: null,
				company: null,
				address: null
			},

			oldPassword: null,
			newPassword: null,
			confirmPassword: null,

			email: null,
			loading: false,
		}
	},

	methods: {
		getUserProfile() {
			axios.get('/user/get-profile').then(({data}) => {
				this.user = data;
			});
		},

		clickedit() {
			this.isedit = true;
		},

		validateData() {
			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					this.$refs.form.submit()
				}
			}).catch(error => {
				Notification.warning('Opps!Something went wrong.');
			});
		},

		showPasswordModal() {
			this.cleanData();
			$('#userPasswordModal').modal('show');
		},

		cleanData() {
			$('#old_password_error').val('');
			$('#new_password_error').val('');
			$('#confirm_password_error').val('');

			this.oldPassword = null;
			this.newPassword = null;
			this.confirmPassword = null;
		},

		changePassword() {
			if ( this.validatePasswords() ) {
				this.saveNewPassword();
			}
		},

		validatePasswords() {
			var error = false;

			if (this.oldPassword == null || this.oldPassword == '') {
				$('#old_password_error').text('Old Password is required');
				$('#old_password_error').show();
				error = true;
			} else {
				$('#old_password_error').hide();
			}

			if (this.newPassword == null || this.newPassword == '') {
				$('#new_password_error').text('New Password is required');
				$('#new_password_error').show();
				error = true;
			} else {
				if (this.oldPassword == this.newPassword) {
					$('#new_password_error').text('Old Password and New Password must not be same');
					$('#new_password_error').show();
					error = true;
				} else {
					$('#new_password_error').hide();
				}
			}

			if (this.confirmPassword == null || this.confirmPassword == '') {
				$('#confirm_password_error').text('Confirm Password is required');
				$('#confirm_password_error').show();
				error = true;
			} else {
				if (this.newPassword != this.confirmPassword) {
					$('#confirm_password_error').text('New Password and Confirm Password must be same');
					$('#confirm_password_error').show();
					error = true;
				} else {
					$('#confirm_password_error').hide();
				}
			}

			if (error) {
				return false;
			} else {
				return true;
			}
		},

		saveNewPassword() {
			var passwords = {
				old_password: this.oldPassword,
				new_password: this.newPassword,
				confirm_password: this.confirmPassword
			};

			axios.post('/user/password/change', passwords).then(({data}) => {
				if (data.status == true) {
					$('#userPasswordModal').modal('hide');
					Notification.success('Success');
				} else {
					this.showErrors(data.message);
				}
			}).catch(error => {
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/login';
				} else {
					Notification.error('Error occured while creating data.');
				}
			});
		},

		showEmailChange() {
			this.email = null;
			this.loading = false;
			$('#userEmailModal').modal('show');
		},

		changeEmail() {
			if (this.email == null || this.email == '') {
				$('#email_error').text('Email is required.');
				$('#email_error').show();
				return false;
			}

			this.loading = true;

			axios.post('/user/email/change', {email: this.email}).then(({data}) => {
				if (data.status == true) {
					$('#userEmailModal').modal('hide');
					Notification.success('Please click the verification link sent to your new email.');
				} else {
					this.showErrors(data.message);
					this.loading = false;
				}
			}).catch(error => {
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/login';
				} else {
					Notification.error('Error occured while requesting data.');
				}
				this.loading = false;
			});
		},

		showErrors(errors) {
			var error_messages = '';

			for (var key in errors) {
				if (errors.hasOwnProperty(key)) {
					error_messages += errors[key] + '<br/>';
				}
			}

			Notification.error(error_messages);
		}
	},

	mounted() {
		this.getUserProfile();
	}
}