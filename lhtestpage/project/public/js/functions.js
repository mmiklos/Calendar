$(function(){
	$('.switch').click(function(){
		var monthNumber = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
		var current = monthNumber.indexOf($(".current").attr('id'));//Current month index on array (1 less than standard month number)
		var prev = false;
		var year = parseInt(document.getElementById('year').innerHTML);
		if(this.innerHTML=='Prev'){
			if(current==0){
				current=12;
				year = year - 1;
			}
			prev = true;
			var new_month = current-1;
		}else{
			if(current==11){
				current=-1;
				year = year + 1;
			}
			var new_month = current+1;
		}

		var data = {
			"prev" : prev,
			"month" : new_month,
			"month_name" : monthNumber[new_month],
			"year" : year,
		};
		$("form#"+this.innerHTML.toLowerCase()).submit(handlesubmit(data));
	});
	
	$('.today').click(function(){
		var monthNumber = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
		var date = new Date();
		var new_month = date.getMonth();
		var year = date.getFullYear();
		var prev = false;

		var data = {
			"prev" : prev,
			"month" : new_month,
			"month_name" : monthNumber[new_month],
			"year" : year,
		};
		$("form#"+this.innerHTML.toLowerCase()).submit(handlesubmit(data));
	});

});

function submit(data){
	xhr = new XMLHttpRequest;
	xhr.open('POST', 'changeMonth.php', true);
	xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	xhr.send();

	xhr.onreadystatechange(create_year(data));
}


function handlesubmit(data){
	//postSuccess(data);
		$.ajax({
			type: 'POST',
			url: 'changeMonth.php',
			data: data,
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			beforeSend: handle_month_change(data),
		});
	read_json(data.month, data.year);
	test_json_obj(data.month, data.year);
}

function postSuccess(data, textStatus, jqXHR){
	
}

function postError(jqXHR, textStatus, errorThrown){
	
}

function create_year(data){
	if(data===undefined){
		var dateObj = new Date();
		dateObj.setDate(1);
	}else{
		var dateObj = new Date(data.year, data.month, 1);
	}
	var first_day = dateObj.getDay();
	var month = dateObj.getMonth();
	var year = dateObj.getFullYear();
	dateObj.setMonth(dateObj.getMonth()+1);
	dateObj.setDate(0);
	var daysInMonth = dateObj.getDate();

		var html = "";
	if(!document.getElementById('body')){
		html += '<div id="body">';
		html += build_menu();
		html +=	'<h2>';
		html +=		"<nav id='navigate'>The Gamer's Calendar";
		html +=		"<a href='#' class='switch' alt='alt'>Next</a>";
		html +=		"<span>&nbsp;|&nbsp;</span>";
		html +=		"<a href='#' class='today' alt='alt'>Today</a>";
		html +=		"<span>&nbsp;|&nbsp;</span>";
		html +=		"<a href='#' class='switch' alt='alt'>Prev</a>";
		html +=		"</nav>";
		html +=	'</h2>';

		html += '<div id="wrap">';

		html += create_form();

		$('body').html(html);
	}

	create_week(first_day, daysInMonth, month, year);
	if(!document.getElementById('body')){
		var endhtml = "</div></div>";
		document.getElementsByTagName('body').innerHTML = document.getElementsByTagName('body').innerHTML + endhtml;
	}
	read_json(month, year);
}

function create_week(firstday, numdays, month, year){
	create_html(firstday, numdays, month, year);
}

function create_html(first_day_of_month, days_in_month, month, year){
		var day = new Array();
		day = build_days_in_month(first_day_of_month, days_in_month);
		//number of weeks included in month
		var num_weeks = Math.ceil((day.length) / 7);
		//HTML SECTION
			var html = build_month(month, year);
			html += "<div id='weekday_names'>";
			for(var i=0;i<=7;i++){
				html += weekday_names(i);
			}
			html += weeks_tab(day, month);
		

		html += "</div></section>";
		clear_html(document.getElementById('wrap').innerHTML);
		document.getElementById('wrap').innerHTML = html;
		
		today();
		hide_unwanted();
		resize();
		build_menu_width();
		expand_week();
		expand_all();
		//END HTML SECTION
}

function build_days_in_month(first_day_of_month, days_in_month){
		var day = [];
		
		var last_day_of_month = ((days_in_month % 7) + first_day_of_month);//determin the last weekday of the month
		last_day_of_month = (last_day_of_month==0) ? 7 : last_day_of_month;//helps instanced of february 2015

		last_day_of_month = ((last_day_of_month > 7) ? last_day_of_month - 8: last_day_of_month-1);
		var calshift = first_day_of_month;

		//days in week before month starts	
		if(first_day_of_month != 0){
			while(first_day_of_month != 0){
				day[calshift-first_day_of_month] = [];
				day[calshift-first_day_of_month]['day'] = 0-first_day_of_month;
				day[calshift-first_day_of_month]['in_month'] = false;	
				first_day_of_month--;			
			}
		}

		//days in month
		for(var i=0; i<days_in_month; i++){
			day[i+calshift] = [];
			day[i+calshift]['day'] = i;
			day[i+calshift]['in_month'] = true;

		}

		//days in week after month ends
		if(last_day_of_month != 6){
			while(last_day_of_month != 6){
				day[days_in_month+(6-last_day_of_month)+calshift-1] = [];
				day[days_in_month+(6-last_day_of_month)+calshift-1]['day'] = days_in_month-1+(6-last_day_of_month);
				day[days_in_month+(6-last_day_of_month)+calshift-1]['in_month'] = false;
				last_day_of_month++;
			}
		}

		for(var i = day.length; i<=42; i++){
			day[i] = [];
			day[i]['day'] = i-(6-last_day_of_month+calshift);
			day[i]['in_month'] = false;
		}

		return day;
	}

function build_month(month, year){
	var m_names = ["January","February","March","April","May","June","July","August","September","October","November","December"];
	var html = "<section id='"+ m_names[month] + "' ";
	html +=	"class='current'>";
	html += "<br /><h1>";
	html +=		m_names[month]+" ";
	html += 	"<span id='year'>"+year+"</span>";
	html += "</h1>";
			
	return html;
	}
function weeks_tab(day, month){
	var week = "<div id='monthdays'>";
		
	for(var i=1;i<=6;i++){
		week += "<div id='week_"+i+"' class='week'><div class='weekday start_of_week'><p>Week "+i+"</p></div>";		
		for(var key in day){
			numKey = ((i-1)*7)+parseInt(key);			
			var current = day[numKey]['day']+1;
			week += create_day(day[numKey], current, month);
			if((numKey+1)%7==0){
				break;
			}
		}
		week += "</div>";//end week div
	}
	week += "</div>";//end monthdays div

	return week;
}
function weekday_names(weekday_num){
	var weekdays = ["All", "Sun", "Mon", "Tue", "Wed", "Thur", "Fri", "Sat"];
	var day_of_week= weekdays[weekday_num];
	var html = "<div class='weekday_names ";
	html += (weekdays[weekday_num] == 'All') ? "start_of_week" : "";
	html +=				"' id='"+day_of_week+"'>" +
						"<p>"+day_of_week+"</p>" +
					"</div>";
	return html;
}
function create_day(value, current, month){
	var weekday = "<div class='weekday ";	
	weekday +=		((current%7==5) ? "end_of_week " : "");
	weekday +=		(value['in_month']==false) ? "not_in_month' " : "' "; 
	weekday +=	" 	id='day_"+value['day']+"'>";
	weekday += "	<p>"+(value['day']+1)+"</p>";
	weekday += "	<div class='event-entry'></div>";
	weekday += "</div>";
	//echo $weekday;
	return weekday;
}

function clear_html(e){
	e = "";
}

function today(){
	dateObj = new Date();
	if($("#year").html() == dateObj.getFullYear()){
		$("#day_"+(dateObj.getDate()-1)+"_"+dateObj.getMonth()).addClass("today");
	}
}

function create_form(){
	var html="<form method='post' action='changeMonth.php' id='prev'>" +
				"<input type='hidden' value='prev' name='prev' />" +
			"</form>" + 
		"<form method='post' action='changeMonth.php' id='next'>" +
			"</form>" +
		"<form method='post' action='changeMonth.php' id='today'>" +
			"</form>";
	return html;
}

function hide_unwanted(){
	for(var i=5; i<7; i++){
		if($("div#week_"+i+".week").children(".not_in_month").length == 7){
			$("div#week_"+i+".week").hide();
		}
	}
}

function read_json(month, year, rank){
	rank = rank || 'National';
	var m_names = ["January","February","March","April","May","June","July","August","September","October","November","December"];
	$('div.event-entry').each(function(){
		$(this).html("");
	});
	$('#listed-events').empty();
	var background = 0;
	if($.getJSON("json/"+year+"_"+(month+1)+"_"+window.country_code+"_"+rank+".json")){
		var data = $.getJSON("json/"+year+"_"+(month+1)+"_"+window.country_code+"_"+rank+".json", function(data){
			//Just here for testing searching through the file
			for ( var day in data ){
				var newEvent = data[day];
				for( var questions in newEvent ){//days got in this loop will represent days with events
					id_questions = questions.replace(/ /g,"_");
			var buildEntry = "<div class='calendar_entry' >";
			var buildEvent = "<section class='title' id='"+id_questions+"_"+day+"'><h4 class='";
				buildEvent += (background % 2 == 0) ? "_e'" : "_o'"; 
				buildEvent += ">"+questions+"&nbsp;&raquo;</h4>"+
												"<div class='details'></div>";
				$('#listed-events').append(buildEvent);
					console.log(day)
					var ele = $("#day_"+(day-1)+" div.event-entry");
					//console.log(questions);
					buildEntry += "<p id='"+day+"_";
					var x = newEvent[questions];
					if(typeof x != "string"){
						console.log(x);
						for( var whereOptions in x ){
							url = x[whereOptions];
							if(whereOptions == 'When'){
								var addDate = "<span class='h-time'>"+m_names[month]+", "+url+"</span>";
								$('#'+id_questions+'_'+day+' h4').append(addDate);
								}
							if(whereOptions == "url"){
								buildEntry += "<a href='#' alt=''>"+questions+"</a></p>";/*+url+" target='_blank'*/////////////reedit
							}
							if( typeof url != 'string'){
								for( var games in url){
									gamelist = url[games];
									if( typeof gamelist != 'string'){
										for( var titles in gamelist){
											newtitles = gamelist[titles];
											for( var list in newtitles){
												//console.log(whereOptions + ": "+games);
												//console.log(titles + ": " + list );
												img = newtitles[list];
												//console.log(img);//for grabbing images
											}
										}
									}								
								}
							}else{
								if(whereOptions == 'Who'){
									id=url.replace(/ /g,"_");
									buildEntry += id+"'>";
									eventDetail = "<p class='odd company'><strong>Sponsor</strong>: "+url+"</p>"
								}else if(whereOptions == 'When'){
									eventDetail += "<p class='even timendate'><strong>Begins</strong>: "+m_names[month]+" "+url+"</p>";
								}else if(whereOptions == 'url'){
									eventDetail += "<p class='odd site-link'><strong>Link</strong>: <a href='"+url+"'alt=''>"+url+"</a></p>";
								}else if(whereOptions == 'gps'){
									eventDetail += "<p class='even event-address'><strong>Address</strong>: "+url+"</p>";
								}
								console.log(whereOptions + ": "+url);//working string
							}
						}
					}
					buildEntry += "</div>";//
					buildEvent += "</div></section>";	
					//console.log(ele, buildEntry);
					ele.append(buildEntry);
					$('#'+id_questions+"_"+day+' div.details').append(eventDetail);
					console.log($('#'+questions+"_"+day+' div.details'), '#'+questions+"_"+day+' div.datails');
					background++;

					/************possible split bellow to another function *************/
					//console.log('p#'+day+"_"+id);//////////////////////////////////////
					$("div.details").each(function(){
						$(this).hide();
					});

					$('p#'+day+"_"+id).click(function(){
						var name = $(this).prop('innerText');
						name = name.replace(/ /g,"_");
						var date = $(this).attr('id').split(/_(.+)?/)[0];
						//console.log($('section#'+name+"_"+date), 'section#'+name+"_"+date);
						if($('section#'+name+"_"+date).children('div.details').is(":hidden")){
							$('section#'+name+"_"+date).children('div.details').show('fast');
						}else{
							$('section#'+name+"_"+date).children('div.details').hide('fast');
						}
					});
					

					

					/****************************end split**************************/
				}

					
			}
			$("section.title").click(function(){
				if($(this).children('.details').is(":visible")){
					$(this).children('.details').hide("fast");
				}else if($(this).children('.details').is(":hidden")){
					$(this).children('.details').show("fast");
				}
			});	

		});
	}
}
/********************************************************************************************************************/
/********************************************************************************************************************/
/********************************************************************************************************************/
/* A new function needs to be made which, instead of like create_year, swaps classes and id's on different elements */
/***********************************************function below*******************************************************/
/********************************************************************************************************************/
/********************************************************************************************************************/

function handle_month_change(info){
	console.log(info.month, info.year, info.month_name);

	var m_names = ["January","February","March","April","May","June","July","August","September","October","November","December"];
	$(".current").attr('id', m_names[info.month]);

	var dateObj = new Date(info.year, info.month, 1);
	var first_day_of_month = dateObj.getDay();//returns the day of the week for the first day of the month
	var month = dateObj.getMonth();//numeric representation of month (0=january)
	var year = dateObj.getFullYear();//four digit representation of the year
		dateObj.setMonth(dateObj.getMonth()+1);//increases the month by 1
		dateObj.setDate(0);//sets the day to the last day of the previous month
	var daysInMonth = dateObj.getDate();//returns the number for the last day of the month
	console.log(first_day_of_month, daysInMonth, first_day_of_month + daysInMonth)
	
	if((first_day_of_month + daysInMonth) > 35){
		$('#week_6').show();
	}else{
		$('#week_6').hide();
	}
	if((first_day_of_month + daysInMonth) <= 28){
		$('#week_5').hide();
	}else{
		$('#week_5').show();
	}

	var day = new Array();
		day = build_days_in_month(first_day_of_month, daysInMonth);

	$("#"+m_names[info.month-1]).attr('id', m_names[info.month]);
	$(".current > h1").prop('innerHTML', m_names[info.month]+ " " +
												"<span id='year'>"+info.year+"</span>");	
		var i = 0;
		$('.weekday').each(function(data){
			$(this).removeClass();
			$(this).addClass('weekday');
			var addCount = Math.floor(i/8);		
			if(data % 8 == 0){
				$(this).addClass('start_of_week');
			}else{
				if(day[i]['in_month'] == false){
					$(this).addClass('not_in_month');
				}
				$(this).attr('id', 'day_'+day[i]['day']);
				$(this).children('p').html(day[i+1]['day'])
				i++;
			}
		});


}




function build_events(){
	html = "<div id='events-pane'>"+
				"<h1> Events! </h1>"+
				"<div id='listed-events'>"+
				"</div>"+
			"</div>";

	$('body').append(html);
	build_footer();
}

function expand_week(){
	$('div.weekday.start_of_week').click(function(){
		$(this).nextAll().each(function(){
			var listed_event = $(this).children('div.event-entry').children('div.calendar_entry');
			if(listed_event.length != 0){
				listed_event.each(function(){
					$(this).children('p').trigger('click');
				});
			}
		});
	});
}

function expand_all(){
	$('div#all').click(function(){
		var all_events = $('div.weekday').children('div.event-entry').children('div.calendar_entry');
		if(all_events.length != 0){
			all_events.each(function(){
				$(this).children('p').trigger('click');
			});
		}
	});
}

function build_footer(){
	var html = "<footer id='footnote' >"+
					"<ul id='top-list'>"+
						"<li>footer_1</li>"+
						"<li>footer_2</li>"+
						"<li>footer_3</li>"+
						"<li>footer_4</li>"+
						"<li>footer_5</li>"+
					"</ul>"+
					"<ul id='bottom-list'>"+
						"<li>footer_6</li>"+
						"<li>footer_7</li>"+
						"<li>footer_8</li>"+
					"</ul>"+
				"</footer>";
	$('body').append(html);


}

function b_geoloc(){
	var x = $('body');

	  if (navigator.geolocation){
	  	  	navigator.geolocation.getCurrentPosition(reverse_geo, showError);
	    }
	  else{
	  		x.append("Geolocation is not supported by this browser.");
		}
}
function showPosition(position)
 {
 	$('body').append("Latitude: " + position.coords.latitude + 
 	"<br>Longitude: " + position.coords.longitude); 
 }

function showError(error)
  {
  	x=$('body')
  switch(error.code) 
    {
    case error.PERMISSION_DENIED:
      x.append("User denied the request for Geolocation.");
      break;
    case error.POSITION_UNAVAILABLE:
      x.append("Location information is unavailable.");
      break;
    case error.TIMEOUT:
      x.append("The request to get user location timed out.");
      break;
    case error.UNKNOWN_ERROR:
      x.append("An unknown error occurred.");
      break;
    }
  }
  function reverse_geo(position){
  	//var address_string = "https://maps.googleapis.com/maps/api/geocode/json?latlng="+position.coords.latitude+","+position.coords.longitude+"&sensor=false&result_type=country|postal_code";
  	//$.getJSON(address_string, function(data){
  	//	console.log(data);
  	//});
	 var loc = ['unitedstatesofamerica', '85008'];
	console.log(loc);
  }

function get_country(cc){
    country_code = cc;
} var country_code = "";
