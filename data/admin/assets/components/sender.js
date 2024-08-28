const VuePagination = resolve => require(['../core/VuePagination'], resolve);

Vue.component('multiselect', require('vue-multiselect').default);

module.exports = {
	components: { VuePagination },

	data: function () {
		return {
			senders: [],
			sender_users: [],

			sender: {
				id: null,
				sender_name: null
			},

			search: '',
			edit: false,

			pagination: {
				total: 0,
				per_page: 2,
				from: 1,
				to: 0,
				current_page: 1,
				last_page: 1,
			},

			users: [],
			user_error: false,
			userLoading: false,
			selected_user: null,

			sender_user: {
				sender_id: null,
				user_id: null
			}
		}
	},

	methods: {
		getSenders() {
			axios.get('/admin/sender/list?page=' + this.pagination.current_page).then(({data}) => {
				this.pagination = data;
				this.senders = data.data;
			}).catch(error => {
				console.log(error);
			});
		},

		searchClick() {
			this.pagination.current_page = 1;
			this.filter();
		},

		filter() {
			if (this.search == "") {
				this.getSenders();
			} else {
				axios.get('/admin/sender/search/' + this.search + '?page=' + this.pagination.current_page).then(({data}) => {
					this.pagination = data;
					this.senders = data.data;
				}).catch(error => {
					console.log(error);
				});
			}
		},

		showAddModal() {
			this.clearData();
			this.edit = false;
			$('#senderModal').modal('show');
			$('#sender_name').focus();
		},

		showEditModal(data) {
			this.edit = true;
			this.sender = Object.assign({}, data);
			$('#senderModal').modal('show');
		},

		validateData() {
			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					let _meth = !this.edit ? 'create' : 'update';

					axios.post('/admin/sender/' + _meth, this.sender).then(({data}) => {
						if (data.success) {
							this.getSenders();
							$('#senderModal').modal('hide');
							Notification.success('Success');
						} else {
							this.showErrorMessages(data.message);
						}
					}).catch(error => {
						Notification.error('Error occurred while creating/updating data.');
					});
				}
			}).catch(error => {
				console.log(error);
			});
		},

		clearData() {
			this.edit = false;
			this.sender.id = null;
			this.sender.sender_name = null;
			this.$validator.reset();
		},

		showDeleteModal(id) {
			this.clearData();
			this.sender.id = id;
			$('#deleteModal').modal('show');
		},

		performDelete() {
			axios.post('/admin/sender/delete', {id: this.sender.id}).then(({data}) => {
				if (data.success) {
					this.pagination.current_page = 1;
					this.getSenders();

					$('#deleteModal').modal('hide');
					Notification.success('Success');
				} else {
					Notification.warning(data.message);
				}
			}).catch(error => {
				Notification.error('Error occurred while deleting data.');
			});
		},

		showSenderUsers(sender_id) {
			axios.get('/admin/sender/users/' + sender_id).then(({data}) => {
				this.sender_users = data;
				$('#usersModal').modal('show');
			}).catch(error => {
				Notification.error('Error occurred while fetching data.');
			});
		},

		showAddUser(sender) {
			this.sender_user.sender_id = sender.id;
			this.user_error = false;
			this.selected_user = null;
			this.sender_user.user_id = null;
			$('#addUserModal').modal('show');
		},

		getUsers(query) {
			this.userLoading = true;

			return axios.post('/admin/order/users', {name: query}).then(({data}) => {
				this.users = data;
				this.userLoading = false;
			});
		},

		addUser() {
			if (this.selected_user != null) {
				this.user_error = false;
				this.sender_user.user_id = this.selected_user.id;

				axios.post('/admin/sender/users/create', this.sender_user).then(({data}) => {
					if (data.success) {
						this.getSenders();
						$('#addUserModal').modal('hide');
						Notification.success('Success');
					} else {
						this.showErrorMessages(data.message);
					}
				}).catch(error => {
					Notification.error('Error occurred while creating data.');
				});
			} else {
				this.user_error = true;
			}
		},

		removeUser(sender_user) {
			axios.post('/admin/sender/users/delete', {id: sender_user.id}).then(({data}) => {
				if (data.success) {
					this.getSenders();
					$('#usersModal').modal('hide');
					Notification.success('Success');
				} else {
					Notification.warning('Oops! Something Went Wrong!');
				}
			}).catch(error => {
				Notification.error('Error occurred while deleting data.');
			});
		},

		showErrorMessages(message) {
			var error_messages = '';

			for (var key in message) {
				if (message.hasOwnProperty(key)) {
					error_messages += message[key] + '<br/>';
				}
			}

			Notification.error(error_messages);
		}
	},

	mounted() {
		this.getSenders();
	},

	computed: {
		usernameTab() {
			return {
				tabIndex: 0,
			}
		}
	}
}