var Chart = require('chart.js');

module.exports= {
	template: `<div class="col-lg-6">
					<div class="au-card m-b-30">
						<div class="au-card-inner">
							<h3 class="title-2 m-b-40">SMS Usage Statistics</h3>
							<canvas id="team-chart" height="100px;"></canvas>
						</div>
					</div>
				</div>`,

	data: function () {
		return {
			year: null,
			month: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			dataStatus: ["Api", "Web App"],
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
			axios.get('/dashboard/sms_usage?year=' + this.year).then(({data}) => {
				this.apiData = data;

				for (property in this.apiData) {
					var _key = property;
					var _index = this.month.findIndex(e => e == _key);

					this.chartData[_index] = this.apiData[_key];
				}
			}).then(data=>{
				var myChart = new Chart('team-chart', {
					type: 'line',
					data: {
						labels: this.month,
						type: 'line',
						defaultFontFamily: 'Poppins',
						datasets: [{
							data: this.chartData,
							label: "SMS",
							backgroundColor: '#fff',
							borderColor: 'rgba(0,103,255,0.5)',
							borderWidth: 3.5,
							pointStyle: 'circle',
							pointRadius: 5,
							pointBorderColor: 'transparent',
							pointBackgroundColor: 'rgba(0,103,255,0.5)',
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
									labelString: 'Month'
								},
								ticks: {
									fontFamily: "Poppins",
									beginAtZero: true,

								}
							}],
							yAxes: [{
								display: true,
								gridLines: {
									display: false,
									drawBorder: false
								},
								scaleLabel: {
									display: true,
									labelString: 'Value',
									fontFamily: "Poppins"
								},
								ticks: {
									fontFamily: "Poppins",
									beginAtZero: true,

								}
							}]
						},
						title: {
							display: false,
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
		this.createPieChart();
	}
}