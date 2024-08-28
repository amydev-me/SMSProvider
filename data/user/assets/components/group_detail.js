const DeleteModal = resolve => require(['../core/DeleteModal'], resolve);

module.exports= {
	components: {DeleteModal},

	data: function () {
		return {
			removeUrl: '/group/delete/',
			contacts: [],

			group: {
				groupName: null,
				description: null,
				id: null
			},

			edit_group: {
				groupName: null,
				description: null,
				id: null
			},

			group_id: null,
			selected_group: {},

			toggle_check: null,
			selected_contacts: []
		}
	},

	methods: {
		showContactModal() {
			$('#contact_modal').modal('show');
		},

		editModal() {
			this.edit_group = Object.assign({}, this.group);
			$('#goupmodal').modal('show');
		},

		getUrlParam() {
			$group_id = Helper.getUrlParameter('group_id');

			if ($group_id) {
				this.group_id = $group_id;
				this.getGroupAndContacts();
			}
		},

		getGroupAndContacts() {
			axios.get('/list/contacts/' + this.group_id).then(({data}) => {
				this.group.groupName = data.groupName;
				this.group.description = data.description;
				this.group.id = data.id;
				this.contacts = data.contacts;
				this.selected_group = { id: this.group.id, groupName: this.group.groupName };
			});
		},

		validateGroup(scope) {
			this.$validator.validateAll(scope).then(successsValidate => {
				if (successsValidate) {
					this.submit();
				}
			}).catch(error => {
				Notification.warning('Opps!Something went wrong.');
			});
		},

		submit() {
			axios.post('/group/update', this.edit_group).then(({data}) => {
				if (data.status == true) {
					$('#goupmodal').modal('hide');
					Notification.success('Success');
					this.group = this.edit_group;
				} else {
					Notification.error('Error occurs while creating data.');
				}
			}).catch(error => {
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/login';
				} else {
					Notification.error('Error occured while deleting data.');
				}
			});
		},

		showDeleteModal() {
			$('#deleteModal').modal('show');
		},

		successdelete() {
			window.location.href = '/address-book';
		},

		showDeleteMultiContactModal() {
			$('#contact_multi_modal').modal('show');
		},

		checkContacts(e) {
			if (e.target.checked) {
				let arr = [];

				this.contacts.forEach(function(element) {
					arr.push(element.id);
				});

				this.selected_contacts = arr;
			} else {
				this.selected_contacts = [];
			}
		},

		promptDelete() {
			$('#delete_background').show();
			$('#contact_delete_modal').modal('show');
		},

		confirmDelete() {
			if (this.selected_contacts.length > 0) {
				axios.post('/group/delete-contacts', {contacts: this.selected_contacts}).then(({data}) => {
					if (data.status == true) {
						this.getGroupAndContacts();
						Notification.success('Success');
						$('#contact_delete_modal').modal('hide');
					} else {
						Notification.error('Error occurs while creating data.');
					}
				}).catch(error => {
					if (error.response.status == 401 || error.response.status == 419) {
						window.location.href = '/login';
					} else {
						Notification.error('Error occured while deleting data.');
					}
				});
			} else {
				Notification.error('Select at least one contact.');
			}
		}
	},

	mounted () {
		this.getUrlParam();

		$('#contact_delete_modal').on('hidden.bs.modal', function (e) {
			$('#delete_background').hide();
		});
	}
}