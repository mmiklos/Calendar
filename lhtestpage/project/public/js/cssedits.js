//This page will dynamically change the css of elemnts depending on the browser window size
$(function(){
	//console.log("Window Inner Width: " + $(window).width());
	//console.log("Window Inner Height: " + $(window).height());
	//console.log("Window Outer Width: " + $(window).width());
	//console.log("Window Outer Height: " + $(window).height());	

	//console.log("Document Inner Width: " + $(document).width());
	//console.log("Document Inner Height: " + $(document).height());
	//console.log("Document Outer Width: " + $(document).width());
	//console.log("Document Outer Height: " + $(document).height());

	//console.log("Screen Resolution: " + screen.width/screen.height);
});

function resize(){
	var height = $(window).width() * 0.122 * 0.8*0.72;
	var height2 = ($(window).height()/$(window).width())
	//console.log(height);
	//console.log(height2);
	$(".weekday").css("height", height);
}