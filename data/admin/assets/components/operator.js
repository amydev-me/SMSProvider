const VuePagination = resolve => require(['../core/VuePagination'], resolve);

module.exports = {
	components: { VuePagination },

	props: [ 'countryId' ],

	data: function () {
		return {
			operators: [],

			operator: {
				id: null,
				country_id: this.countryId,
				name: null
			},

			search: '',
			is_edit: false,

			numbers: [],

			operator_detail: {
				country_id: this.countryId,
				operator_id: null,
				starting_number: null
			}
		}
	},

	methods: {
		getOperators() {
			axios.get('/admin/operator/list/' + this.countryId).then(({data}) => {
				this.operators = data;
			}).catch(error => {
				console.log(error);
			});
		},

		searchClick() {
			if (this.search == "") {
				this.getOperators();
			} else {
				axios.get('/admin/operator/search/' + this.search).then(({data}) => {
					this.operators = data;
				}).catch(error => {
					console.log(error);
				});
			}
		},

		showAddModal() {
			this.clearData();
			this.is_edit = false;
			$('#operatorModal').modal('show');
		},

		showEditModal(operator) {
			this.is_edit = true;
			this.operator = Object.assign({}, operator);
			$('#operatorModal').modal('show');
		},

		validateData() {
			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					let _meth = !this.is_edit ? 'create' : 'update';

					axios.post('/admin/operator/' + _meth, this.operator).then(({data}) => {
						if (data.success) {
							this.getOperators();
							$('#operatorModal').modal('hide');
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
			this.operator.id = null;
			this.operator.name = null;
			this.$validator.reset();
		},

		showStatusModal(operator) {
			this.clearData();
			this.operator = Object.assign({}, operator);
			$('#statusModal').modal('show');
		},

		changeStatus() {
			axios.post('/admin/operator/status/' + this.operator.id, this.operator).then(({data}) => {
				if (data.success) {
					this.searchClick();
					$('#statusModal').modal('hide');
					Notification.success('Success');
				} else {
					Notification.warning('Oops! Something Went Wrong!');
				}
			}).catch(error => {
				Notification.error('Error occurred while changing data.');
			});
		},

		showDeleteModal(id) {
			this.clearData();
			this.operator.id = id;
			$('#deleteModal').modal('show');
		},

		performDelete() {
			axios.post('/admin/operator/delete/' + this.operator.id, this.operator).then(({data}) => {
				if (data.success) {
					this.getOperators();
					$('#deleteModal').modal('hide');
					Notification.success('Success');
				} else {
					Notification.warning('Oops! Something Went Wrong!');
				}
			}).catch(error => {
				Notification.error('Error occurred while deleting data.');
			});
		},

		showNumbers(id) {
			axios.get('/admin/operator/numbers/' + id).then(({data}) => {
				this.numbers = data;
				$('#numbersModal').modal('show');
			}).catch(error => {
				Notification.error('Error occurred while fetching data.');
			});
		},

		showAddNumber(operator) {
			this.operator_detail.operator_id = operator.id;
			this.operator_detail.starting_number = null;
			$('#addNumberModal').modal('show');
		},

		addNumber() {
			axios.post('/admin/operator/numbers/create', this.operator_detail).then(({data}) => {
				if (data.success) {
					this.getOperators();
					$('#addNumberModal').modal('hide');
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
				Notification.error('Error occurred while creating data.');
			});
		},

		removeNumber(number) {
			axios.post('/admin/operator/numbers/delete/' + number.id).then(({data}) => {
				if (data.success) {
					this.getOperators();
					$('#numbersModal').modal('hide');
					Notification.success('Success');
				} else {
					Notification.warning('Oops! Something Went Wrong!');
				}
			}).catch(error => {
				Notification.error('Error occurred while deleting data.');
			});
		}
	},

	mounted() {
		this.getOperators();

		var that = this;

		$('#operatorModal').on('hidden.bs.modal', function (e) {
			that.clearData();
		})
	}
}