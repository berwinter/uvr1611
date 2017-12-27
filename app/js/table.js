function Table(item)
{
	var $table = $("<table><thead></thead><tbody></tbody></table>");
	$table.find('thead').append("<tr><th /><th>Minimum</th><th>Maximum</th><th>Mittelwert</th></tr>");
	$table.addClass("chartinfo");
		
	var data = [];
	for(var i in item.columns.analog) {
		data[i] = {name: item.columns.analog[i].name, color: lineChart.options.colors[i]};
	}
	
	for(var i in data) {
		$table.find('tbody').append('<tr><td><svg xmlns="http://www.w3.org/2000/svg" height="15" width="200"><g><text text-anchor="start" x="21" y="12.75" font-family="Arial" font-size="15" stroke="none" stroke-width="0" fill="#222222">'+data[i].name+'</text><rect width="15" height="15" stroke="none" stroke-width="0" fill="'+data[i].color+'"/></g></svg></td><td class="pointer"><div class="value"></div><div class="timestamp"></div></td><td class="pointer"><div class="value"></div><div class="timestamp"></div></td><td><div class="value"></div></td></tr>');
	}
	
	$("td.pointer",$table).click(function() {
		lineChart.chart.setSelection([$(this).data()]);
		$('#container').scrollTop(0);
	});
	
	$("tbody tr td:nth-child(1)",$table).click(function() {
		var line = $("tbody tr",$table).index($(this).parent());;
		minmaxChart.fetch(line);
		toolbar.showBackToChart();
		menu.selectedItem.table.getTable().hide();
		$("#minmax_chart").show();
		$("#line_chart").hide();
	});

	if(item.columns.digital.length > 0) {
		var $digitalTable = $("<table><thead></thead><tbody></tbody></table>");
		$digitalTable.find('thead').append("<tr><th /><th>Laufzeit</th><th>Ein/Aus</th></tr>");
		$digitalTable.addClass("chartinfo");
		var data = [];
		for(var i in item.columns.digital) {
			data[i] = {name: item.columns.digital[i].name, color: lineChart.digitalOptions.colors[i]};
		}
	
		for(var i in data) {
			$digitalTable.find('tbody').append('<tr><td><svg xmlns="http://www.w3.org/2000/svg" height="15" width="200"><g><text text-anchor="start" x="21" y="12.75" font-family="Arial" font-size="15" stroke="none" stroke-width="0" fill="#222222">'+data[i].name+'</text><rect width="15" height="15" stroke="none" stroke-width="0" fill="'+data[i].color+'"/></g></svg></td><td><div class="value"></div></td><td><div class="value"></div></td></tr>');
		}
	}
	
	this.fill = function(data, vFormat)
	{
		var dateFormatter = new google.visualization.DateFormat({pattern: "dd.MM"});
		var timeFormatter = new google.visualization.DateFormat({pattern: "HH:mm"});
		var numberFormatter = new google.visualization.NumberFormat({pattern: vFormat});
		$table.find("tbody > tr").each(function(index) {
			$(this).find("td:eq(1) > div.value").text(numberFormatter.formatValue(data[index].min.value));
			$(this).find("td:eq(1) > div.timestamp").text("am "+dateFormatter.formatValue(data[index].min.time)+
														  " um "+ timeFormatter.formatValue(data[index].min.time));
			$(this).find("td:eq(1)").data({row: data[index].min.row, column: data[index].min.column});
			$(this).find("td:eq(2) > div.value").text(numberFormatter.formatValue(data[index].max.value));
			$(this).find("td:eq(2) > div.timestamp").text("am "+dateFormatter.formatValue(data[index].max.time)+
			                                              " um "+ timeFormatter.formatValue(data[index].max.time));
			$(this).find("td:eq(2)").data({row: data[index].max.row, column: data[index].max.column});
			$(this).find("td:eq(3) > div.value").text(numberFormatter.formatValue(data[index].avg.value));
		});
		
		// fill digital table
		$.ajax({
			url: "digitalStats.php",
			data: {
				date: (toolbar.date.getFullYear() + "-" + (toolbar.date.getMonth() + 1) + "-" + toolbar.date.getDate()),
				id: menu.selectedItem.id,
				period: toolbar.getPeriod()
			},
			dataType: "json",
			success: function(jsonData) {
				$digitalTable.find("tbody > tr").each(function(index) {
					var frame = menu.selectedItem.columns.digital[index].frame;
					var type = menu.selectedItem.columns.digital[index].type;
					var time = Math.floor(jsonData[frame][type]["time"]/3600)+"h "+Math.floor((jsonData[frame][type]["time"]%3600)/60)+"min "+(jsonData[frame][type]["time"]%60)+ "sec";
					$(this).find("td:eq(1) > div.value").text(time);
					$(this).find("td:eq(2) > div.value").text(jsonData[frame][type]["count"]);
				});
			}
		});
	}
	this.getTable = function()
	{
		return $table.add($digitalTable);
	}
}
function EnergyTable(item)
{
	var $table = $("<table><thead></thead><tbody></tbody></table>");
	$table.addClass("chartinfo");
	
	var data = [];
	for(var i in item.columns.analog) {
		data[i] = {name: item.columns.analog[i].name, color: barChart.options.colors[i]};
	}
	
	var $head = $("<tr><th/></tr>");
	for(var i in data)
	{
		$head.append('<th><svg xmlns="http://www.w3.org/2000/svg" height="15" width="200"><g><text text-anchor="start" x="21" y="12.75" font-family="Arial" font-size="15" stroke="none" stroke-width="0" fill="#222222">'+data[i].name+'</text><rect width="15" height="15" stroke="none" stroke-width="0" fill="'+data[i].color+'"/></g></svg></th>');
	}
	$table.find('thead').append($head);
	
	this.fill = function(data, vFormat)
	{
		var numberFormatter = new google.visualization.NumberFormat({pattern: vFormat});
		for(var i in data)
		{
			$table.find('tbody > tr:eq(0) > td > div.value:eq('+i+')').text(numberFormatter.formatValue(data[i].max));
			$table.find('tbody > tr:eq(1) > td > div.value:eq('+i+')').text(numberFormatter.formatValue(data[i].avg));
			if(data[i].summer)
				$table.find('tbody > tr:eq(2) > td > div.value:eq('+i+')').text(numberFormatter.formatValue(data[i].summer.avg));
			if(data[i].winter)
				$table.find('tbody > tr:eq(3) > td > div.value:eq('+i+')').text(numberFormatter.formatValue(data[i].winter.avg));
			$table.find('tbody > tr:eq(4) > td > div.value:eq('+i+')').text(numberFormatter.formatValue(data[i].sum));
			if(data[i].summer)
				$table.find('tbody > tr:eq(5) > td > div.value:eq('+i+')').text(numberFormatter.formatValue(data[i].summer.sum));
			if(data[i].winter)
				$table.find('tbody > tr:eq(6) > td > div.value:eq('+i+')').text(numberFormatter.formatValue(data[i].winter.sum));
		}
	}
	this.getTable = function()
	{
		return $table;
	}
	this.getRow = function(name, columns)
	{
		var $row = $("<tr></tr>");
		$row.append('<td class="header">'+name+'</td>');
		for(var i=0;i<columns;i++)
		{
			$row.append('<td><div class="value"></div><div class="timestamp"></div></td>');
		}
		return $row;
	}
	$table.find('tbody').append(this.getRow('Bester Tag',data.length));
	$table.find('tbody').append(this.getRow('Mittlerer Tagesertrag',data.length));
	$table.find('tbody').append(this.getRow('Tagesertrag Sommer',data.length));
	$table.find('tbody').append(this.getRow('Tagesertrag Winter',data.length));
	$table.find('tbody').append(this.getRow('Gesamtertrag',data.length));
	$table.find('tbody').append(this.getRow('Gesamtertrag Sommer',data.length));
	$table.find('tbody').append(this.getRow('Gesamtertrag Winter',data.length));
}