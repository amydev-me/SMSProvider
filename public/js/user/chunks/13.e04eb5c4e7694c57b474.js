webpackJsonp([13],{193:function(t,a,o){var e=o(146);t.exports={template:'<div class="col-lg-6">\n\t\t\t\t\t<div class="au-card m-b-30">\n\t\t\t\t\t\t<div class="au-card-inner">\n\t\t\t\t\t\t\t<h3 class="title-2 m-b-40">Last Week</h3>\n\t\t\t\t\t\t\t<canvas id="week-chart" height="100px;"></canvas>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t</div>\n\t\t\t\t</div>',data:function(){return{month:[],chartData:[],chartColors:["rgba(0, 123, 255,0.9)","rgba(0, 123, 255,0.7)"],apiData:[]}},methods:{createPieChart:function(){var t=this;axios.get("/dashboard/last_week").then(function(a){var o=a.data;t.month=o.logs,t.chartData=o.data,t.month=t.month.map(function(t){return Helper.formatPrettyDateMonth(t)})}).then(function(a){new e("week-chart",{type:"line",data:{labels:t.month,type:"line",defaultFontFamily:"Poppins",datasets:[{data:t.chartData,label:"SMS",backgroundColor:"#fff",borderColor:"#F60E6B",borderWidth:3.5,pointStyle:"circle",pointRadius:5,pointBorderColor:"transparent",pointBackgroundColor:"#F60E6B"}]},options:{responsive:!0,tooltips:{mode:"index",titleFontSize:12,titleFontColor:"#000",bodyFontColor:"#000",backgroundColor:"#fff",titleFontFamily:"Poppins",bodyFontFamily:"Poppins",cornerRadius:3,intersect:!1},legend:{display:!1,position:"top",labels:{usePointStyle:!0,fontFamily:"Poppins"}},scales:{xAxes:[{display:!0,gridLines:{display:!1,drawBorder:!1},scaleLabel:{display:!1},ticks:{fontFamily:"Poppins",beginAtZero:!0}}],yAxes:[{display:!0,gridLines:{display:!1,drawBorder:!1},ticks:{fontFamily:"Poppins",beginAtZero:!0}}]},title:{display:!1}}})})}},mounted:function(){this.createPieChart()}}}});