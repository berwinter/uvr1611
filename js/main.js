jQuery(function($){
	$.datepicker.regional['de'] = {clearText: 'löschen', clearStatus: 'aktuelles Datum löschen',
	    closeText: 'schließen', closeStatus: 'ohne Änderungen schließen',
	    prevText: '<zurück', prevStatus: 'letzten Monat zeigen',
	    nextText: 'Vor>', nextStatus: 'nächsten Monat zeigen',
	    currentText: 'heute', currentStatus: '',
	    monthNames: ['Januar','Februar','März','April','Mai','Juni',
	    'Juli','August','September','Oktober','November','Dezember'],
	    monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
	    'Jul','Aug','Sep','Okt','Nov','Dez'],
	    monthStatus: 'anderen Monat anzeigen', yearStatus: 'anderes Jahr anzeigen',
	    weekHeader: 'Wo', weekStatus: 'Woche des Monats',
	    dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
	    dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
	    dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
	    dayStatus: 'Setze DD als ersten Wochentag', dateStatus: 'Wähle D, M d',
	    dateFormat: 'dd.mm.yy', firstDay: 1, 
	    initStatus: 'Wähle ein Datum', isRTL: false};
	$.datepicker.setDefaults($.datepicker.regional['de']);
});

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
		$("#browser").hide();
		if(!$("div.item").is(":visible"))
		{
			$("div.item").fadeIn('slow');
		}	
	}
	else
	{		
		$("#browser a").click(function() {
			$("#browser").hide();
			if(!$("div.item").is(":visible"))
			{
				$("div.item").fadeIn('slow');
			}
			
		});
	}
}

$(document).ready(function() {	
	checkBrowser();
	
	$.ajax({
	      url: "menu.php",
	      dataType:"json",
	      success: function(jsonData) {

	    	  values = jsonData.values;
	    	  
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
	    		  $("#menu").append($item);
	    	  }
	    	
	    	if(!$("#browser").is(":visible"))
	    	{
	    		$("div.item").fadeIn('slow');
	    	}
	    	
			// menu hover effect
			$("div.item").hover(function() {
				$(this).addClass("hover");
			},
			function() {
				$(this).removeClass("hover");
			});
	
			$("div.item").click(function() {
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
			});
	     }
	});
	initSchema();
	
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
	
	initChart();
});

function initChart() {
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
	
	chart = new google.visualization.LineChart(document.getElementById('chart_div'));
	energy = new google.visualization.ColumnChart(document.getElementById('energy_chart_div'));
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
	$("#roehren_temp > tspan").text(jsonData.analog15 + ' \u00B0C');
	$("#flach_temp > tspan").text(jsonData.analog16 + ' \u00B0C');
	$("#solarrl_temp > tspan").text(jsonData.analog14 + ' \u00B0C');
	$("#solarvl_temp > tspan").text(jsonData.analog13 + ' \u00B0C');
	$("#fb_pump > tspan").text(digitalValue(jsonData.digital2));
	$("#hz_pump > tspan").text(digitalValue(jsonData.digital1));
	$("#ww_pump > tspan").text(digitalValue(jsonData.digital6));
	$("#flach_pumpe > tspan").text(digitalValue(jsonData.digital11));
	$("#roehren_pumpe > tspan").text(digitalValue(jsonData.digital10));
	$("#lade_pumpe > tspan").text(digitalValue(jsonData.digital7));
	$("#flach_info > tspan:eq(1)").text("Gesamt: " + jsonData.energy1 +"kWh");
	$("#flach_info > tspan:eq(2)").text("Aktuell: " + jsonData.power1 +"kW");
	$("#roehren_info > tspan:eq(1)").text("Gesamt: " + jsonData.energy2 +"kWh");
	$("#roehren_info > tspan:eq(2)").text("Aktuell: " + jsonData.power2 +"kW");
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
