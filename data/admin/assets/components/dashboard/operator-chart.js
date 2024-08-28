var Chart = require('chart.js');

module.exports = {
	template: `<div class="col-lg-6 col-xl-4">
					<div class="au-card m-b-30 pie">
						<div class="au-card-inner">
							<h3 class="title-2 m-b-40">Operator Usage</h3>
							<canvas id="operator-chart"></canvas>
						</div>
					</div>
				</div>`,

	data: function () {
		return {
			year: null,
			operators: ['MPT', 'Ooredoo', 'Telenor', 'MyTel'],
			chartColors: ['#FFCA08', '#FF0000', '#00AAF8', '#F26523'],
			response_data: [],
			operator_data: []
		}
	},

	methods: {
		createOperatorPieChart() {
			axios.get('/admin/dashboard/operators?year=' + this.year).then(({data}) => {
				this.response_data = data;
				var that = this;

				this.response_data.forEach(function (item) {
					var optIndex = that.operators.findIndex(e => e == item.operator);
					that.operator_data[optIndex] = item.total_sms;
				});

			}).then(() => {
				new Chart('operator-chart', {
					type: 'pie',
					data: {
						labels: this.operators,
						datasets: [
							{
								data: this.operator_data,
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

			console.log(this.year);
		},
	},

	mounted() {
		this.getUrlParameter();
		this.createOperatorPieChart();
	}
}