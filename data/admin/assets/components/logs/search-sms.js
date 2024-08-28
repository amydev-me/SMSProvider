const VuePagination = resolve => require(['../../core/VuePagination'], resolve);

module.exports = {
	components: { VuePagination },

	data: function() {
		return {
			search_type: '',
			keyword: null,

			logs: null,

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
		searchClick() {
			this.pagination.current_page = 1;
			this.filter();
		},

		filter() {
			axios.post('/dashboard-user/search' + '?search_type=' + this.search_type + '&keyword=' + this.keyword + '&page=' + this.pagination.current_page).then(({data}) => {
				this.pagination = data;
				this.logs = data.data;
			}).catch(error => {
				console.log(error);
			});
		}
	}
}