const datePicker  = resolve => require(['vue-bootstrap-datetimepicker'], resolve);

module.exports = {
	components: {datePicker},

	data: function() {
		return {
			table: null,

			options: {
				format: 'DD MMM YYYY',
				useCurrent: false,
			},

			f_from_date: null,
			f_to_date: null,
		}
	},

	methods: {
		loadTable() {
			this.table = $('#sms_list').DataTable({
				searching: false,
				processing: true,
				serverSide: true,
				ajax: {
					url: '/admin/operator-log/list/',
					data: function (d) {
						d.from_date = $('#f-from-date').val();
						d.to_date = $('#f-to-date').val();
					}
				},

				order: [ [1, 'desc'] ],

				columns: [
					{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
					{ data: 'created_at', name: 'created_at', searchable: false },
					{ data: 'recipients', name: 'recipients', orderable: false, searchable: false },
					{ data: 'sender_name', name: 'sender_name' },
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
		this.loadTable();
	}
}