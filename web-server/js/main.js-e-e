var currentTime = new Date();

// Load the Visualization API and the piechart package.
google.load('visualization', '1', {'packages':['corechart']});
  var selectedItemId = 1;
  $(document).ready(function() {
	$("#chart_container").hide();
	$(".item").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});
	$(".item").click(function() {
		var newItem = $(this);
		if(newItem.attr('id') != selectedItemId)
		{
			selectedItemId = newItem.attr('id');
			$(".active").removeClass("active");
			newItem.addClass("active");
			var top = newItem.position().top + 85;
			$("#indicator").animate({'top':top});
		
			if(selectedItemId == 1)
			{
				$("#chart_container").fadeOut("fast", function() {
					$("#schema_container").fadeIn("fast");
				});
				actualValues();
			}
			else
			{
				$("#schema_container").fadeOut("fast", function() {
					$("#chart_container").show();
					drawChart();
				});
			}
		}
	});
	
	$("#back").click(function (){
		currentTime.setTime(currentTime.getTime() - 86400000);
		drawChart();
	});
	$("#forward").click(function (){
		currentTime.setTime(currentTime.getTime() + 86400000);
		drawChart();
	});
	
	$("#schema_container").load("images/schema.svg", actualValues);

	chart = new google.visualization.LineChart(document.getElementById('chart_div'));
  });

function drawChart() {
  $.ajax({
      url: "index.php",
	  data: {
		date: (currentTime.getFullYear() + "-" + (currentTime.getMonth() + 1) + "-" + currentTime.getDate()),
		id: selectedItemId
	  },
      dataType:"json",
      success: function(jsonData) {
		  // Create our data table out of JSON data loaded from server.
		  var data = new google.visualization.DataTable(jsonData);

		  // Instantiate and draw our chart, passing in some options.
		  chart.draw(data, {width: 1000, height: 500,hAxis: {format:'H:mm'}, vAxis: {format:'#.# \u00B0C',minValue:0},animation: {
		   duration: 1000,
		    easing: 'out'
		  },
		  chartArea: {width: '80%', height: '80%'},
		  legend: {position: 'bottom'}});
	  }
  });
}

function actualValues()
{
	if(selectedItemId == 1)
	{
	  $.ajax({
	      url: "latest.php",
	      dataType:"json",
	      success: function(jsonData) {
			  $("#speicher1_oben > tspan").text(jsonData.analog1 + ' \u00B0C');
			  $("#speicher1_unten > tspan").text(jsonData.analog2 + ' \u00B0C');
			  $("#speicher2_oben > tspan").text(jsonData.analog3 + ' \u00B0C');
			  $("#speicher2_unten > tspan").text(jsonData.analog4 + ' \u00B0C');
			  $("#innen_temp > tspan").text(jsonData.analog5 + ' \u00B0C');
			  $("#aussen_temp > tspan").text(jsonData.analog6 + ' \u00B0C');
			  $("#vl1_temp > tspan").text(jsonData.analog7 + ' \u00B0C');
			  $("#vl2_temp > tspan").text(jsonData.analog8 + ' \u00B0C');
			  $("#rl3_temp > tspan").text(jsonData.analog9 + ' \u00B0C');
			  $("#kessel_temp > tspan").text(jsonData.analog12 + ' \u00B0C');
			  $("#fb_pump > tspan").text(digitalValue(jsonData.digital2));
			  $("#hz_pump > tspan").text(digitalValue(jsonData.digital1));
			  $("#ww_pump > tspan").text(digitalValue(jsonData.digital6));
			  setTimeout(actualValues, 60000);
		  }
	  });
	}
}

function digitalValue(value)
{
	if(value == '\u0001')
	{
		return 'EIN';
	}
	else
	{
		return 'AUS';
	}
}
