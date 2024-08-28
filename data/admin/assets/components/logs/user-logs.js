module.exports = {
	data: function() {
		return {
			table: null,
		}
	},

	methods: {
		loadTable() {
			this.table = $('#user_list').DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					url: '/dashboard-user/list/',
					data: function (d) {
						d.account_type = $('#f-account-type').val();
					}
				},

				order: [ [0, 'desc'] ],

				columns: [
					{ data: 'id', name: 'id' },
					{ data: 'username', name: 'username' },
					{ data: 'account_type', name: 'account_type' },
					{ data: 'mobile', name: 'mobile' },
					{ data: 'email', name: 'email' },
					{ data: 'action', name: 'action', orderable: false, searchable: false }
				]
			});
		},

		changeType() {
			this.table.draw();
		},
	},

	mounted() {
		this.loadTable();
	}
}