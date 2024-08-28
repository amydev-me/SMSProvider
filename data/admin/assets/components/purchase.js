const datePicker  = resolve => require(['vue-bootstrap-datetimepicker'], resolve);

module.exports = {
	components: {datePicker},

	data: function() {
		return {
			table: null,

			purchase: {
				'id': null,
				'amount': null,
				'purchase_date': null,
				'mpt_price': null,
				'telenor_price': null,
				'ooredoo_price': null,
				'mytel_price': null,
				'mec_price': null,
			},

			edit: false,

			options: {
				format: 'DD MMM YYYY',
				useCurrent: false,
			},

			remove_id: null,

			f_purchase_date: null,
		}
	},

	methods: {
		loadTable() {
			this.table = $('#purchase_list').DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					url: '/admin/purchase/list/',
					data: function (d) {
						d.purchase_date = $('#f-purchase-date').val();
					}
				},

				order: [ [0, 'desc'] ],

				columns: [
					{ data: 'id', name: 'id' },
					{ data: 'amount', name: 'amount' },
					{ data: 'purchase_date', name: 'purchase_date' },
					{ data: 'mpt_price', name: 'mpt_price' },
					{ data: 'telenor_price', name: 'telenor_price' },
					{ data: 'ooredoo_price', name: 'ooredoo_price' },
					{ data: 'mytel_price', name: 'mytel_price' },
					{ data: 'mec_price', name: 'mec_price' },
					{ data: 'balances.balance', name: 'balances.balance' },
					{ data: 'action', name: 'action' }
				]
			});
		},

		changeDate() {
			this.table.draw();
		},

		showNewPurchase() {
			this.cleanData();
			this.edit = false;
			$('#purchase_modal').modal('show');
		},

		cleanData() {
			this.purchase.id = null;
			this.purchase.amount = null;
			this.purchase.purchase_date = null;
			this.purchase.mpt_price = null;
			this.purchase.telenor_price = null;
			this.purchase.ooredoo_price = null;
			this.purchase.mytel_price = null;
			this.purchase.mec_price = null;
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

			axios.post('/admin/purchase/' + url, this.purchase).then(({data}) => {
				if (data.status == true) {
					$('#purchase_modal').modal('hide');
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

		showEditPurchase(id) {
			this.cleanData();
			this.edit = true;

			axios.get('/admin/purchase/edit/' + id).then(({data}) => {
				if (data.status == true) {
					var purchase = data.purchase;

					var purchase_date = new Date(purchase.purchase_date);

					this.purchase.id = purchase.id;
					this.purchase.amount = purchase.amount;
					this.purchase.purchase_date = purchase.purchase_date;
					this.purchase.mpt_price = purchase.mpt_price;
					this.purchase.telenor_price = purchase.telenor_price;
					this.purchase.ooredoo_price = purchase.ooredoo_price;
					this.purchase.mytel_price = purchase.mytel_price;
					this.purchase.mec_price = purchase.mec_price;

					$('#purchase_modal').modal('show');
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

		deletePurchase(id) {
			this.remove_id = id;
			$('#delete_modal').modal('show');
		},

		removePurchase() {
			axios.post('/admin/purchase/delete', {id: this.remove_id}).then(({data}) => {
				if (data.status == true) {
					$('#delete_modal').modal('hide');
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

		$('#purchase_list').delegate('.edit_purchase', 'click', (evt) => {
			const id = evt.currentTarget.getAttribute('data-id');
			this.showEditPurchase(id);
		});

		$('#purchase_list').delegate('.delete_purchase', 'click', (evt) => {
			const id = evt.currentTarget.getAttribute('data-id');
			this.deletePurchase(id);
		});
	}
}