const VuePagination = resolve => require(['../core/VuePagination'], resolve);
const datePicker  = resolve => require(['vue-bootstrap-datetimepicker'], resolve);

Vue.component('multiselect', require('vue-multiselect').default);

module.exports = {
	components: { VuePagination, datePicker },

	data: function () {
		return {
			senders: [],

			sender: {
				id: null,
				user_id: null,
				sender_name: null,
				operator_id: null,
				register_at: null,
			},

			search: '',
			is_edit: false,

			pagination: {
				total: 0,
				per_page: 2,
				from: 1,
				to: 0,
				current_page: 1,
				last_page: 1,
			},

			users: [],
			userLoading: false,
			selected_user: null,

			operators: [],
			selected_operator: null,

			options: {
				format: 'DD MMM YYYY',
				useCurrent: false,
			},
		}
	},

	methods: {
		getSenders() {
			axios.get('/admin/user-sender/list?page=' + this.pagination.current_page).then(({data}) => {
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
				axios.get('/admin/user-sender/search/' + this.search + '?page=' + this.pagination.current_page).then(({data}) => {
					this.pagination = data;
					this.senders = data.data;
				}).catch(error => {
					console.log(error);
				});
			}
		},

		showAddModal() {
			this.clearData();
			this.is_edit = false;
			$('#senderModal').modal('show');
			$('#sender_name').focus();
		},

		showEditModal(data) {
			this.is_edit = true;
			this.sender = Object.assign({}, data);

			this.selected_user = {
				id: this.sender.user_id,
				username: this.sender.user.username
			};

			this.selected_operator = {
				id: this.sender.operator_id,
				name: this.sender.operator_id != null ? this.sender.operator.name : 'Foreign'
			};

			$('#senderModal').modal('show');
		},

		getUsers (query) {
			this.userLoading = true;

			return axios.post('/admin/order/users', {name: query}).then(({data}) => {
				this.users = data;
				this.userLoading = false;
			});
		},

		getOperators() {
			axios.get('/admin/operator/async-myanmar').then(({data}) => {
				var foreign = {
					'id': null,
					'name': 'Foreign'
				};

				data.push(foreign);
				this.operators = data;
			});
		},

		validateData() {
			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					this.sender.user_id = this.selected_user.id;
					this.sender.operator_id = this.selected_operator.id;

					let _meth = !this.is_edit ? 'create' : 'update';

					axios.post('/admin/user-sender/' + _meth, this.sender).then(({data}) => {
						if (data.success) {
							this.getSenders();
							$('#senderModal').modal('hide');
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
						Notification.error('Error occurred while creating/updating data.');
					});
				}
			}).catch(error => {
				console.log(error);
			});
		},

		clearData() {
			this.is_edit = false;

			this.sender.id = null;
			this.sender.user_id = null;
			this.sender.sender_name = null;
			this.sender.operator_id = null;
			this.sender.register_at = null;

			this.selected_user = null;
			this.selected_operator = null;
			this.$validator.reset();
		},

		showDeleteModal(id) {
			this.clearData();
			this.sender.id = id;
			$('#deleteModal').modal('show');
		},

		performDelete() {
			axios.post('/admin/user-sender/delete/' + this.sender.id, this.sender).then(({data}) => {
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
		}
	},

	mounted() {
		this.getSenders();
		this.getOperators();
	},

	computed: {
		usernameTab() {
			return {
				tabIndex: 0,
			}
		},

		operatorTab() {
			return {
				tabIndex: 0,
			}
		}
	},
}