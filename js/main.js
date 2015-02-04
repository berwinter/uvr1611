var menu = {
		selectedItem: null,
		items: [],
		init: function()
		{
			$.ajax({
			    url: "menu.php",
			    dataType:"json",
			    success: function(jsonData){
			    	actualValues.values = jsonData.values;
			    	menu.items = jsonData.menu;
					var $menu = $("<div></div>");
					var $pages = $("<div><div id=\"chart_container\"><div id=\"step_chart\"></div><div id=\"line_chart\"></div><div id=\"minmax_chart\"></div></div><div id=\"energy_container\"><div id=\"bar_chart\"></div></div></div>");
					$pages.find("#minmax_chart").hide();
					
					for (var i in menu.items)
					{
						var item = menu.items[i];
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
								item["load"] = barChart.fetch;
								item["table"] = new EnergyTable(item);
						  		break;
							case 'line':
								$item.find("div.icon").addClass("chart");
								item["container"] = $pages.find("#chart_container");
								item["load"] = lineChart.fetch;
								item["page"] = "analogChart.php"
								item["table"] = new Table(item);
						  		break;
							case 'power':
								$item.find("div.icon").addClass("chart");
								item["container"] = $pages.find("#chart_container");
								item["load"] = lineChart.fetch;
								item["page"] = "powerChart.php";
								item["table"] = new Table(item);
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
						menu.display();
						lineChart.init();
						barChart.init();
						minmaxChart.init();
						menu.handle();
					});
			    }
			});
		},
		display: function()
		{
			$("#browser").hide();
			if(!$("div.item").is(":visible")) {
				$("div.item").fadeIn('slow');
			}	
		},
		handle: function()
		{
			var id = location.hash.substr(1);
			if(id == "home" || menu.items[id]==null){
				$("#logo").animate({'top':'50%','left':'50%'});
				$("#menu").fadeIn();
				$("body").animate({'background-color':'#EEE'});
				$("#content").animate({'top':'100%'},function(){
					$("#content").hide();
				});
				menu.selectedItem = null;
			}
			else if(menu.items[id] != menu.selectedItem){
				menu.selectedItem = menu.items[id];
				if($("#menu").is(':visible')){
					var newItem = menu.items[id].item;
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
						
						menu.showContent();
					});
				}
				else
				{
					menu.showContent();
				}
			}
		},
		showContent: function()
		{
			$("#pages > table.chartinfo").detach();
			$("#pages").children().hide();
			menu.selectedItem["container"].show();
			
			switch(menu.selectedItem["type"]) {
				case "schema":
					toolbar.hideDateNavigation();
					actualValues.fetchData();
					break;
				case "energy":
					toolbar.showDateNavigation();
					toolbar.showGrouping();
					menu.selectedItem.load();
					menu.selectedItem.table.getTable().appendTo("#pages");
					break;
				default:
					toolbar.showDateNavigation();
					toolbar.showPeriod();
					menu.selectedItem.load();
					menu.selectedItem.table.getTable().appendTo("#pages");
					break;
			}
		}
	}


	var actualValues = 
	{
		init: function()
		{
			this.fetchData();
			setTimeout(this.timer, 30000);
		},
		fetchData: function()
		{
			$.ajax({
				url: "latest.php",
				dataType:"json",
				success: this.display
			});
		},
		timer: function()
		{
			if(menu.selectedItem && menu.selectedItem["type"] == "schema")
			{
				actualValues.fetchData();
			}
			setTimeout(actualValues.timer, 30000);
		},
		display: function(data)
		{
			for(var i in actualValues.values) {
				var value = actualValues.values[i];
				var text = value.format.replace(/((DIGITAL|MWH|KWH|MISCHER_AUF|MISCHER_ZU|VENTIL|DREHZAHL|ANIMATION|STATUS|KESSEL|HKL|PUFFER)\()?#\.?(#*)\)?/g, function(number,tmp,modifier,fractions) {
					switch(modifier) {
						case "MISCHER_AUF":
							return converter.mixerOn(data[value.frame][value.type]);
						case "MISCHER_ZU":
							return converter.mixerOff(data[value.frame][value.type]);
						case "VENTIL":
							return converter.valve(data[value.frame][value.type]);
						case "DIGITAL":
							return converter.digital(data[value.frame][value.type]);
						case "MWH":
							return converter.mwh(data[value.frame][value.type]);
						case "KWH":
							return converter.kwh(data[value.frame][value.type]).toFixed(fractions.length);
						case "DREHZAHL":
							return converter.speed(data[value.frame][value.type]);
						case "ANIMATION":
							for(var i in $(value.path))
							{
								if(data[value.frame][value.type] == 1)
								{
									$(value.path)[i].beginElement();
								}
								else
								{
									$(value.path)[i].endElement();	
								}
							}
							return null;
						case "KESSEL":
							return converter.kessel(data[value.frame][value.type]);
						case "HKL":
							return converter.hkl(data[value.frame][value.type]);
						case "PUFFER":
							return converter.puffer(data[value.frame][value.type]);
						default:
							try {
								return data[value.frame][value.type].toFixed(fractions.length);
							}
							catch (e) {
								return data[value.frame][value.type];
							}
					}

				});
				
				if(text != null)
				{
					$(value.path).text(text);
				}
			}
			$("#time").text(data["time"]);
		}
	}

	var converter = {
		digital: function(value)
		{
			if(value == 1) {
				return 'EIN';
			}
			else {
				return 'AUS';
			}
		},
		mwh: function(value)
		{
			return Math.floor(value/1000);
		},
		kwh: function(value)
		{
			return value%1000;
		},
		mixerOn: function(value)
		{
			if(value == 1) {
				return 'AUF';
			}
			return '';
		},
		mixerOff: function(value)
		{
			if(value == 1) {
				return 'ZU';
			}
			return '';
		},
		speed: function(value)
		{
			return (value/30*100).toFixed()+"%";
		},
		valve: function(value)
		{
			if(value == 1) {
				return 'OFFEN';
			}
			else {
				return 'ZU';
			}
		},
		kessel: function(value)
		{
			if(value == 1)
				return 'Aus';
			else if (value == 4)
				return 'Zuenden';
			else if (value == 5)
				return 'LB';
			else if (value == 7)
				return 'Ausbrand';
			else return value;
		},
		hkl: function(value)
		{
			if(value == 1)
				return 'Heizen';
			else if (value == 2)
				return 'Absenken';
			else if (value == 3)
				return 'Abgesenkt';
			else if (value == 4)
				return 'Aus';
			else return value;
		},
		puffer: function(value)
		{
			if(value == 2)
				return 'OK';
			else if (value == 3)
				return 'LADEN';
			else if (value == 4)
				return 'HAND';
			else if (value == 0)
				return 'ANFORDERUNG';
			else return value;
		}
	}

	google.load('visualization', '1', {'packages':['corechart']});
	menu.init();

	$(document).ready(function() {
		actualValues.init();
		toolbar.init();
		
		$(window).on("hashchange", menu.handle);
	});
