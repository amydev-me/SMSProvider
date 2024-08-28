Vue.component('multiselect', require('vue-multiselect').default);

module.exports = {
	props: ['smsType'],

	data: function () {
		return {
			new_order: {
				user_id: null,
				package_id: null,
				usd_rate: null,
				status: null,
				sms_type: null,
			},

			packages: [],
			selected_package: null,
			packageLoading: false,

			table: null,

			order: {
				order_id: null,
				status: null,
				title: null,
				text: null,
				sms_type: null,
			},

			loading: false,
		}
	},

	methods: {
		getUrlParam() {
			var uri = window.location.pathname;
			var res = uri.split("/");
			this.new_order.user_id = res[4];
		},

		showNewOrder() {
			this.selected_package = null;
			this.new_order.status = 'confirm';
			this.$validator.reset();

			$('#new_order').modal('show');
		},

		getPackages(query) {
			this.packageLoading = true;

			return axios.post('/admin/order/packages', {name: query}).then(({data}) => {
				this.packages = data;
				this.packageLoading = false;
			});
		},

		validateData() {
			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					if (this.smsType == 'Package') {
						this.new_order.package_id = this.selected_package.id;
					}

					this.new_order.sms_type = this.smsType;

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

		loadTable() {
			var user_id = this.new_order.user_id;

			var key = 0;
			var column_data = [];

			column_data[key++] = { data: 'id', name: 'id' };
			column_data[key++] = { data: 'invoice_no', name: 'invoice_no' };

			if (this.smsType == 'Package') {
				column_data[key++] = { data: 'package.packageName', name: 'package.packageName' };
				column_data[key++] = { data: 'cost', name: 'cost' };
				column_data[key++] = { data: 'credit', name: 'credit' };
				column_data[key++] = { data: 'extra_credit', name: 'extra_credit' };
				column_data[key++] = { data: 'total_credit', name: 'total_credit' };
			} else if (this.smsType == 'PAYG') {
				column_data[key++] = { data: 'cost', name: 'cost' };
				column_data[key++] = { data: 'total_credit', name: 'total_credit' };
			}

			column_data[key++] = { data: 'payment_date', name: 'payment_date' };

			if (this.smsType == 'Package') {
				column_data[key++] = { data: 'order_date', name: 'order_date' };
			} else if (this.smsType == 'PAYG') {
				column_data[key++] = { data: 'invoice_date', name: 'invoice_date' };
			}

			column_data[key++] = { data: 'status', name: 'status' };
			column_data[key++] = { data: 'action', name: 'action' };

			this.table = $('#order_list').DataTable({
				processing: true,
				serverSide: true,

				ajax: {
					url: '/admin/order/list/',
					data: function (d) {
						d.user_id = user_id;
					}
				},

				order: [ [0, 'desc'] ],
				columns: column_data
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

			this.order.sms_type = this.smsType;

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
	},

	mounted() {
		this.getUrlParam();

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