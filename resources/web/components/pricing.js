const VuePagination = resolve => require(['../core/VuePagination'], resolve);

module.exports = {
	components: { VuePagination },

	data: function () {
		return {
			countries: [],

			search: '',
			page_size: 2,

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
			axios.get('/pricing/country?page=' + this.pagination.current_page + '&page_size=' + this.page_size).then(({data}) => {
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
				axios.get('/pricing/search/' + this.search + '?page=' + this.pagination.current_page + '&page_size=' + this.page_size).then(({data}) => {
					this.pagination = data;
					this.countries = data.data;
				}).catch(error => {
					console.log(error);
				});
			}
		},
	},

	mounted() {
		this.getCountries();
	}
}