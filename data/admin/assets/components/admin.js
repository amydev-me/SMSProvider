const VuePagination = resolve => require(['../core/VuePagination'], resolve);

module.exports = {
	components: { VuePagination },

	data: function() {
		return {
			admins: [],

			admin: {
				id: null,
				username: null,
				full_name: null,
				password: null,
				role: 1
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

			remove_id: null,
			remove_admin: null,
		}
	},

	methods: {
		getAdmins() {
			axios.get('/admin/list/show?page=' + this.pagination.current_page).then(({data}) => {
				this.pagination = data;
				this.admins = data.data;
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
				this.getAdmins();
			} else {
				axios.get('/admin/list/search/' + this.search + '?page=' + this.pagination.current_page).then(({data}) => {
					this.pagination = data;
					this.admins = data.data;
				}).catch(error => {
					console.log(error);
				});
			}
		},

		showAddModal() {
			this.clearData();
			this.is_edit = false;
			$('#adminModal').modal('show');
		},

		showEditModal(admin) {
			this.is_edit = true;
			this.admin = Object.assign({}, admin);
			$('#adminModal').modal('show');
		},

		clearData() {
			this.is_edit = false;
			this.admin.id = null;
			this.admin.username = null;
			this.admin.full_name = null;
			this.admin.password = null;
			this.admin.role = 1;
			this.$validator.reset();
		},

		validateData() {
			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					let _meth = !this.is_edit ? 'create' : 'update';

					axios.post('/admin/list/' + _meth, this.admin).then(({data}) => {
						if (data.status == true) {
							this.getAdmins();
							$('#adminModal').modal('hide');
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

		showDeleteModal(id, username) {
			this.clearData();
			this.remove_id = id;
			this.remove_admin = username;
			$('#deleteModal').modal('show');
		},

		performDelete() {
			axios.post('/admin/list/delete/' + this.remove_id).then(({data}) => {
				if (data.success) {
					this.pagination.current_page = 1;
					this.getAdmins();

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
		this.getAdmins();

		var that = this;

		$('#countryModal').on('hidden.bs.modal', function (e) {
			that.clearData();
		});
	}
}