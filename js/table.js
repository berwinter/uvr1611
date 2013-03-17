function Table(item)
{
	var $table = $("<table><thead></thead><tbody></tbody></table>");
	$table.find('thead').append("<tr><th /><th>Minimum</th><th>Maximum</th><th>Mittelwert</th></tr>");
	$table.addClass("chartinfo");
	
	var data = [];
	for(var i in item.columns) {
		data[i] = {name: item.columns[i], color: lineChart.options.color[i]};
	}
	
	for(var i in data)
	{
		$table.find('tbody').append('<tr><td><svg xmlns="http://www.w3.org/2000/svg" height="15" width="200"><g><text text-anchor="start" x="21" y="12.75" font-family="Arial" font-size="15" stroke="none" stroke-width="0" fill="#222222">'+data[i].name+'</text><rect width="15" height="15" stroke="none" stroke-width="0" fill="'+data[i].color+'"/></g></svg></td><td class="pointer"><div class="value"></div><div class="timestamp"></div></td><td class="pointer"><div class="value"></div><div class="timestamp"></div></td><td><div class="value"></div></td></tr>');
	}
	
	$("td.pointer",$table).click(function() {
		lineChart.chart.setSelection([$(this).data()]);
	});
	
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
	}
	this.getTable = function()
	{
		return $table;
	}
}