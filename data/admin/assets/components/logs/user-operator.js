const datePicker  = resolve => require(['vue-bootstrap-datetimepicker'], resolve);

module.exports = {
	components: {datePicker},

	data: function () {
		return {
			table: null,

			user_id: null,

			options: {
				format: 'DD-MM-YYYY',
				useCurrent: false,
			},

			f_from_date: null,
			f_to_date: null,
		}
	},

	methods: {
		getUrlParam() {
			var uri = window.location.pathname;
			var res = uri.split("/");
			this.user_id = res[3];
		},

		loadTable() {
			var user_id = this.user_id;

			this.table = $('#sms_list').DataTable({
				searching: false,
				processing: true,
				serverSide: true,
				ajax: {
					url: '/dashboard-user/logs',
					data: function (d) {
						d.from_date = $('#f-from-date').val();
						d.to_date = $('#f-to-date').val();

						d.user_id = user_id;
					}
				},

				order: [ [1, 'desc'] ],

				columns: [
					{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
					{ data: 'created_at', name: 'created_at', searchable: false },
					{ data: 'recipients', name: 'recipients', orderable: false, searchable: false },
					{ data: 'message_content', name: 'message_content' },
					{ data: 'message_parts', name: 'message_parts' },
					{ data: 'total_sms', name: 'total_sms' },
					{ data: 'detail', name: 'detail', orderable: false, searchable: false }
				]
			});
		},

		changeDate() {
			this.table.draw();
		},
	},

	mounted() {
		this.getUrlParam();

		this.loadTable();
	}
}