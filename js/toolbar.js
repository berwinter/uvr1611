var toolbar = {
	date: null,
	init: function()
	{
		this.home = $("#home");
		this.backToChart = $("#backToChart");
		this.back = $("#back");
		this.today = $("#date");
		this.forward = $("#forward");
		this.datepicker = $("#datepicker");
		this.buttonset = $("#buttonset");
		this.period = $("#period");
		this.grouping = $("#grouping");
		
		// home button
		this.home.button({
			icons: {
				primary: "ui-icon-home"
			}
		}).click(function (){
			location.hash = "home";
		});
		
		// back button
		this.back.button({
			icons: {
				primary: "ui-icon-carat-1-w"
			},
			text: false
		}).click(function (){
			toolbar.setDate(new Date(toolbar.date.getTime() - 86400000));
		});
		
		// back to chart button
		this.backToChart.button({
			icons: {
				primary: "ui-icon-carat-1-w"
			}
		}).click(function (){
			menu.selectedItem.load();
			toolbar.showDateNavigation();
			toolbar.showPeriod();
			menu.selectedItem.table.getTable().show();
			$("#minmax_chart").hide();
			if(menu.selectedItem.columns.digital.length)
				$("#step_chart").show();
			$("#line_chart").show();
		});
		
		// forward button
		this.forward.button({
			icons: {
				primary: "ui-icon-carat-1-e"
			},
			text: false
		}).click(function (){
			toolbar.setDate(new Date(toolbar.date.getTime() + 86400000));
		});
		
		// today button
		this.today.button().click(function (){
			toolbar.setDate(new Date());
		});
		
		// init datepicker
		this.datepicker.addClass("ui-widget ui-widget-content ui-corner-all").datepicker({
			"onSelect": function(selectedDate){
				toolbar.setDate($.datepicker.parseDate("dd.mm.yy",selectedDate));
			}
		});
		toolbar.setDate(new Date());
		
		// init buttonsets
		this.buttonset.buttonset();
		this.period.buttonset().change(function(){menu.selectedItem.load()});
		this.grouping.buttonset().change(function(){menu.selectedItem.load()});
	},
	setDate: function(newDate)
	{
		if(newDate.getTime() <= (new Date()).getTime())
		{
			this.date = newDate;
			this.datepicker.datepicker("setDate", this.date);
			this.forward.button("enable");
			if(menu.selectedItem)
				menu.selectedItem.load();
		}
		if(newDate.getTime() + 86400000 > (new Date()).getTime())
		{
			this.forward.button("disable");
		}
	},
	hideDateNavigation: function()
	{
		this.datepicker.hide();
		this.buttonset.hide();
		this.period.hide();
		this.grouping.hide();
		this.backToChart.hide();
		this.home.show();
	},
	showDateNavigation: function()
	{
		this.home.show();
		this.datepicker.show();
		this.buttonset.show();
		this.backToChart.hide();
	},
	showPeriod: function()
	{
		this.grouping.hide();
		this.period.show();
	},
	showGrouping: function()
	{
		this.period.hide();
		this.grouping.show();
	},
	getPeriod: function()
	{
		return $("#period input[type='radio']:checked").val();
	},
	getGrouping: function()
	{
		return $("#grouping input[type='radio']:checked").val();
	},
	showBackToChart: function()
	{
		this.hideDateNavigation();
		this.home.hide();
		this.backToChart.show();
	}
}

