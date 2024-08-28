var Chart = require('chart.js');

module.exports = {
	template: `<div class="col-lg-12 col-xl-4">
					<div class="au-card m-b-30 pie">
						<div class="au-card-inner">
							<h3 class="title-2 m-b-40">Package Usage</h3>
							<canvas id="package-chart"></canvas>
						</div>
					</div>
				</div>`,

	data: function () {
		return {
			year: null,
			packages: [],
			chartColors: ['#42A5F5','#5C6BC0','#7E57C2', '#AB47BC', '#EC407A', '#EF5350', '#D4E157', '#9CCC65'],
			response_data: [],
			package_data: []
		}
	},

	methods: {
		createPackagePieChart() {
			axios.get('/admin/dashboard/packages?year=' + this.year).then(({data}) => {
				this.packages = data.package_names;
				this.response_data = data.packages;
				var that = this;

				this.response_data.forEach(function (item) {
					var optIndex = that.packages.findIndex(e => e == item.packageName);
					that.package_data[optIndex] = item.total_packages;
				});

			}).then(() => {
				new Chart('package-chart', {
					type: 'pie',
					data: {
						labels: this.packages,
						datasets: [
							{
								data: this.package_data,
								backgroundColor: this.chartColors,
								borderColor: ['#fff'],
								borderWidth: 1,
							}
						]
					},
					options: {
						legend: {
							display: true,
							position: 'right'
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
		this.createPackagePieChart();
	}
}