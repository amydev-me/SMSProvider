const DeleteModal = resolve => require(['../../core/DeleteModal'], resolve);
const datePicker	= resolve => require(['vue-bootstrap-datetimepicker'], resolve);
const VuePagination = resolve => require(['../../core/VuePagination'], resolve);

module.exports = {
	components: {DeleteModal,datePicker,VuePagination},

	data: function () {
		return {
			contacts: [],
			filtertext: null,
			selected_group: [],
			contact_id: null,
			removeUrl: '/contact/delete/',
			
			pagination: {
				total: 0,
				per_page: 2,
				from: 1,
				to: 0,
				current_page: 1,
				last_page: 1,
			},
		}
	},
	methods: {
		success() {
			this.getAllContacts();
		},

		getAllContacts() {
			axios.get('/contact/list?page='+this.pagination.current_page).then(({data}) => {
				this.contacts = data.data;
				this.pagination = data;
			}).catch(error => {

			})
		},

		showContactModal() {
			$('#contact_modal').modal('show');
		},

		paginationClick() {
			if (this.filtertext == null || this.filtertext=='') {
				this.getAllContacts();
			} else {
				axios.get('/contact/filter?page='+this.pagination.current_page, {
					params: {
					param: this.filtertext
					}
				}).then(({data}) => {
					this.contacts = data.data;
					this.pagination = data;
				});
			}
		},

		filterContact() {
			this.pagination.current_page = 1;

			if (this.filtertext == null || this.filtertext == '') {

				this.getAllContacts();
			} else {
				axios.get('/contact/filter?page='+this.pagination.current_page, {
					params: {
						param: this.filtertext
					}
				}).then(({data}) => {
					this.contacts = data.data;
					this.pagination = data;
				});
			}
		},

		showDeleteModal(id) {
			this.contact_id = id;
			$('#deleteModal').modal('show');
		},

		successdelete() {
			this.getAllContacts();
			this.contact_id = null;
			$('#deleteModal').modal('hide');
		}
	},

	mounted() {
		this.getAllContacts();
	}
}