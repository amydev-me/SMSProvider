const datePicker  = resolve => require(['vue-bootstrap-datetimepicker'], resolve);
const VuePagination = resolve => require(['../core/VuePagination'], resolve);

module.exports = {
	components: { datePicker, VuePagination },

	data: function () {
		return {
			orders: [],

			invoiceDate: null,
			status: 'all',
			pageSize: 10,
			search: null,

			options: {
				format: 'DD MMM YYYY',
				useCurrent: false,
			},

			order: {
				order_id: null,
				status: null,
				title: null,
				text: null
			},

			loading: false,

			pagination: {
				total: 0,
				per_page: 2,
				from: 1,
				to: 0,
				current_page: 1,
				last_page: 1,
			}
		}
	},

	methods: {
		getPaygOrders() {
			axios.get('/admin/payg-order/orders').then(({data}) => {
				this.pagination = data;
				this.orders = data.data;
			}).catch(error => {
				console.log(error);
			});
		},

		filter() {
			var filter = {
				invoice_date: this.invoiceDate,
				status: this.status,
				page_size: this.pageSize,
				search: this.search,
				page: this.pagination.current_page
			};

			axios.post('/admin/payg-order/filter', filter).then(({data}) => {
				this.pagination = data;
				this.orders = data.data;
			}).catch(error => {
				console.log(error);
			});
		},

		filterInvoice() {
			this.pagination.current_page = 1;
			this.status = 'all';
			this.search = null;
			this.filter();
		},

		filterSearch() {
			this.pagination.current_page = 1;
			this.filter();
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

			axios.post('/admin/payg-order/update', this.order).then(({data}) => {
				if (data.status == true) {
					$('#order_modal').modal('hide');
					Notification.success('Success');

					this.filter();
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

		changeUppercase(string) {
			return string.charAt(0).toUpperCase() + string.slice(1);
		},

		formatPrettyDate(date) {
			if (date != null) {
				return Helper.formatPrettyDate(date);
			} else {
				return '';
			}
		}
	},

	mounted() {
		this.getPaygOrders();
	}
}