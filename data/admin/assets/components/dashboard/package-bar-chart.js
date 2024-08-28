var Chart = require('chart.js');

module.exports = {
	template: `<div class="col-lg-6">
					<div class="au-card m-b-30 line">
						<div class="au-card-inner">
							<h3 class="title-2 m-b-40">Packages Sold</h3>
							<canvas id="package-bar-chart"></canvas>
						</div>
					</div>
				</div>`,

	data: function () {
		return {
			year: null,
			months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			response_data: [],
			package_data: []
		}
	},

	methods: {
		createRegistrationLineChart() {
			axios.get('/admin/dashboard/packagebar?year=' + this.year).then(({data}) => {
				this.response_data = data;
				var that = this;

				this.response_data.forEach(function (item) {
					var optIndex = that.months.findIndex(e => e == item.created_month);
					that.package_data[optIndex] = item.total_packages;
				});
			}).then(() => {
				new Chart('package-bar-chart', {
					type: 'bar',
					data: {
						labels: this.months,
						datasets: [
							{
								yAxisID: 0,
								label: 'Packages Sold in ' + this.year,
								backgroundColor: '#42A5F5',
								borderColor: '#42A5F5',
								data: this.package_data,
								fill: false,
							}
						]
					},
					options: {
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero: true
								}
							}]
						}
					}
				});
			});
		},

		getUrlParameter () {
			let year = Helper.getUrlParameter('year');

			if (year) {
				this.year = year;
			} else {
				this.year = new Date().getFullYear();
			}
		},
	},

	mounted() {
		this.getUrlParameter();
		this.createRegistrationLineChart();
	}

}