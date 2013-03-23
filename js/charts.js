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
		color: ['#3366cc','#dc3912','#ff9900','#109618','#990099']
	},
	zoomed: false,
	init: function()
	{
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
		this.data = new google.visualization.DataTable();
		// add columns
		this.data.addColumn('datetime', 'Time');
		var cols = menu.selectedItem["columns"];
		for (var i in cols) {
			this.data.addColumn('number', cols[i].name);
			table[i] = {min:{value:null},max:{value:null},avg:{value:0}};

		}
		// format date
		for ( var i = 0; i < this.json.length; i++ ) { 
			this.json[i][0] = new Date(this.json[i][0]*1000);
			
			// find min max
			for(var j=1; j < this.json[i].length; j++) {
				if(table[j-1].min.value == null || this.json[i][j] < table[j-1].min.value) {
					table[j-1].min.value = this.json[i][j];
					table[j-1].min.time = this.json[i][0];	
					table[j-1].min.row = i;	
					table[j-1].min.column = j;
				}
				if(table[j-1].max.value == null || this.json[i][j] > table[j-1].max.value) {
					table[j-1].max.value = this.json[i][j];
					table[j-1].max.time = this.json[i][0];
					table[j-1].max.row = i;	
					table[j-1].max.column = j;
				}
				table[j-1].avg.value += this.json[i][j];
			}
		}
		// calc avg
		for (var i in cols) {
			table[i].avg.value /= this.json.length;
		}

		// add data
		this.data.addRows(this.json);	
		// set unit
		this.options.vAxis.format = menu.selectedItem["unit"];		
		this.options.hAxis.format = toolbar.getPeriod() == "day" ? "HH:mm": "dd.MM";
		
		// check if there is data
		if(this.json[0]){
			// set viewbox
			this.options.hAxis.viewWindow = {min:this.json[0][0], max:this.json[this.json.length-1][0]};
			// fill table with information
			menu.selectedItem.table.fill(table, this.options.vAxis.format);
		}
		this.zoomed = false;
		this.chart.draw(this.data, this.options);
	},
	selectHandler: function() {
		if(lineChart.chart.getSelection().length && lineChart.chart.getSelection()[0].row) {
			var row = lineChart.chart.getSelection()[0].row;
			var offset = toolbar.getPeriod() == "day" ? 1800000: 43200000;
			lineChart.options.hAxis.format = toolbar.getPeriod() == "day" ? "HH:mm": "dd.MM HH:mm";
			lineChart.options.hAxis.viewWindow.min = new Date(lineChart.json[row][0].getTime()-offset);
			lineChart.options.hAxis.viewWindow.max = new Date(lineChart.json[row][0].getTime()+offset);
			lineChart.zoomed = true;
			lineChart.chart.draw(lineChart.data, lineChart.options);
		}
	},
	clickHandler: function(e) {
		if(e.targetID == "chartarea" && lineChart.zoomed) {
			lineChart.options.hAxis.format = toolbar.getPeriod() == "day" ? "HH:mm": "dd.MM";
			lineChart.options.hAxis.viewWindow.min = lineChart.json[0][0];
			lineChart.options.hAxis.viewWindow.max = lineChart.json[lineChart.json.length-1][0];
			lineChart.zoomed = false;
			lineChart.chart.draw(lineChart.data, lineChart.options);
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
		legend: {position: 'bottom'}
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
		
		var cols = menu.selectedItem["columns"];
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