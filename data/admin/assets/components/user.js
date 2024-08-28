const { isValidNumber } = require('libphonenumber-js');

module.exports = {
	data: function() {
		return {
			table: null,

			user: {
				id: null,
				username: null,
				full_name: null,
				email: null,
				mobile: null,
				password: null,
				sms_type: 'Package',
				company: null,
				address: null
			},

			edit: false,
			block: false,

			remove_id: null,
			remove_user: null,
			block_status: null,
		}
	},

	methods: {
		loadTable() {
			this.table = $('#user_list').DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					url: '/admin/user/list/',
					data: function (d) {
						d.account_type = $('#f-account-type').val();
					}
				},

				order: [ [0, 'desc'] ],

				columns: [
					{ data: 'id', name: 'id' },
					{ data: 'username', name: 'username' },
					{ data: 'account_type', name: 'account_type' },
					{ data: 'mobile', name: 'mobile' },
					{ data: 'email', name: 'email' },
					{ data: 'sms_type', name: 'sms_type' },
					{ data: 'remaining_credit', name: 'remaining_credit', sortable: false, searchable: false },
					{ data: 'unpaid_credit', name: 'unpaid_credit' },
					{ data: 'action', name: 'action', orderable: false, searchable: false }
				]
			});
		},

		changeType() {
			this.table.draw();
		},

		showNewUser() {
			this.cleanData();
			this.edit = false;
			$('#user_modal').modal('show');
		},

		cleanData() {
			this.user.id = null;
			this.user.username = null;
			this.user.full_name = null;
			this.user.email = null;
			this.user.mobile = null;
			this.user.password = null;
			this.user.sms_type = 'Package';
			this.user.company = null;
			this.user.address = null;
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
			var url = this.edit ? 'update' : 'create';

			this.user.mobile = $("#phone").intlTelInput("getNumber");

			axios.post('/admin/user/' + url, this.user).then(({data}) => {
				if (data.status == true) {
					$('#user_modal').modal('hide');
					Notification.success('Success');
					this.table.ajax.reload();
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

		showEditUser(id) {
			this.cleanData();
			this.edit = true;

			axios.get('/admin/user/edit/' + id).then(({data}) => {
				if (data.status == true) {
					var user = data.user;

					this.user.id = user.id;
					this.user.username = user.username;
					this.user.full_name = user.full_name;
					this.user.email = user.email;
					this.user.company = user.company;
					this.user.address = user.address;
					this.user.mobile = user.mobile;
					this.user.sms_type = user.sms_type;
					$("#phone").intlTelInput("setNumber", user.mobile);

					$('#user_modal').modal('show');
				} else {
					Notification.error(data.message);
				}
			}).catch(error => {
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/admin/login';
				} else {
					Notification.error('Error occured while getting data.');
				}
			});
		},

		deleteUser(id, username) {
			this.remove_id = id;
			this.remove_user = username;

			this.block = false;
			$('#delete_modal').modal('show');
		},

		blockUser(id, username, block_status) {
			this.remove_id = id;
			this.remove_user = username;
			this.block_status = block_status;

			this.block = true;
			$('#block_modal').modal('show');
		},

		removeUser() {
			var url = this.block ? 'block' : 'delete';

			axios.post('/admin/user/' + url, {id: this.remove_id}).then(({data}) => {
				if (data.status == true) {
					$('#delete_modal').modal('hide');
					$('#block_modal').modal('hide');
					Notification.success('Success');
					this.table.ajax.reload();
				} else {
					Notification.error(data.message);
				}
			}).catch(error => {
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/admin/login';
				} else {
					Notification.error('Error occured while getting data.');
				}
			});
		}
	},

	mounted() {
		this.loadTable();

		setTimeout(function () {
			$("#phone").intlTelInput({
				defaultCountry: "mm",
				preferredCountries: ["mm"],

				geoIpLookup: function (callback) {
					$.get('https://ipinfo.io', function () {}, "jsonp").always(function (resp) {
						var countryCode = (resp && resp.country) ? resp.country : "";
						callback(countryCode);
					});
				},

				utilsScript: "/js/utils.js"
			});
		}, 500);

		$('#user_list').delegate('.edit_user', 'click', (evt) => {
			const id = evt.currentTarget.getAttribute('data-id');
			this.showEditUser(id);
		});

		$('#user_list').delegate('.delete_user', 'click', (evt) => {
			const id = evt.currentTarget.getAttribute('data-id');
			const username = evt.currentTarget.getAttribute('data-username');
			this.deleteUser(id, username);
		});

		$('#user_list').delegate('.block_user', 'click', (evt) => {
			const id = evt.currentTarget.getAttribute('data-id');
			const username = evt.currentTarget.getAttribute('data-username');
			const block_status = evt.currentTarget.getAttribute('data-block');
			this.blockUser(id, username, block_status);
		});
	},

	created () {
		this.$validator.extend('verify_phone', {
			getMessage: field => `Invalid phone number`,
			validate: value => new Promise((resolve) => {
				var number = $("#phone").intlTelInput("getNumber");
				let validUser = isValidNumber(number);
				setTimeout(() => {
					resolve({
						valid: validUser
					});
				}, 200);
			})
		});
	}
}