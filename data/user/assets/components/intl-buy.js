const VuePagination = resolve => require(['../core/VuePagination'], resolve);

module.exports = {
	components: { VuePagination },

	data: function () {
		return {
			pricings: [],
			filter_text: null,

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
		getAllPricing() {
			axios.get('/buy-intl/list?page=' + this.pagination.current_page).then(({data}) => {
				this.pricings = data.data;
				this.pagination = data;
			}).catch(error => {

			})
		},

		paginationClick() {
			if (this.filter_text == null || this.filter_text == '') {
				this.getAllPricing();
			} else {
				axios.get('/buy-intl/filter?page=' + this.pagination.current_page, {
					params: {
						param: this.filter_text
					}
				}).then(({data}) => {
					this.pricings = data.data;
					this.pagination = data;
				});
			}
		},

		filterCountry() {
			this.pagination.current_page = 1;

			if (this.filter_text == null || this.filter_text == '') {
				this.getAllPricing();
			} else {
				axios.get('/buy-intl/filter?page=' + this.pagination.current_page, {
					params: {
						param: this.filter_text
					}
				}).then(({data}) => {
					this.pricings = data.data;
					this.pagination = data;
				});
			}
		},
	},

	mounted() {
		this.getAllPricing();
	}
}