const VuePagination = resolve => require(['../core/VuePagination'], resolve);

module.exports = {
	components: { VuePagination },

	data: function () {
		return {
			countries: [],

			country: {
				id: null,
				name: null,
				iso: null,
				code: null,
				prefix: null,
				rate: null,
				cost: null,
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
			}
		}
	},

	methods: {
		getCountries() {
			axios.get('/admin/country/list?page=' + this.pagination.current_page).then(({data}) => {
				this.pagination = data;
				this.countries = data.data;
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
				this.getCountries();
			} else {
				axios.get('/admin/country/search/' + this.search + '?page=' + this.pagination.current_page).then(({data}) => {
					this.pagination = data;
					this.countries = data.data;
				}).catch(error => {
					console.log(error);
				});
			}
		},

		showAddModal() {
			this.clearData();
			this.is_edit = false;
			$('#countryModal').modal('show');
		},

		showEditModal(country) {
			this.is_edit = true;
			this.country = Object.assign({}, country);
			$('#countryModal').modal('show');
		},

		validateData() {
			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					let _meth = !this.is_edit ? 'create' : 'update';

					axios.post('/admin/country/' + _meth, this.country).then(({data}) => {
						if (data.success) {
							this.getCountries();
							$('#countryModal').modal('hide');
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
			this.country.id = null;
			this.country.name = null;
			this.country.iso = null;
			this.country.code = null;
			this.country.prefix = null;
			this.country.rate = null;
			this.country.cost = null;
			this.$validator.reset();
		},

		showStatusModal(country) {
			this.clearData();
			this.country = Object.assign({}, country);
			$('#statusModal').modal('show');
		},

		changeStatus() {
			axios.post('/admin/country/status/' + this.country.id + '?page=' + this.pagination.current_page).then(({data}) => {
				if (data.success) {
					if (this.search == "") {
						this.getCountries();
					} else {
						this.searchClick();
					}

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
			this.country.id = id;
			$('#deleteModal').modal('show');
		},

		performDelete() {
			axios.post('/admin/country/delete/' + this.country.id).then(({data}) => {
				if (data.success) {
					this.pagination.current_page = 1;
					this.getCountries();

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
		this.getCountries();

		var that = this;

		$('#countryModal').on('hidden.bs.modal', function (e) {
			that.clearData();
		});
	}
}