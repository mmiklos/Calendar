$(document).ready(function(){
	console.log(this);
	console.log($(this));

	//Eliminate searches which dont match the user input string
	$('#search').keyup(function( event ){
		var input = this.value.toLowerCase();
		console.log(input);
		var ex = new RegExp("/" + input + "/gi");
		$('.game_name').each(function(){
			if(this.innerHTML.toLowerCase().indexOf(input) == -1 && input!=""){
				$(this).parent().parent().css("display", "none");
			}else{
				$(this).parent().parent().css("display", "block");

			}
		});
	});

	//Erase serch field ONLY if value == "Search"
	$('#search').focus(function(){
			if(this.value=="Search"){
				this.value = "";
			}
		});
	$('#search').blur(function(){
			if(this.value==""){
				this.value = "Search";
			}
		});

	(function(){
		var clickcount = 0;
		//This will need to be dynamically created later, but for now, this will do as a test
		var html = $( "<br /><div id='genre_box'> <div class='genre'><input type='checkbox'  name='rpg' />RPG &nbsp</div> " +
						"<div class='genre'><input type='checkbox' name='fps' />FPS &nbsp</div>" +
						"<div class='genre'><input type='checkbox' name='moba' />MOBA &nbsp</div>" +
						"<div class='genre'><input type='checkbox' name='fps' />Racing &nbsp</div>" +
						"<div class='genre'><input type='checkbox' name='fps' />Fighting &nbsp</div>" +
						"<div class='genre'><input type='checkbox' name='fps' />RTS &nbsp</div>" +
						"</div>" );
		$('#advanced').click(function(){
			console.log(this, $(this));
			clickcount++;

			$(this).parent().append(html);
			
			if(clickcount%2==0){
				$('#genre_box').hide();
			}else{
				$('#genre_box').show();
			}
		});
	}());
	
	var genres = "";
	$('._genre').click(function(){	
		//$('._genre').each(function(){
			//console.log($( this ).prop( "checked" ));
			if($( this ).prop( "checked" )){
				genres = genres + this.value + " ";
			}else{
				genres = genres.replace(this.value+" ", "");
			}
		//});
		//genres = genres.trim();
		console.log(genres);
		$('#genre').attr('value',genre); 
		console.log(document.getElementById("genre").value);
	});


});

