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
		this.login = $("#login");
		this.blLogin = $("#bl_login");
		this.dlLogin = $("#dl_login");
		this.slider = $("#slider");
		this.edit = $("#editChart");
		this.editDialog = $("#edit_chart_dialog");
		
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
		
		this.login.buttonset();
		this.blLogin.button().click(function (){
			toolbar.loginToBl();
		});
		this.dlLogin.button().click(function (){
			toolbar.loginToDl();
		});
		if(menu.loggedin) {
			$("span", toolbar.dlLogin).text("Logout");
		}
		
		this.editDialog.dialog({
			autoOpen: false,
			modal: true,
			minWidth: 500,
			minHeight: 400,
			width: 500,
			height: 400,
	        buttons: {
	          "Speichern": dialog.save
	        },
			open: dialog.open
		});
		
		$( "#activeLines, #availableLines" ).sortable({
		      connectWith: ".editBox"
		}).disableSelection();
		  
		this.edit.button({
			icons: {
				primary: "ui-icon-pencil"
			},
			text: false
		}).click(function() {
			toolbar.editDialog.dialog("open");
		});
		
		
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
			if(menu.loggedin) {
				toolbar.edit.show();
			}
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
		this.edit.hide();
	},
	loginToDl: function() {
		if(!menu.loggedin) {
			$.ajax({
			    type: "POST",
			    url: "login.php",
		        dataType:"json",
			    data: {"user": "admin", "password": $("#login_password").val()},
				success: function(data) {
					$("#login_password").val("");
					if(data.status == "successful") {
						menu.login();
						$("span", toolbar.dlLogin).text("Logout");
						$("#login_message").text("Login erfolgreich!").show().fadeOut(2000);
					}
					else {
						menu.logout();
						$("span", toolbar.dlLogin).text("Login");
						$("#login_message").text("Password fehlerhaft!").show().fadeOut(2000);
					}
				}
			});
		}
		else {
			$.ajax({
			    type: "POST",
			    url: "logout.php",
		        dataType:"json",
				success: function(data) {
					$("#login_password").val("");
					menu.logout();
					$("span", toolbar.dlLogin).text("Login");
					$("#login_message").text("Ausgeloggt!").show().fadeOut(2000);
				}
			});
		}
	},
	loginToBl: function() {
		$("#bl_login_form > input[name=blp]").val($("#login_password").val())
		$("#login_password").val("");
		$("#bl_login_form").submit()
	}
}

var dialog = {
	names: null,
	open: function() {
		$("#availableLines, #activeLines").empty();
		$activeLines = $("#activeLines");
		for(var i in menu.selectedItem.columns.analog) {
			var item = menu.selectedItem.columns.analog[i];
			var $item = $("<li class=\"ui-state-default\">"+item.name+"</li>").data(item);
			$activeLines.append($item);
		}
		
		if(dialog.names != null) {
			dialog.showAvaliable();
		}
		else {
			$.ajax({
			    url: "names.php",
		        dataType:"json",
				success: function(data) {
					dialog.names = data;
					dialog.showAvaliable();
				}
			});
		}
	},
	showAvaliable: function() {
		$availableLines = $("#availableLines");
		for(var i in dialog.names) {
			var item = dialog.names[i];
			if(item.type.indexOf("digital") == -1 && item.type.indexOf("energy") == -1 && !dialog.find(item.frame, item.type)) {
				var $item = $("<li class=\"ui-state-default\">"+item.name+"</li>").data(item);
				$availableLines.append($item);
			}
		}
	},
	find: function(frame, type) {
		for(var i in menu.selectedItem.columns.analog) {
			var item = menu.selectedItem.columns.analog[i];
			if(item.frame == frame && item.type == type) {
				return true;
			}
		}
		return false;
	},
	save: function() {
		var analog = [];
		$("#activeLines > li").each(function(i) {
			analog.push({index: i+1, name: $(this).data("name"), type: $(this).data("type"), frame: $(this).data("frame")});
		});
		var digital = menu.selectedItem.columns.digital;
		$.ajax({
		    type: "POST",
		    url: "editChart.php",
			data: {chartid: menu.selectedItem.id, names: analog.concat(digital)},
	        dataType:"json",
			success: function(data) {
				menu.selectedItem.columns.analog = analog;
				menu.selectedItem["table"] = new Table(menu.selectedItem);
				menu.showContent();
			}
		});
	}
}
