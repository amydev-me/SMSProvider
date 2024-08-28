const datePicker = resolve => require(['vue-bootstrap-datetimepicker'], resolve);
const VuePagination = resolve => require(['../core/VuePagination'], resolve);

module.exports = {
	components: { datePicker, VuePagination },

	data: function () {
		return {
			logs: [],

			options: {
				format: 'DD MMM YYYY',
				useCurrent: false,
			},

			startDate: null,
			endDate: null,

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
		logsFilterByDate() {
			axios.get('/filter-logs', {params: {start_date: this.startDate, end_date: this.endDate, page: this.pagination.current_page}}).then(({data}) => {
				this.pagination = data;
				this.logs = data.data;
			}).catch(error => {

			});
		},

		formatDate(date) {
			return Helper.formatPrettyDateTime(date);
		}
	},

	mounted() {
		this.startDate = Helper.formatPrettyDate(new Date());
		this.endDate = Helper.formatPrettyDate(new Date());
		this.logsFilterByDate();
	}
}