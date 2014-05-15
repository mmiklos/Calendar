<?php
require_once('../includes/calander_face.php');

//header( 'Location: index.php');
$ajax = ($_SERVER[ 'HTTP_X_REQUESTED_WITH' ] === 'XMLHttpRequest');
$x = (isset($_POST['month']) && isset($_POST['year']));
//$x = isset($_POST['prev']);


if(isset($_POST['submit']) && isset($_POST['type']) && isset($_POST['owner']) && isset($_POST['name']) && isset($_POST['time'])){
	$_POST['month'] = ltrim($_POST['month'], '0');
	$calendar->change_year($_POST['year']);
	$calendar->set_month($_POST['month']);
	$_POST['country_code'] = $countryIP->countrytocc($_POST['country']);
	$calendar->push_to_file($_POST);
}

if($ajax){
	sendAjaxResponse($x);
} else {
	sendStandardResponse($x);
}

?>