var Chart = require('chart.js');

module.exports = {
	template: `<div class="col-lg-6">
					<div class="au-card m-b-30">
						<div class="au-card-inner">
							<h3 class="title-2 m-b-40">Last Week</h3>
							<canvas id="week-chart" height="100px;"></canvas>
						</div>
					</div>
				</div>`,

	data: function () {
		return {
			month:[],
			chartData: [],
			chartColors: [
				"rgba(0, 123, 255,0.9)",
				"rgba(0, 123, 255,0.7)",
			],
			apiData: []
		}
	},

	methods: {
		createPieChart() {
			axios.get('/dashboard/last_week').then(({data}) => {
				this.month = data.logs;
				this.chartData = data.data;

				this.month = this.month.map(x => Helper.formatPrettyDateMonth(x));

			}).then(data => {
				var myChart = new Chart('week-chart', {
					type: 'line',
					data: {
						labels: this.month,
						type: 'line',
						defaultFontFamily: 'Poppins',
						datasets: [{
							data: this.chartData,
							label: "SMS",
							backgroundColor: '#fff',
							borderColor: '#F60E6B',
							borderWidth: 3.5,
							pointStyle: 'circle',
							pointRadius: 5,
							pointBorderColor: 'transparent',
							pointBackgroundColor: '#F60E6B',
						},]
					},
					options: {
						responsive: true,
						tooltips: {
							mode: 'index',
							titleFontSize: 12,
							titleFontColor: '#000',
							bodyFontColor: '#000',
							backgroundColor: '#fff',
							titleFontFamily: 'Poppins',
							bodyFontFamily: 'Poppins',
							cornerRadius: 3,
							intersect: false,
						},
						legend: {
							display: false,
							position: 'top',
							labels: {
								usePointStyle: true,
								fontFamily: 'Poppins',
							},


						},
						scales: {
							xAxes: [{
								display: true,
								gridLines: {
									display: false,
									drawBorder: false
								},
								scaleLabel: {
									display: false,

								},
								ticks: {
									fontFamily: "Poppins",
									beginAtZero: true
								}
							}],
							yAxes: [{
								display: true,
								gridLines: {
									display: false,
									drawBorder: false
								},
								ticks: {
									fontFamily: "Poppins",
									beginAtZero: true
								}
							}]
						},
						title: {
							display: false,
						}
					}
				});
			});
		}
	},

	mounted() {
		this.createPieChart();
	}
}