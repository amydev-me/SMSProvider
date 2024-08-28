const datePicker  = resolve => require(['vue-bootstrap-datetimepicker'], resolve);

Vue.component('multiselect', require('vue-multiselect').default);

module.exports = {
	components: { datePicker },

	data: function() {
		return {
			table: null,
			packages: [],

			orderDate: null,
			packageId: null,
			status: '',

			order: {
				order_id: null,
				status: null,
				title: null,
				text: null
			},

			loading: false,

			new_order: {
				user_id: null,
				package_id: null,
				status: 'confirm',
				sms_type: null,
			},

			selected_user: null,
			selected_package: null,

			asyncUsers: [],
			asyncPackages: [],

			userLoading: false,
			packageLoading: false,

			options: {
				format: 'DD MMM YYYY',
				useCurrent: false,
			},
		}
	},

	methods: {
		selectablePackages() {
			axios.get('/admin/package/packages').then(({data}) => {
				this.packages = data;
			});
		},

		getUrlParameter() {
			var uri = window.location.pathname;
			var res = uri.split("/");
			this.packageId = res[4] ? res[4] : '';
		},

		loadTable() {
			var that = this;

			this.table = $('#order_list').DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					url: '/admin/order/list/',
					data: function (d) {
						d.order_date = that.orderDate;
						d.package_id = that.packageId;
						d.status = that.status;
					}
				},

				order: [ [0, 'desc'] ],

				columns: [
					{ data: 'id', name: 'id' },
					{ data: 'invoice_no', name: 'invoice_no' },
					{ data: 'user.username', name: 'user.username' },
					{ data: 'package.packageName', name: 'package.packageName' },
					{ data: 'cost', name: 'cost' },
					{ data: 'credit', name: 'credit' },
					{ data: 'extra_credit', name: 'extra_credit' },
					{ data: 'total_credit', name: 'total_credit' },
					{ data: 'payment_date', name: 'payment_date' },
					{ data: 'order_date', name: 'order_date' },
					{ data: 'status', name: 'status' },
					{ data: 'action', name: 'action', orderable: false, searchable: false }
				]
			});
		},

		showConfirmModal(id) {
			this.order.order_id = id;
			this.order.title = 'Confirm Order';
			this.order.text = 'Do you want to confirm this order?';
			this.order.status = 'confirm';

			$('#order_modal').modal('show');
		},

		showPaymentModal(id) {
			this.order.order_id = id;
			this.order.title = 'Receive Payment';
			this.order.text = 'Do you want to complete this payment?';
			this.order.status = 'paid';
			
			$('#order_modal').modal('show');
		},

		showCancelModal(id) {
			this.order.order_id = id;
			this.order.title = 'Cancel Order';
			this.order.text = 'Are you sure you want to cancel this order?';
			this.order.status = 'cancel';

			$('#order_modal').modal('show');
		},

		submit() {
			this.loading = true;

			axios.post('/admin/order/update', this.order).then(({data}) => {
				if (data.status == true) {
					$('#order_modal').modal('hide');
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

				this.loading = false;
			}).catch(error => {
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/admin/login';
				} else {
					Notification.error('Error occured while deleting data.');
				}

				this.loading = false;
			});
		},

		changeStatus() {
			this.table.draw();
		},

		changeDate() {
			this.table.draw();
		},

		showNewOrder() {
			this.selected_user = null;
			this.selected_package = null;
			this.new_order.status = 'confirm';
			this.$validator.reset();

			$('#new_order').modal('show');
		},

		getUsers (query) {
			this.userLoading = true;

			return axios.post('/admin/order/users', {name: query}).then(({data}) => {
				this.asyncUsers = data;
				this.userLoading = false;
			});
		},

		getPackages(query) {
			this.packageLoading = true;

			return axios.post('/admin/order/packages', {name: query}).then(({data}) => {
				this.asyncPackages = data;
				this.packageLoading = false;
			});
		},

		validateData() {
			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					this.new_order.user_id = this.selected_user.id;
					this.new_order.sms_type = this.selected_user.sms_type;
					this.new_order.package_id = this.selected_package.id;

					this.saveOrder();
				}
			}).catch(error => {
				console.log(error);
			});
		},

		saveOrder() {
			this.loading = true;

			axios.post('/admin/order/create', this.new_order).then(({data}) => {
				if (data.status == true) {
					$('#new_order').modal('hide');
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

				this.loading = false;
			}).catch(error => {
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/admin/login';
				} else {
					Notification.error('Error occured while creating data.');
				}

				this.loading = false;
			});
		},
	},
	
	mounted() {
		this.selectablePackages();
		this.getUrlParameter();
		this.loadTable();

		$('#order_list').delegate('.confirm_order', 'click', (evt) => {
			const id = evt.currentTarget.getAttribute('data-id');
			this.showConfirmModal(id);
		});

		$('#order_list').delegate('.change_payment', 'click', (evt) => {
			const id = evt.currentTarget.getAttribute('data-id');
			this.showPaymentModal(id);
		});

		$('#order_list').delegate('.cancel_order', 'click', (evt) => {
			const id = evt.currentTarget.getAttribute('data-id');
			this.showCancelModal(id);
		});
	}
}