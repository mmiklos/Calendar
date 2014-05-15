<?php
require_once('../includes/calander_face.php');

//header( 'Location: index.php');
$ajax = ($_SERVER[ 'HTTP_X_REQUESTED_WITH' ] === 'XMLHttpRequest');
$x = (isset($_POST['month']) && isset($_POST['year']));
//$x = isset($_POST['prev']);

if($x){
	$calendar->set_month($_POST['month']);
	$calendar->change_year($_POST['year']);
}
$_POST['this_month'] = $calendar->get_month();
$_POST['this_year'] = $calendar->get_year();
var_dump($_POST);
echo($ajax);
$file = 'filetest.txt';
if($handle = fopen($file, 'w')){
	$content = 'Hello: ';
	foreach($_POST as $key => $value){
		$content .= $key."=>".$value."\n";
	}
	fwrite($handle, $content);
	fclose($handle);
}


if($ajax){
	sendAjaxResponse($x);
} else {
	sendStandardResponse($x);
}

function sendAjaxResponse($x){
	header("Content-Type: application/x-javascript");
	if($x){
		header( 'Status: 201' );
	} else {
		header( 'Status: 400' );
	}
}

function sendStandardResponse($x){
	if($x){
		header( 'Location: calendar.php?error=Stats not adjusted');
	} else {
		header( 'Location: calendar.php?error=Stats not adjusted' );
	}
}

?>