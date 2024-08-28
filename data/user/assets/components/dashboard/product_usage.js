var Chart = require('chart.js');

module.exports = {
	template: `<div class="col-lg-12 col-xl-4">
					<div class="au-card m-b-30 pie">
						<div class="au-card-inner">
							<h3 class="title-2 m-b-40">Product Usage</h3>
							<canvas id="usage-chart"></canvas>
						</div>
					</div>
				</div>`,

	data: function () {
		return {
			status:["Api","Web App"],
			dataStatus:["Api","Web App"],
			chartData: [],
			chartColors: [
				"rgba(0, 123, 255,0.9)",
				"rgba(0, 123, 255,0.7)",
			],
			apiData: []
		}
	},

	methods: {
		createPieChart () {
			axios.get('/dashboard/product_usage?year=' + this.year).then(({data}) => {
				this.apiData = data.source;
				var that = this;
				this.apiData.forEach(function (item) {
					var optIndex = that.status.findIndex(e => e == item.source);

					var _percent=(item.total/data.allcount)*100;
					that.chartData[optIndex] =_percent.toFixed(0);
					that.dataStatus[optIndex]=item.source+" "+_percent.toFixed(0)+"%"
				})
			}).then(() => {
				new Chart('usage-chart', {
					type: 'pie',
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
		this.createPieChart();
	}
}