var toolbar = {
	date: new Date(),
	init: function()
	{
		this.home = $("#home");
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
		this.datepicker.datepicker("setDate", this.date);
		
		// init buttonsets
		this.buttonset.buttonset();
		this.period.buttonset().change(function(){menu.selectedItem.load()});
		this.grouping.buttonset().change(function(){menu.selectedItem.load()});
	},
	setDate: function(newDate)
	{
		this.date = newDate;
		this.datepicker.datepicker("setDate", this.date);
		menu.selectedItem.load();
	},
	hideDateNavigation: function()
	{
		this.datepicker.hide();
		this.buttonset.hide();
		this.period.hide();
		this.grouping.hide();
	},
	showDateNavigation: function()
	{
		this.datepicker.show();
		this.buttonset.show();
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
	}
}

