const VuePagination = resolve => require(['../core/VuePagination'], resolve);
const datePicker  = resolve => require(['vue-bootstrap-datetimepicker'], resolve);

Vue.component('multiselect', require('vue-multiselect').default);

module.exports = {
	components: { VuePagination, datePicker },

	data: function () {
		return {
			sender_name: null,
			sender_details: [],

			sender_detail: {
				id: null,
				sender_id: null,
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

			operators: [],
			selected_operator: null,

			options: {
				format: 'DD MMM YYYY',
				useCurrent: false,
			},
		}
	},

	methods: {
		getSenderDetails() {
			axios.get('/admin/sender/sender-detail/list?sender_id=' + this.sender_detail.sender_id + 'page=' + this.pagination.current_page).then(({data}) => {
				this.pagination = data;
				this.sender_details = data.data;
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
				this.getSenderDetails();
			} else {
				axios.get('/admin/sender/sender-detail/search/' + this.search + '?sender_id=' + this.sender_detail.sender_id + 'page=' + this.pagination.current_page).then(({data}) => {
					this.pagination = data;
					this.sender_details = data.data;
				}).catch(error => {
					console.log(error);
				});
			}
		},

		showAddModal() {
			this.clearData();
			this.is_edit = false;
			$('#senderDetailModal').modal('show');
		},

		showEditModal(data) {
			this.is_edit = true;
			this.sender_detail = Object.assign({}, data);

			this.selected_operator = {
				id: this.sender_detail.operator_id,
				name: this.sender_detail.operator_id != null ? this.sender_detail.operator.name : 'Foreign'
			};

			$('#senderDetailModal').modal('show');
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
					this.sender_detail.operator_id = this.selected_operator.id;

					let _meth = !this.is_edit ? 'create' : 'update';

					axios.post('/admin/sender/sender-detail/' + _meth, this.sender_detail).then(({data}) => {
						if (data.success) {
							this.getSenderDetails();
							$('#senderDetailModal').modal('hide');
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

			this.sender_detail.id = null;
			this.sender_detail.operator_id = null;
			this.sender_detail.register_at = null;

			this.selected_operator = null;
			this.$validator.reset();
		},

		showDeleteModal(id) {
			this.clearData();
			this.sender_detail.id = id;
			$('#deleteModal').modal('show');
		},

		performDelete() {
			axios.post('/admin/sender/sender-detail/delete', {id: this.sender_detail.id}).then(({data}) => {
				if (data.success) {
					this.pagination.current_page = 1;
					this.getSenderDetails();

					$('#deleteModal').modal('hide');
					Notification.success('Success');
				} else {
					Notification.warning(data.message);
				}
			}).catch(error => {
				Notification.error('Error occurred while deleting data.');
			});
		},

		getUrlParam() {
			var uri = window.location.pathname;
			var res = uri.split("/");
			this.sender_detail.sender_id = res[3];

			axios.get('/admin/sender/name/' + this.sender_detail.sender_id).then(({data}) => {
				this.sender_name = data;
			});
		}
	},

	mounted() {
		this.getUrlParam();
		this.getSenderDetails();
		this.getOperators();
	},

	computed: {
		operatorTab() {
			return {
				tabIndex: 0,
			}
		}
	},
}