var currentTime = new Date();

// Load the Visualization API and the piechart package.
google.load('visualization', '1', {'packages':['corechart']});

var selectedItem = null;

function checkBrowser()
{
	if(($.browser.chrome && $.browser.version.slice(0,2)>6) ||
	   ($.browser.webkit && $.browser.version.slice(0,3)>533) ||
	   ($.browser.mozilla && $.browser.version.slice(0,3)>=2.0) ||
	   ($.browser.msie && $.browser.version.slice(0,2)>7) ||
	   ($.browser.opera && $.browser.version.slice(0,4)>11.5) )
	{
		$(document).ready(loadMenu);
	}
	else
	{		
		$(document).ready(function() {
			$("#browser a").click(function() {		
				loadMenu();
			});
		});
	}
}
checkBrowser();

function loadMenu()
{
	$("#browser").hide();
	if(!$("div.item").is(":visible"))
	{
		$("div.item").fadeIn('slow');
	}	
}


$.ajax({
    url: "menu.php",
    dataType:"json",
    success: function(jsonData){
		values = jsonData.values;
		var $menu = $("<div></div>");
		for (var i in jsonData.menu)
		{
			var item = jsonData.menu[i];
			var $item = $('<div class="item"><div class="icon"></div><div>'+item.name+'</div></div>');
			switch(item.type)
			{
				case 'schema':
					$item.find("div.icon").addClass("home");
					break;
				default:
					$item.find("div.icon").addClass("chart");
			  		break;
			}
		  
			$item.data(item);
			$menu.append($item);
		}

		$menu.find("div.item").hover(function() {
			$(this).addClass("hover");
		},
		function() {
			$(this).removeClass("hover");
		});
		
		$menu.find("div.item").click(menuClickHandler);
		
		$(document).ready(function() {
			$("#menu").append($menu.children());
		});
    }
});


function menuClickHandler()
{
	var newItem = $(this);
	$("div.active").removeClass("active");
	newItem.addClass("active");
	
	var indicator = $("#indicator");
	var top = newItem.position().top + 14;
	var left = newItem.position().left -1;
	
	if(!indicator.is(':visible'))
	{
		indicator.css({'top': top , 'left': left});
		indicator.fadeIn();
	}

	if(indicator.position().top != top)
	{
		$("#indicator").animate({'top':top});
	}
	$("#indicator").animate({'left':left}, function() {
		
		
		$("#logo").animate({'top':230,'left':230});
		$("#menu").fadeOut();
		$("body").animate({'background-color':'#FFF'});
		$("#content").show().animate({'top':90}, function() {
			$("#content").trigger('complete');
		});
		if(newItem != selectedItem)
		{
			selectedItem = newItem;
			$("#pages").children().hide();
			$("#datepicker").hide();
			$("#buttonset").hide();
			
			switch(selectedItem.data("type"))
			{
				case 'schema':
					$("#schema_container1").show();
					$("#schema_container2").show();
					break;
				case 'energy':
					$("#energy_container").show();
					fetchColumnChartData();
					break;
				case 'line':
					$("#chart_container").show();
					$("#datepicker").show();
					$("#buttonset").show();
					fetchLineChartData("analogChart.php");
					break;
				case 'power':
					$("#chart_container").show();
					$("#datepicker").show();
					$("#buttonset").show();
					fetchLineChartData("powerChart.php");
					break;
			}
		}
	});
}

$(document).ready(function() {
	initSchema();
	
	initToolbar();
	lineChart = new google.visualization.LineChart(document.getElementById('line_chart'));
	barChart = new google.visualization.ColumnChart(document.getElementById('bar_chart'));
});

function initToolbar()
{
	$("#home").button({
		icons: {
				primary: "ui-icon-home"
			}
		}).click(function (){
			$("#logo").animate({'top':'50%','left':'50%'});
			$("#menu").fadeIn();
			$("body").animate({'background-color':'#EEE'});
			$("#content").animate({'top':'100%'},function(){
				$("#content").hide();
			});

	});
	
	$("#back").button({
		icons: {
			primary: "ui-icon-carat-1-w"
		},
		text: false
	}).click(function (){
		currentTime.setTime(currentTime.getTime() - 86400000);
		$("#datepicker").datepicker("setDate", currentTime);
		fetchLineChartData();
	});
	
	$("#date").button().click(function (){
		currentTime =  new Date();
		$("#datepicker").datepicker("setDate", currentTime);
		fetchLineChartData();
	});
	
	$("#forward").button({
		icons: {
			primary: "ui-icon-carat-1-e"
		},
		text: false
	}).click(function (){
		currentTime.setTime(currentTime.getTime() + 86400000);
		$("#datepicker").datepicker("setDate", currentTime);		
		fetchLineChartData();
	});
	
	$("#datepicker").addClass("ui-widget ui-widget-content ui-corner-all").datepicker({
		"onSelect": function(date) {
			currentTime = $.datepicker.parseDate("dd.mm.yy",date);
			fetchLineChartData();
		}
	});
	
	$("#datepicker").datepicker("setDate", currentTime);
	
	$("#buttonset").buttonset();
}

function fetchLineChartData(source) {
	var chartId = selectedItem.data("id");
	
	
  $.ajax({
      url: source,
	  data: {
		date: (currentTime.getFullYear() + "-" + (currentTime.getMonth() + 1) + "-" + currentTime.getDate()),
		id: chartId
	  },
      dataType:"json",
	  timeout: 120000,
      success: function(jsonData) {
		  if($("#content").is(":animated"))
		  {
		  	 $("#content").one('complete', jsonData, drawLineChart);
		  }
		  else
		  {
			 drawLineChart({data:jsonData});
		  }
	  },
	  complete: function(xhr,status) {
		$("#overlay").hide();
	  },
	  beforeSend: function(xhr,settings) {
		$("#overlay").show();
	  }
  });
}

function drawLineChart(object)
{
	// Create our data table out of JSON data loaded from server.
	var data = new google.visualization.DataTable();
	data.addColumn('datetime', 'Time');
	
	var cols = selectedItem.data("columns");
	
	for (var i in cols)
	{
		data.addColumn('number', cols[i]);
	}

	for ( var i = 0; i < object.data.length; i++ ) { 
	    object.data[i][0] = new Date(object.data[i][0]*1000);
	}

	data.addRows(object.data);


	unit = selectedItem.data("unit");

	// Instantiate and draw our chart, passing in some options.
	chart.draw(data,
	{
		width: 800,
		height: 500,
		hAxis: {format: 'H:mm'},
		vAxis: {format: unit, minValue: 0},
		animation: {
			duration: 1000,
  			easing: 'out'
		},
		chartArea: {width: '80%', height: '80%'},
		legend: {position: 'bottom'}
	});
}

function fetchColumnChartData() {

  $.ajax({
      url: "energyChart.php",
	  data: {
		type: "days",
	  },
      dataType:"json",
      success: function(jsonData) {
		  if($("#content").is(":animated"))
		  {
		  	 $("#content").one('complete', jsonData, drawColumnChart);
		  }
		  else
		  {
			 drawColumnChart({data:jsonData});
		  }
	  },
	  complete: function(xhr,status) {
		$("#overlay").hide();
	  },
	  beforeSend: function(xhr,settings) {
		$("#overlay").show();
	  }
  });
}

function drawColumnChart(object)
{
	// Create our data table out of JSON data loaded from server.
	var data = new google.visualization.DataTable(object.data);

	// Instantiate and draw our chart, passing in some options.
	energy.draw(data,
	{
		width: 800,
		height: 500,
		vAxis: {format: '#.## kWh', minValue: 0},
		animation: {
			duration: 1000,
			easing: 'out'
		},
		chartArea: {width: '80%', height: '80%'},
		legend: {position: 'bottom'}
	});
}

function actualValues()
{
	if(selectedItem.data('type') == 'schema')
	{
	  $.ajax({
	      url: "latest.php",
	      dataType:"json",
	      success: function(jsonData) {
		  	printValues(jsonData);
		  }
	  });
	}
	setTimeout(actualValues, 60000);
}

function printValues(jsonData)
{
	for(var i in values)
	{
		var value = values[i];
		$(value.path).text(value.format.replace(/#\.?(#*)/, function(number,fractions) {
			return jsonData[value.frame][value.type].toFixed(fractions.length);
		}));
	}
}

function initSchema()
{
	$.ajax({
	      url: "latest.php",
	      dataType:"json",
	      success: function(jsonData) {
			$("#schema_container1").load("images/schema.svg",function() {
				$("#schema_container2").load("images/kollektoren.svg",function() {
					printValues(jsonData);
					setTimeout(actualValues, 60000);
				});
			});
		  },
		  complete: function(xhr,status) {
			$("#overlay").hide();
		  },
		  beforeSend: function(xhr,settings) {
			$("#overlay").show();
		  }
	});
}

function digitalValue(value)
{
	if(value == 1)
	{
		return 'EIN';
	}
	else
	{
		return 'AUS';
	}
}
