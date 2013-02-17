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
		menu = jsonData.menu;
		var $menu = $("<div></div>");
		var $pages = $("<div><div id=\"chart_container\"><div id=\"line_chart\"></div></div><div id=\"energy_container\"><div id=\"bar_chart\"></div></div></div>");
		
		for (var i in menu)
		{
			var item = menu[i];
			var $item = $('<div class="item"><div class="icon"></div><div>'+item.name+'</div></div>');
			item["item"] = $item;
			item["index"] = i;
			switch(item.type)
			{
				case 'schema':
					$item.find("div.icon").addClass("home");
					var $container = $('<div class="schema"></div>').load("images/"+item.schema);
					$pages.append($container);
					item["container"] = $container;
					break;
				case 'energy':
					$item.find("div.icon").addClass("chart");
					item["container"] = $pages.find("#energy_container");
					item["load"] = fetchBarChartData;
			  		break;
				case 'line':
					$item.find("div.icon").addClass("chart");
					item["container"] = $pages.find("#chart_container");
					item["load"] = fetchLineChartData;
					item["page"] = "analogChart.php"
			  		break;
				case 'power':
					$item.find("div.icon").addClass("chart");
					item["container"] = $pages.find("#chart_container");
					item["load"] = fetchLineChartData;
					item["page"] = "powerChart.php"
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
		
		$menu.find("div.item").click(function() {
			location.hash = $(this).data("index");
		});
		
		$(document).ready(function() {
			$("#menu").append($menu.children());
			$("#pages").append($pages.children());
			lineChart = new google.visualization.LineChart(document.getElementById('line_chart'));
			barChart = new google.visualization.ColumnChart(document.getElementById('bar_chart'));
			handleMenu();
		});
    }
});

$(document).ready(function() {
	actualValues();
	initToolbar();
	
	$(window).on("hashchange", handleMenu);
});

function handleMenu() {
	var id = location.hash.substr(1);
	
	if(id == "home" || menu[id]==null)
	{
		$("#logo").animate({'top':'50%','left':'50%'});
		$("#menu").fadeIn();
		$("body").animate({'background-color':'#EEE'});
		$("#content").animate({'top':'100%'},function(){
			$("#content").hide();
		});
		selectedItem = null;
	}
	else if(menu[id] != selectedItem)
	{
		selectedItem = menu[id];
		if($("#menu").is(':visible'))
		{
			var newItem = menu[id].item;
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
				indicator.animate({'top':top});
			}
			indicator.animate({'left':left}, function() {
				$("#logo").animate({'top':230,'left':230});
				$("#menu").fadeOut();
				$("body").animate({'background-color':'#FFF'});
				$("#content").show().animate({'top':90}, function() {
					$("#content").trigger('complete');
				});
				
				showContent();
			});
		}
		else
		{
			showContent();
		}
	}
}

function showContent()
{
	$("#pages").children().hide();
	$("#datepicker").hide();
	$("#buttonset").hide();
	selectedItem["container"].show();
	if(selectedItem["type"] != "schema")
	{
		$("#datepicker").show();
		$("#buttonset").show();
		selectedItem.load();
	}
}

function initToolbar()
{
	$("#home").button({
		icons: {
				primary: "ui-icon-home"
			}
		}).click(function (){
			location.hash = "home";
	});
	
	$("#back").button({
		icons: {
			primary: "ui-icon-carat-1-w"
		},
		text: false
	}).click(function (){
		currentTime.setTime(currentTime.getTime() - 86400000);
		$("#datepicker").datepicker("setDate", currentTime);
		selectedItem.load();
	});
	
	$("#date").button().click(function (){
		currentTime =  new Date();
		$("#datepicker").datepicker("setDate", currentTime);
		selectedItem.load();
	});
	
	$("#forward").button({
		icons: {
			primary: "ui-icon-carat-1-e"
		},
		text: false
	}).click(function (){
		currentTime.setTime(currentTime.getTime() + 86400000);
		$("#datepicker").datepicker("setDate", currentTime);		
		selectedItem.load();
	});
	
	$("#datepicker").addClass("ui-widget ui-widget-content ui-corner-all").datepicker({
		"onSelect": function(date) {
			currentTime = $.datepicker.parseDate("dd.mm.yy",date);
			selectedItem.load();
		}
	});
	
	$("#datepicker").datepicker("setDate", currentTime);
	
	$("#buttonset").buttonset();
}

function fetchLineChartData() {
  var chartId = selectedItem["id"];
	
	
  $.ajax({
      url: selectedItem["page"],
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
	
	var cols = selectedItem["columns"];
	
	for (var i in cols)
	{
		data.addColumn('number', cols[i]);
	}

	for ( var i = 0; i < object.data.length; i++ ) { 
	    object.data[i][0] = new Date(object.data[i][0]*1000);
	}

	data.addRows(object.data);


	unit = selectedItem["unit"];
	// Instantiate and draw our chart, passing in some options.
	lineChart.draw(data,
	{
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

function fetchBarChartData() {
  var chartId = selectedItem["id"];
  $.ajax({
      url: "energyChart.php",
	  data: {
		date: (currentTime.getFullYear() + "-" + (currentTime.getMonth() + 1) + "-" + currentTime.getDate()),
		id: chartId
	  },
      dataType:"json",
      success: function(jsonData) {
		  if($("#content").is(":animated"))
		  {
		  	 $("#content").one('complete', jsonData, drawBarChart);
		  }
		  else
		  {
			  drawBarChart({data:jsonData});
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

function drawBarChart(object)
{
	// Create our data table out of JSON data loaded from server.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Date');
	
	var cols = selectedItem["columns"];
	
	for (var i in cols)
	{
		data.addColumn('number', cols[i]);
	}
	
	data.addRows(object.data);

	// Instantiate and draw our chart, passing in some options.
	barChart.draw(data,
	{
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
	$.ajax({
	      url: "latest.php",
	      dataType:"json",
	      success: function(jsonData) {
		  	printValues(jsonData);
		  }
	});
	setTimeout(actualValues, 60000);
}

function printValues(jsonData)
{
	for(var i in values)
	{
		var value = values[i];
		$(value.path).text(value.format.replace(/((DIGITAL)\()?#\.?(#*)\)?/, function(number,tmp,modifier,fractions) {
			switch(modifier)
			{
				case "DIGITAL":
					return digitalValue(jsonData[value.frame][value.type]);
				default:
					return jsonData[value.frame][value.type].toFixed(fractions.length);
			}

		}));
	}
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
