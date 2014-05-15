
function construct_event(element){
	console.log($(this), this, element, element[0].innerHTML);
	var html = "";
	element.each(function(data){
		html += "<div class='entry' >"+
					"<p>"+element[data].innerHTML+"</p>"+
				"</div>"; 

	});
		$('#listed-events').html("");
		$('#listed-events').append(html);
}
