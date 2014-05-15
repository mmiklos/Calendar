<?php
	require_once('../includes/initialize.php');
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Test Game Page</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>
	<body>
		<div id="wrapper">
			<div id="title">
				<h1><a href="#">Pick some games you enjoy!</a></h1>
			</div>
			<article id="search-functions">
				<form method="POST" action="index.php">
					<fieldset id="fieldset1">
						<input type="text" value="Search" name="search" id="search" />
						<input type="submit" value="Search" name="find" id="find" />
						<a href="#" id="advanced">Advanced Search</a> &nbsp;
						<a href="add_game.php" id="add_game">Upload a game</a>
							<br />

					</fieldset>
				</form>
			</article>
			<article id="game-list">
				<?php
					$files = glob('img/game_thumbs/*.{jpg,png,gif}', GLOB_BRACE);
					$i = 0;
					$html = "<form method='post' action='index.php'>
								<fieldset>";
					foreach($files as $file){
						//Make sure the file can be open
						if($handle = fopen($file, 'r')){
							//Remove the extenstions, then remove the period (so as to not accidently remove letters before the period)
							$id_title = rtrim(rtrim(substr($file, strrpos($file, '/')+1), "jpngif"), ".");
							$temp = str_replace(array("_"), " ", str_replace(array("&"), ":", str_replace(array("%"), ",", $id_title)));
							$title = ucwords(strtolower($temp));
							$html .= "<div class='game ".
														(($i%2==0) ? "odd" : "even")
																		."'>
											<label for='cb_".$id_title."'>
												<img src='".$file."'alt='insert_title_here_later' width='100px' height='133px' /><br />
												<p class='game_name'>". $title ."</p>
											</label>
											<input type='checkbox' name='checkbox' id='cb_".$id_title."'/>Select
											<input type='hidden' name='genres' class='genre' value='"."' />

									</div>";//The hidden field is most likely unnecessary, noted here for easy find later
							$i++;
							fclose($handle);
						}
					}
					echo $html;
				?>
				<br /></fieldset><h1></h1>
					<footer id="base">
						<input type="submit" value="Submit" name="submit" id="submit" />
					</footer>
				</form>
					<br />
			</article>
		</div>
		<script src="js/jquery-1.11.0.min.js"></script>
		<script src="js/functions.js"></script>
		<script src="js/test.js"></script>
	</body>
</html>
<?php
?>