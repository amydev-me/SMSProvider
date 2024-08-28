var Chart = require('chart.js');

module.exports = {
	template: `<div class="col-lg-6 col-xl-4">
					<div class="au-card m-b-30 pie">
						<div class="au-card-inner">
							<h3 class="title-2 m-b-40">Delivery Rate</h3>
							<canvas id="delivery-chart"></canvas>
						</div>
					</div>
				</div>`,

	data: function () {
		return {
			year: null,
			deliveries: ['Delivered', 'Failed', 'Rejected'],
			chartColors: ['#28A745', '#DC3545', '#004476'],
			response_data: [],
			delivery_data: []
		}
	},

	methods: {
		createDeliveryDoughnutChart() {
			axios.get('/admin/dashboard/deliveries?year=' + this.year).then(({data}) => {
				this.response_data = data.deliveries;
				var that = this;

				this.response_data.forEach(function (item) {
					var optIndex = that.deliveries.findIndex(e => e == item.status);

					var _percent = (item.total / data.all_count) * 100;
					that.delivery_data[optIndex] =_percent.toFixed(0);
					that.deliveries[optIndex] = item.status + " " + _percent.toFixed(0) + "%";
				});

			}).then(() => {
				new Chart('delivery-chart', {
					type: 'doughnut',
					data: {
						labels: this.deliveries,
						datasets: [
							{
								data: this.delivery_data,
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
		this.createDeliveryDoughnutChart();
	}
}