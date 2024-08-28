module.exports = {
	data: function () {
		return {
			packages: [],

			package: {
				id: null,
				packageName: null,
				credit: null,
				cost: null,
				currency_type: null
			},

			options: [
				{ text: 'Active', value: '1' },
				{ text: 'Inactive', value: '0' }
			],

			edit: false,
			free: false,
			edit_promo: false,

			promotion: {
				id: null,
				package_id: null,
				promo_credit: null,
				promo_status: null,
				max_purchase: null
			},
		}
	},

	methods: {
		getPackages() {
			axios.get('/admin/package/packages').then(({data}) => {
				this.packages = data;
			});
		},

		cleanData() {
			this.package.id = null;
			this.package.packageName = null;
			this.package.credit = null;
			this.package.cost = null;
			this.package.currency_type = null;
			this.$validator.reset();
		},

		showAddModal() {
			this.cleanData();
			this.edit = false;
			this.free = false;
			$('#package_modal').modal('show');
			$('#package_name').focus();
		},

		showEditModal(data) {
			this.edit = true;
			this.package = Object.assign({}, data);

			if (this.package.packageName == 'Free') {
				this.free = true;
			} else {
				this.free = false;
			}

			$('#package_modal').modal('show');
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

			axios.post('/admin/package/' + url, this.package).then(({data}) => {
				if (data.status == true) {
					this.getPackages();
					$('#package_modal').modal('hide');
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
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/admin/login';
				} else {
					Notification.error('Error occured while creating/updating data.');
				}
			});
		},

		showPromotionModal(data) {
			this.cleanPromotion();
			this.edit_promo = false;
			this.promotion.package_id = data.id;
			$('#promotion_modal').modal('show');
		},

		validatePromotion() {
			var url = this.edit_promo ? 'update' : 'create';

			axios.post('/admin/package/promotion/' + url, this.promotion).then(({data}) => {
				if (data.status == true) {
					this.getPackages();
					$('#promotion_modal').modal('hide');
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
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/admin/login';
				} else {
					Notification.error('Error occured while creating/updating data.');
				}
			});
		},

		viewPromotion(promotion) {
			this.edit_promo = true;
			this.promotion = Object.assign({}, promotion);
			$('#promotion_modal').modal('show');
		},

		cleanPromotion() {
			this.promotion.id = null;
			this.promotion.package_id = null;
			this.promotion.promo_credit = null;
			this.promotion.promo_status = null;
			this.promotion.max_purchase = null;
		},

		deletePromotionModal(id) {
			this.cleanPromotion();
			this.promotion.id = id;
			$('#deleteModal').modal('show');
		},

		performDelete() {
			axios.post('/admin/package/promotion/delete/' + this.promotion.id).then(({data}) => {
				if (data.success) {
					this.getPackages();
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
		this.getPackages();
	}
}