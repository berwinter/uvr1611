var lineChart = {
	options: {
		height: 500,
		vAxis: {minValue: 0},
		hAxis: {},
		animation: {
			duration: 1000,
  			easing: 'out'
		},
		chartArea: {width: '80%', height: '80%'},
		legend: {position: 'bottom'},
		colors: ['#3366cc','#dc3912','#ff9900','#109618','#990099','#3366cc','#dc3912','#ff9900','#109618','#990099']
	},
	digitalOptions: {
		vAxis: {minValue: 0, textPosition: 'none'},
		hAxis: {textPosition: 'none'},
		animation: {
			duration: 1000,
  			easing: 'out'
		},
		chartArea: {width: '80%', height: '50%'},
		legend: {position: 'bottom'},
		colors: ['#3366cc','#dc3912','#ff9900','#109618','#990099','#3366cc','#dc3912','#ff9900','#109618','#990099']
	},
	zoomed: false,
	init: function()
	{
		this.digitalChart = new google.visualization.LineChart(document.getElementById('step_chart'));
		this.chart = new google.visualization.LineChart(document.getElementById('line_chart'));
		google.visualization.events.addListener(this.chart, 'click', this.clickHandler);
		google.visualization.events.addListener(this.chart, 'select', this.selectHandler);
	},
	fetch: function()
	{
		var chartId = menu.selectedItem.id;
		$.ajax({
			url: menu.selectedItem["page"],
			data: {
				date: (toolbar.date.getFullYear() + "-" + (toolbar.date.getMonth() + 1) + "-" + toolbar.date.getDate()),
				id: chartId,
				period: toolbar.getPeriod()
			},
			dataType:"json",
			timeout: 120000,
			success: function(jsonData) {
				if($("#content").is(":animated")) {
					$("#content").one('complete', function() {
						lineChart.json = jsonData;
						lineChart.draw(jsonData);
					});
				}
				else {
					lineChart.json = jsonData;
					lineChart.draw();
				}
			},
			complete: function(xhr,status) {
				$("#overlay").hide();
			},
			beforeSend: function(xhr,settings) {
				$("#overlay").show();
			}
		});
	},
	draw: function()
	{
		var table = [];
		this.data = {};
		this.data.analog = new google.visualization.DataTable();
		this.data.digital = new google.visualization.DataTable();
		// add columns
		this.data.analog.addColumn('datetime', 'Time');
		this.data.digital.addColumn('datetime', 'Time');
		var cols = menu.selectedItem["columns"];
		
		this.json.digital = [];
		this.json.analog = [];
		
		for ( var i = 0; i < this.json.length; i++ ) { 
			this.json.digital[i] = [];
			this.json.analog[i] = [];
			this.json.digital[i][0] = new Date(this.json[i][0]*1000);
			this.json.analog[i][0] = new Date(this.json[i][0]*1000);
		}
		
		for (var i in cols.analog) {
			this.data.analog.addColumn('number', cols.analog[i].name);
			var tableRow = {min:{value:null},max:{value:null},avg:{value:0}};
			for (var j = 0; j < this.json.length; j++ ) { 
				var value = this.json[j][cols.analog[i].index];
				this.json.analog[j].push(value);
				if(tableRow.min.value == null || value < tableRow.min.value) {
					tableRow.min.value = value;
					tableRow.min.time = this.json.analog[j][0];	
					tableRow.min.row = j;	
					tableRow.min.column = this.json.analog[j].length-1;
				}
				if(tableRow.max.value == null || value > tableRow.max.value) {
					tableRow.max.value = value;
					tableRow.max.time = this.json.analog[j][0];
					tableRow.max.row = j;	
					tableRow.max.column = this.json.analog[j].length-1;
				}
				tableRow.avg.value += value;
			}
			tableRow.avg.value /= this.json.length;
			table.push(tableRow);
		}
		var rowMarker = [];
		for (var i in cols.digital) {
			this.data.digital.addColumn('number', cols.digital[i].name);
			var lastValue;
			for ( var j = 0; j < this.json.length; j++ ) { 
				var value = this.json[j][cols.digital[i].index];
				this.json.digital[j].push({v:value*0.7+(this.json.digital[j].length-1)*1, f:(value?"EIN":"AUS")});
				if(value != lastValue & j>0) {
					rowMarker[j] = true;
					lastValue = value;
				}
			}
		}		
		// check if there is data
		if(this.json.analog[0] && this.json.analog[0][1] != null){
			$("#line_chart").show();
			// add data
			this.data.analog.addRows(this.json.analog);	
			// set unit
			this.options.vAxis.format = menu.selectedItem["unit"];		
			this.options.hAxis.format = toolbar.getPeriod() == "day" ? "HH:mm": "dd.MM";
			// set viewbox
			this.options.hAxis.viewWindow = {min:this.json.analog[0][0], max:this.json.analog[this.json.analog.length-1][0]};
			// fill table with information
			menu.selectedItem.table.fill(table, this.options.vAxis.format);
			this.chart.draw(this.data.analog, this.options);
		}
		else {
			$("#line_chart").hide();
		}
		if(this.json.digital[0] && this.json.digital[0][1] != null){
			$("#step_chart").show();
			this.data.digital.addRows(this.json.digital);
			for(var i in rowMarker) {
				if(i>0){
					var temp1 = this.json.digital[i];
					var temp2 = this.json.digital[i-1];
					temp2[0] = new Date(temp1[0]-1);
					this.data.digital.addRow(temp2);
				}
			}
			this.data.digital.sort([{column: 0}]);
			this.digitalOptions.hAxis.viewWindow = {min:this.json.digital[0][0], max:this.json.digital[this.json.digital.length-1][0]};
			this.digitalOptions.height = this.json.digital[0].length*40;
			this.digitalOptions.vAxis.gridlines = {count:this.json.digital[0].length};
			this.digitalOptions.vAxis.maxValue = (this.json.digital[0].length-1)*1;
			this.digitalChart.draw(this.data.digital, this.digitalOptions);
		}
		else {
			$("#step_chart").hide();
		}
		
		this.zoomed = false;

	},
	selectHandler: function() {
		if(lineChart.chart.getSelection().length && lineChart.chart.getSelection()[0].row) {
			var row = lineChart.chart.getSelection()[0].row;
			var offset = toolbar.getPeriod() == "day" ? 1800000: 43200000;
			if(lineChart.json.analog[0] && lineChart.json.analog[0][1] != null){
				lineChart.options.hAxis.format = toolbar.getPeriod() == "day" ? "HH:mm": "dd.MM HH:mm";
				lineChart.options.hAxis.viewWindow.min = new Date(lineChart.json.analog[row][0].getTime()-offset);
				lineChart.options.hAxis.viewWindow.max = new Date(lineChart.json.analog[row][0].getTime()+offset);
				lineChart.chart.draw(lineChart.data.analog, lineChart.options);
			}
			if(lineChart.json.digital[0] && lineChart.json.digital[0][1] != null){
				lineChart.digitalOptions.hAxis.viewWindow.min = new Date(lineChart.json.analog[row][0].getTime()-offset);
				lineChart.digitalOptions.hAxis.viewWindow.max = new Date(lineChart.json.analog[row][0].getTime()+offset);
				lineChart.digitalChart.draw(lineChart.data.digital, lineChart.digitalOptions);
			}
			lineChart.zoomed = true;
		}
		else if(lineChart.chart.getSelection().length && !lineChart.chart.getSelection()[0].row)
		{
			var line = lineChart.chart.getSelection()[0].column-1;
			minmaxChart.fetch(line);
			toolbar.showBackToChart();
			menu.selectedItem.table.getTable().hide();
			$("#minmax_chart").show();
			$("#line_chart").hide();
			$("#step_chart").hide();
		}
	},
	clickHandler: function(e) {
		if(e.targetID == "chartarea" && lineChart.zoomed) {
			if(lineChart.json.analog[0] && lineChart.json.analog[0][1] != null){
				lineChart.options.hAxis.format = toolbar.getPeriod() == "day" ? "HH:mm": "dd.MM";
				lineChart.options.hAxis.viewWindow.min = lineChart.json.analog[0][0];
				lineChart.options.hAxis.viewWindow.max = lineChart.json.analog[lineChart.json.analog.length-1][0];
				lineChart.chart.draw(lineChart.data.analog, lineChart.options);
			}
			if(lineChart.json.digital[0] && lineChart.json.digital[0][1] != null){
				lineChart.digitalOptions.hAxis.viewWindow.min = lineChart.json.analog[0][0];
				lineChart.digitalOptions.hAxis.viewWindow.max = lineChart.json.analog[lineChart.json.analog.length-1][0];
				lineChart.digitalChart.draw(lineChart.data.digital, lineChart.digitalOptions);
			}
			lineChart.zoomed = false;
		}
	}
}

var barChart = {
	options: {
		height: 500,
		vAxis: {format: '#.## kWh', minValue: 0},
		animation: {
			duration: 1000,
			easing: 'out'
		},
		chartArea: {width: '80%', height: '80%'},
		legend: {position: 'bottom'},
		colors: ['#3366cc','#dc3912','#ff9900','#109618','#990099','#3366cc','#dc3912','#ff9900','#109618','#990099']
	},
	init: function()
	{
		this.chart = new google.visualization.ColumnChart(document.getElementById('bar_chart'));
	},
	fetch: function()
	{
		var chartId = menu.selectedItem["id"];
		$.ajax({
			url: "energyChart.php",
			data: {
				date: (toolbar.date.getFullYear() + "-" + (toolbar.date.getMonth() + 1) + "-" + toolbar.date.getDate()),
				grouping: toolbar.getGrouping(),
				id: chartId
			},
			dataType:"json",
			success: function(jsonData) {
				if($("#content").is(":animated")) {
					$("#content").one('complete', function(){
						barChart.json = jsonData;
						barChart.draw();
					});
				}
				else {
					barChart.json = jsonData;
					barChart.draw();
				}
			},
			complete: function(xhr,status) {
				$("#overlay").hide();
			},
			beforeSend: function(xhr,settings) {
				$("#overlay").show();
			}
		});
	},
	draw: function()
	{
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Date');
		
		var cols = menu.selectedItem["columns"].analog;
		var table = {};
		
		for (var i in cols)
		{
			data.addColumn('number', cols[i].name);
			table[i] = this.json.statistics[cols[i].frame][cols[i].type];
		}
		
		menu.selectedItem.table.fill(table, this.options.vAxis.format);
		data.addRows(this.json.rows);
		this.chart.draw(data, this.options);
	}
}

var minmaxChart = {
		options: {
			height: 500,
			vAxis: {minValue: 0},
			hAxis: {format: 'dd.MM'},
			animation: {
				duration: 1000,
	  			easing: 'out'
			},
			chartArea: {width: '80%', height: '80%'},
			legend: {position: 'bottom'},
			curveType: 'function'
		},
		init: function()
		{
			this.chart = new google.visualization.LineChart(document.getElementById('minmax_chart'));
		},
		fetch: function(line)
		{		
			$.ajax({
				url: "minmaxChart.php",
				data: {
					date: (toolbar.date.getFullYear() + "-" + (toolbar.date.getMonth() + 1) + "-" + toolbar.date.getDate()),
					type: menu.selectedItem.columns.analog[line].type,
					frame: menu.selectedItem.columns.analog[line].frame
				},
				dataType:"json",
				timeout: 120000,
				success: function(jsonData) {
					if($("#content").is(":animated")) {
						$("#content").one('complete', function() {
							minmaxChart.json = jsonData;
							minmaxChart.draw(line);
						});
					}
					else {
						minmaxChart.json = jsonData;
						minmaxChart.draw(line);
					}
				},
				complete: function(xhr,status) {
					$("#overlay").hide();
				},
				beforeSend: function(xhr,settings) {
					$("#overlay").show();
				}
			});
		},
		draw: function(line)
		{
			var table = [];
			this.data = new google.visualization.DataTable();
			// add columns
			this.data.addColumn('datetime', 'Time');
			this.data.addColumn('number', 'Minimum '+menu.selectedItem.columns.analog[line].name);
			this.data.addColumn('number', 'Maximum '+menu.selectedItem.columns.analog[line].name);
			// format date
			for ( var i = 0; i < this.json.length; i++ ) { 
				this.json[i][0] = new Date(this.json[i][0]*1000);
			}
			
			this.data.addRows(this.json);	
			// set unit
			this.options.vAxis.format = menu.selectedItem["unit"];		
			this.chart.draw(this.data, this.options);
		}
	}
