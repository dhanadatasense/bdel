
$(document).ready(function(){

	// base URL
	var baseurl = $('.geturl').val();


   google.charts.load('current', {'packages':['corechart']});
   google.charts.setOnLoadCallback(targetChart);
   
   function targetChart() {
	   $.ajax({
		   type: 'POST',
		   data: {
			   'method' : '_adminTargetDashboard',
		   },
		   url: baseurl + 'index.php/admin/dashboard/admin_chart',
		   dataType: 'json',
	   }).done(function (response)
	   {
		   
		   var data = google.visualization.arrayToDataTable(response['data']);
		   var options = {
				 title: '',
				 pieHole: 0.4,
		   };

		   var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
		   chart.draw(data, options);
	   });
   }

   google.charts.load('current', {'packages':['corechart']});
   google.charts.setOnLoadCallback(orderChart);

   function orderChart() {
	   $.ajax({
		   type: 'POST',
		   data: {
			   'method' : '_adminOrderDashboard',
		   },
		   url: baseurl + 'index.php/admin/dashboard/admin_chart',
		   dataType: 'json',
	   }).done(function (response)
	   {
		   var data = google.visualization.arrayToDataTable(response['data']);
		   var options = {
				 title : '',
				 vAxis: {title: 'Value'},
				 hAxis: {title: 'Process'},
				 seriesType: 'bars',
				 series: {5: {type: 'line'}}
		   };

		   var chart = new google.visualization.ComboChart(document.getElementById('orderchart'));
		   chart.draw(data, options);

		   chart.draw(data, options);
	   });
   }
});