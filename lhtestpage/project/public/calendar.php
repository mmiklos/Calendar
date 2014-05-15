<?php
	require_once("../includes/initialize.php");
	if($countryIP){

	}
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Game Upload</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">

	</head>
	<body>


		<br />
		<p id="testObject">
		</p>
		<?php
			if(isset($_POST['submit'])){
				$type = htmlspecialchars($_POST['type']);
				$type2 = htmlentities($_POST['type']);
				var_dump($_POST);
				echo "<br />" . "<br />" . $_POST['time'];
			}
		?>

		<script src="js/jquery-1.11.0.min.js"></script>
		<script src="js/functions.js"></script>		
		<script src="js/menu_construct.js"></script>		
		<script src="js/cssedits.js"></script>
		<script>
			get_country("<?php echo $countryIP->country_code; ?>");//this MUST come before create_year()
			create_year();
			build_events();
			b_geoloc();
			
			//
		</script>			
		<script src="js/events_construct.js"></script>

		<?php
			var_dump($countryIP->iptocc('97.124.73.114'));
			$calendar->geoloc('85281');
			echo "Numbers Here: ";
			echo $calendar->latitude;
			echo $calendar->longitude;
		?>
	</body>
</html>
