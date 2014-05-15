<?php
require_once('../includes/initialize.php');
//$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
//$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'upload.processor.php'; 
$uploadHandler = 'add_game.php';
$max_file_size = 30000;

if(isset($_POST['submit'])){
	$img = new Photograph;
	$game = new Game;
	$file_name_chars = $_POST['file_name'];
	$file_name = "";

	//Create file name from input game name
	for($i=0; $i<strlen($file_name_chars); $i++){
		$temp = substr($file_name_chars, $i, 1);
		if(!ctype_alnum($temp) || $temp==" "){
			if($temp==" "){
				$temp = "_";
			}elseif($temp==":"){
				$temp = "&";
			}elseif($temp==","){
				$temp = "%";
			}
		}
		$file_name = $file_name.$temp;
	}
	$parts = (pathinfo($_FILES['img']['name']));
	$_FILES['img']['name'] = ucfirst($file_name.".".$parts['extension']);;
	$img->attach_file($_FILES['img']);
	if($img->save()){
		if($game->add_game($_POST)){
			$session->message('Game Added Successfully!');
			var_dump($_FILES, $_POST);
		}
	}else{
		$session->message = 'Game cannot be added: <br />'.join("<br />", $img->errors);
	}
	echo $session->message();
}

?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Game Upload</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>
	<body>
		<div id="wrapper">
			<div id="title">
				<h1><a href="#">Upload a game</a></h1>
			</div>
			<article id="game_upload">
				<form id="upload" action="<?php echo $uploadHandler ?>" enctype="multipart/form-data" method="post">
					<fieldset id="fieldset1">
						<h4>Enter an image to represent the game</h4>
						<p>Maximum file size is 30Kbs</p>
						<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size ?>"> 
						<input type="file" accept='image/*' name="img" id="img" required />

							<br />


						 	<br />

						 <h4>Enter the name of the game</h4>
						<input type="text" value="Name of Game" name="file_name" id="file_name" required/>
							
							<br /><br />
							<h4>Pick a genre</h4>
							
						<input type="text" value="" name="genre" id="genre" style="display:none;" />

						<input type="checkbox" value="RPG" name="rpg" id="rpg" class="_genre" />RPG
						<input type="checkbox" value="FPS" name="fps" id="fps" class="_genre" />FPS
						<input type="checkbox" value="MOBA" name="moba" id="moba" class="_genre" />MOBA
						<input type="checkbox" value="Racing" name="racing" id="racing" class="_genre" />Racing
						<input type="checkbox" value="Fighting" name="fighting" id="fighting" class="_genre" />Fighting
						<input type="checkbox" value="RTS" name="rts" id="rts" class="_genre" />RTS<br />
						
							<br />
							
							<h4>Enter some tags</h4>
							<p>tags must be seperated by a space (' ') or a comma (',')</p>
						<input type="text" name='tags' id='tags' />

							<br /><br />

						<input type="submit" value="Add Game" name="submit" id="submit" />	

					</fieldset>
				</form>
				<a href="index.php" alt="return">Return to Main Page</a>
			</article>

		</div><!--wrapper-->
		<script src="js/jquery-1.11.0.min.js"></script>
		<script src="js/functions.js"></script>
		<script src="js/test.js"></script>
	</body>
</html>