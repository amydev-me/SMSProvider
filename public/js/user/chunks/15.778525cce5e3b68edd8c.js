webpackJsonp([15],{200:function(t,a,e){var i=e(146);t.exports={template:'<div class="col-lg-6">\n\t\t\t\t\t<div class="au-card m-b-30">\n\t\t\t\t\t\t<div class="au-card-inner">\n\t\t\t\t\t\t\t<h3 class="title-2 m-b-40">SMS Usage Statistics</h3>\n\t\t\t\t\t\t\t<canvas id="team-chart" height="100px;"></canvas>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t</div>\n\t\t\t\t</div>',data:function(){return{year:null,month:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],dataStatus:["Api","Web App"],chartData:[],chartColors:["rgba(0, 123, 255,0.9)","rgba(0, 123, 255,0.7)"],apiData:[]}},methods:{createPieChart:function(){var t=this;axios.get("/dashboard/sms_usage?year="+this.year).then(function(a){var e=a.data;for(property in t.apiData=e,t.apiData){var i=property,n=t.month.findIndex(function(t){return t==i});t.chartData[n]=t.apiData[i]}}).then(function(a){new i("team-chart",{type:"line",data:{labels:t.month,type:"line",defaultFontFamily:"Poppins",datasets:[{data:t.chartData,label:"SMS",backgroundColor:"#fff",borderColor:"rgba(0,103,255,0.5)",borderWidth:3.5,pointStyle:"circle",pointRadius:5,pointBorderColor:"transparent",pointBackgroundColor:"rgba(0,103,255,0.5)"}]},options:{responsive:!0,tooltips:{mode:"index",titleFontSize:12,titleFontColor:"#000",bodyFontColor:"#000",backgroundColor:"#fff",titleFontFamily:"Poppins",bodyFontFamily:"Poppins",cornerRadius:3,intersect:!1},legend:{display:!1,position:"top",labels:{usePointStyle:!0,fontFamily:"Poppins"}},scales:{xAxes:[{display:!0,gridLines:{display:!1,drawBorder:!1},scaleLabel:{display:!1,labelString:"Month"},ticks:{fontFamily:"Poppins",beginAtZero:!0}}],yAxes:[{display:!0,gridLines:{display:!1,drawBorder:!1},scaleLabel:{display:!0,labelString:"Value",fontFamily:"Poppins"},ticks:{fontFamily:"Poppins",beginAtZero:!0}}]},title:{display:!1}}})})},getUrlParameter:function(){var t=Helper.getUrlParameter("year");this.year=t||(new Date).getFullYear()}},mounted:function(){this.getUrlParameter(),this.createPieChart()}}}});