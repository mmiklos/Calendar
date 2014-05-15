function construct_datetime_string(year, month, day, hour, minute){
	var string = year + "-" + month + "-" + day + " " + hour + ":" + minute;
	return string;
}

function handlejson(){
	//theses are currently giving the wrong value, not the input value, but instead the original value
	var form = $("#pushinfo");
	var year = $('#time-year')[0].value;
	var month = $('#time-month')[0].value;
	var day = $('#time-day')[0].value;
	var hour = parseInt($('#time-hour').attr('value'));
	var minute = $('#time-minute')[0].value;
	var meridiem = $('#meridiem')[0].value;
	if( $('#meridiem')[0].value == "pm" && hour < 12){
		hour = hour + 12;
	}
	 $('#time').attr('value', construct_datetime_string(year, month, day, hour, minute));

	 $.ajax({
			type: 'POST',
			url: 'buildjson.php',
		});
	
}

function build_menu(){
	var html = "";
	html = "<nav id='main-nav'>" + 
				"<ul>"+
					"<li id='item_1'><a href='#' alt=''>Json Input</a></li>"+
					"<li id='item_2'><a href='#' alt=''>Welcome Title</a></li>"+
					"<li id='item_3'><a href='#' alt=''>Checklist</a></li>"+
					"<li id='item_4'><a href='#' alt=''>Search Postal Codes</a></li>"+
					"<li id='item_5'><a href='#' alt=''>Item 5</a></li>"+
					"<li id='item_6'><a href='#' alt=''>Item 6</a></li>"+
				"</ul>"+
			"</nav>";
	html += "<div id='pop-up'></div>";
	return html;			
}

function build_menu_width(){//function should work no matter where the menu is placed in the DOM
	var parent_width = $('#main-nav').parent().width();
	var num_of_menu_items = ($('#main-nav>ul>li').length)
	var mbp = 11;///the combined margin/border/padding space used for an individual menu item - should be 1-2px larger to accomodate rounding
	var width = 100*((1/num_of_menu_items) - ((1 - ((parent_width - (num_of_menu_items * mbp))/parent_width))/num_of_menu_items));
	console.log(width);
	$('#main-nav>ul>li').each(function(){
		$(this).css("width", width+"%");	
	});
}

$(function(){//hide-and-show navigation
	$('#pop-up').hide();

	$('#main-nav>ul>li').click(function(){		
		var html = "";		
		var current_class = $('div#pop-up').prop('className');		
		$('div#pop-up').removeClass();
		
		if($(this).prop('id') == 'item_1'){
			html = build_form_menu();
		}else if($(this).prop('id') == 'item_2'){
			html = build_temp_menu();
		}else if($(this).prop('id') == 'item_3'){
			html = build_check_list();
		}else if($(this).prop('id') == 'item_4'){
			html = build_search();
		}
		$('div#pop-up').html(html);
		$('div#pop-up').addClass($(this).prop('id'));
		
		//placing events after object is insterted to page
		if($(this).prop('id') == 'item_1'){
			$('form#pushinfo').submit(handlejson());
		}

		if($('#pop-up').is(":visible") ){
			if(current_class == $(this).prop('id')){
				$('#pop-up').hide("fast");
			}	
		}else{
			$('#pop-up').show("fast");
		}

	return false;

	});
});

function build_form_menu(){
	var html = "<form method='post' action='jsonbuild.php' id='pushinfo' class='insideMenu'>"+
					"<p>Naming</p>"+
					"<label for='etype'>Calendar Display Name: </label><input type='text' maxlength='100' name='type' id='etype' value='eg. \"Sales\", \"Tournament\", \"Tickets\"' required />"+
					"<br /><label for='owner'>Individual/Group Sponsoring Event: </label><input type='text' maxlength='100' name='owner' id='owner' required />"+
					"<br /><label for='name'>Name Your Event: </label><input type='text' maxlength='100' name='name' id='name' required />"+
					"<br /><br />Listing Options: <input type='radio' name='norl' value='National' />National "+
					"<input type='radio' name='norl' value='Local' />Local"+"<br/>    -if 'Local' is chosen, postalcode below will identify its location"+
					"<p>Time Details</p>"+
					"<label for='time-month'>First Event Day: </label><input type='text' size='2' name='month' maxlength='2' id='time-month' value='MM' required />/"+
					"<input type='text' name='day' size='2' maxlength='2' id='time-day' value='DD' required />/"+
					"<input type='text' name='year' size='2' maxlength='4' id='time-year' value='YYYY' required />"+
					"<br /><label for='time-month'>Finale Event Day: </label><input type='text' size='2' name='month-end' maxlength='2' id='time-month-end' value='MM'  />/"+
					"<input type='text' name='day-end' size='2' maxlength='2' id='time-day-end' value='DD'  />/"+
					"<input type='text' name='year-end' size='2' maxlength='4' id='time-year-end' value='YYYY'  />"+
					"<br /><label for='time-hour'>Daily Start Time: </label><input type='text' size='2' name='hour' maxlength='2' id='time-hour' value='HH' required />:"+
					"<input type='text' name='minute' size='2' maxlength='2' id='time-minute' value='MM' required />"+
					"<select id='meridiem'>" +
						"<option value='0'>24hr</option>" +
						"<option value='am'>AM</option>" +
						"<option value='pm'>PM</option>" +
					"</select>" +
					"<input type='hidden' name='time' id='time' />" +
					"<p>Locale Information</p>"+
					"<label for='url'>URL: </label><input type='url' maxlength='100' name='url' id='url' value='http://' />"+
					"<br /><br /><label for='country'>Event Address</label><br />" +
					"<label for='country'>Country: </label><input type='text' maxlength='100' size='46' name='country' id='country' required />"+
					"<br /><label for='street'>Street: </label><input type='text' maxlength='100' size='48' name='street' id='street' required />"+
					"<br /><label for='city'>City: </label><input type='text' maxlength='100' name='city' id='city' required />"+
					"<label for='zipcode'>Zip:</label><input type='text' maxlength='5' name='zipcode' id='zipcode' required />"+
					"<p>Event Explanation and Details</p>"+
					"<label for='price'>Pricing: </label><input type='text' maxlength='10' name='price' id='price' />"+
					"<br /><label for='add'>What is the Event: (1000 chars-max)</label><br /><textarea type='text' cols='50' rows='10' maxlength='1000' name='add' id='add' ></textarea>"+
					"<br /><label for='activities'>Activities: </label><input type='text' maxlength='100' name='activities' id='activities' />"+
					"<input type='button' maxlength='10' name='price' id='price' value='Add More' />"+

					"<br /><input type='submit' name='submit' id='pushsubmit'/>"+
				"</form>";
	return html;
}

function build_temp_menu(){
	var html = "Welcome to the site<br />" +
					"<p>Here is some filler content</p>"+
					"<p>And a bit more but not so much that it fills the page </p>";
	return html;
}

function build_check_list(){
	var html = "<form method='post' action='' id='user-favorite' class='insideMenu'>"+
					"<span class='checkbox'><input type='checkbox' name='tournaments' id='tourn-box' /><label for='tourn-box'>Tournaments</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='video-games' id='videogame-box' /><label for='videogame-box'>Video Games</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='vidoe-game-cons' id='vgcon-box' /><label for='vgcon-box'>Video Game Conventions</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='ent-cons' id='entcon-box' /><label for='entcon-box'>Entertainment Conventions</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_1' id='temp_1' /><label for='temp_1'>temp_1</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_2' id='temp_2' /><label for='temp_2'>temp_2</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_3' id='temp_3' /><label for='temp_3'>temp_3</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_4' id='temp_4' /><label for='temp_4'>temp_4</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_5' id='temp_5' /><label for='temp_5'>temp_5</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_6' id='temp_6' /><label for='temp_6'>temp_6</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_7' id='temp_7' /><label for='temp_7'>temp_7</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_8' id='temp_8' /><label for='temp_8'>temp_8</label></span>"+					"<span class='checkbox'><input type='checkbox' name='temp_7' id='temp_7' /><label for='temp_7'>temp_7</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_9' id='temp_9' /><label for='temp_9'>temp_9</label></span>"+					"<span class='checkbox'><input type='checkbox' name='temp_7' id='temp_7' /><label for='temp_7'>temp_7</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_10' id='temp_10' /><label for='temp_10'>temp_10</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_11' id='temp_11' /><label for='temp_11'>temp_11</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_12' id='temp_12' /><label for='temp_12'>temp_12</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_13' id='temp_13' /><label for='temp_13'>temp_13</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_14' id='temp_14' /><label for='temp_14'>temp_14</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_15' id='temp_15' /><label for='temp_15'>temp_15</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_16' id='temp_16' /><label for='temp_16'>temp_16</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_17' id='temp_17' /><label for='temp_17'>temp_17</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_18' id='temp_18' /><label for='temp_18'>temp_18</label></span>"+					"<span class='checkbox'><input type='checkbox' name='temp_7' id='temp_7' /><label for='temp_7'>temp_7</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_19' id='temp_19' /><label for='temp_19'>temp_19</label></span>"+					"<span class='checkbox'><input type='checkbox' name='temp_7' id='temp_7' /><label for='temp_7'>temp_7</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_20' id='temp_20' /><label for='temp_20'>temp_20</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_21' id='temp_21' /><label for='temp_21'>temp_21</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_22' id='temp_22' /><label for='temp_22'>temp_22</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_23' id='temp_23' /><label for='temp_23'>temp_23</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_24' id='temp_24' /><label for='temp_24'>temp_24</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_25' id='temp_25' /><label for='temp_25'>temp_25</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_26' id='temp_26' /><label for='temp_26'>temp_26</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_27' id='temp_27' /><label for='temp_27'>temp_27</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_28' id='temp_28' /><label for='temp_28'>temp_28</label></span>"+					"<span class='checkbox'><input type='checkbox' name='temp_7' id='temp_7' /><label for='temp_7'>temp_7</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_29' id='temp_29' /><label for='temp_29'>temp_29</label></span>"+					"<span class='checkbox'><input type='checkbox' name='temp_7' id='temp_7' /><label for='temp_7'>temp_7</label></span>"+
					"<span class='checkbox'><input type='checkbox' name='temp_30' id='temp_30' /><label for='temp_30'>temp_30</label></span>"+
				
					"<br /><br /><input type='submit' name='submit' id='check-submit' value='Update'/>"+
				"</form>";


	return html;
}

function build_search(){
	var html = "<form method='post' action='' id='search-form' class='insideMenu'>" + 
					"<p>Enter a Postal Code to limit the events to a localized area</p>"+
					"<label for='search-distance'>Postal Code</label><input type='text' id='search-distance' name='search' value='' />"+
					"Radius: <select id='radius'>" +
						"<option value=''>N/A</option>" +
						"<option value='10'>10 Miles</option>" +
						"<option value='25'>25 Miles</option>" +
						"<option value='50'>50 Miles</option>" +
						"<option value='100'>100 Miles</option>" +
					"</select>" +
					"<input type='submit' value='Search' name='submit' id='search-submit' />"+
				"</form>";
	return html;
}
