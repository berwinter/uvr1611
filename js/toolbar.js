var toolbar = {
	timeInc: 86400000, 
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
		this.login = $("#bl_login_submit");
		this.slider = $("#slider");
		
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth();
		var yyyy = today.getFullYear();
		today = Math.round((new Date(yyyy, mm, dd)).getTime()/60000);
		var maxDate = Math.round((new Date()).getTime()/60000);
		var tooltip = $('<div></div>').css({
		    position: 'absolute',
		    top: 25,
		    left: -10
		}).hide();
		this.slider.slider({
			range: "min",
			min: today,
			max: maxDate,
			value: maxDate,
			step: 1,
			slide: function(e, ui) {
				var hours = ''+Math.floor((ui.value-today) / 60);
				var minutes = ''+(ui.value - today - (hours * 60));

				if(hours.length == 1) hours = '0' + hours;
				if(minutes.length == 1) minutes = '0' + minutes;
				tooltip.text(hours+":"+minutes);
			},
			change: function(e, ui) {
				if(ui.value < maxDate) {
					actualValues.fetchData(ui.value*60);
				}
				else {
					actualValues.fetchData();
				}
			}
		}).find(".ui-slider-handle").append(tooltip).hover(function() {
			tooltip.show()
		}, function() {
			tooltip.hide()
		});
		
		this.login.button();
		
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
			toolbar.setDate(new Date(toolbar.date.getTime() - toolbar.timeInc));
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
			toolbar.setDate(new Date(toolbar.date.getTime() + toolbar.timeInc));
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
		this.period.buttonset().change(function(){
			if(toolbar.getPeriod() == "week") {
				toolbar.timeInc = 7*86400000;
			}
			else {
				toolbar.timeInc = 86400000;
			}
			menu.selectedItem.load();
		});
		this.grouping.buttonset().change(function(){
			if(toolbar.getGrouping() == "months") {
				toolbar.timeInc = 31*86400000;
			}
			else {
				toolbar.timeInc = 86400000;
			}
			menu.selectedItem.load();
		});
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
	showSlider: function()
	{
		this.slider.show();
	},
	hideSlider: function()
	{
		this.slider.hide();
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

