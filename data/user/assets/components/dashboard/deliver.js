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
			status: ["Delivered", "Failed", "Rejected"],
			dataStatus: ["Delivered", "Failed", "Rejected"],
			chartColors: ['#28A745', '#DC3545', '#004476'],
			chartData: [],
			apiData: []
		}
	},

	methods: {
		createDoughnutChart () {
			axios.get('/dashboard/deliver?year=' + this.year).then(({data}) => {
				this.apiData = data.status;
				var that = this;

				this.apiData.forEach(function (item) {
					var optIndex = that.status.findIndex(e => e == item.status);

					var _percent = (item.total/data.allcount) * 100;
					that.chartData[optIndex] =_percent.toFixed(0);
					that.dataStatus[optIndex] = item.status + " " + _percent.toFixed(0) + "%"
				});
			}).then(() => {
				new Chart('delivery-chart', {
					type: 'doughnut',
					data: {
						labels: this.dataStatus,
						datasets: [
							{
								data: this.chartData,
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
	
	mounted () {
		this.getUrlParameter();
		this.createDoughnutChart();
	}
}