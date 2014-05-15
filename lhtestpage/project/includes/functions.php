<?php
require_once("initialize.php");

function strip_zeros_from_date($marked_string=""){
	//remove marked zeros first
	$no_zeros = str_replace('*0', '', $marked_string);
	//then remove other marks
	$cleaned_string = str_replace('*', '', $no_zeros);
	return $cleaned_string;
}

function redirect_to( $location = NULL ){
	if($location!=NULL){
		header("Location: {$location}");
		exit;
	}
}

function output_message($message=""){
	if(!empty($message)){
		return "<p class=\"message\">{$message}</p>";
	}else{
		return "";
	}
}

function __autoload($class_name){
	$class_name = strtolower($class_name);
	$path = LIB_PATH.DS."{$class_name}.php";
	if(file_exists($path)){
		require_once($path);
	}else{
		die("The file {$class_name}.php could not be found.");
	}
}

function include_layout_template($template=""){
	include(SITE_ROOT.DS."public".DS."layouts".DS.$template);
}

function log_action($action, $message=""){
	$filename = SITE_ROOT.DS."logs".DS."logs.txt";
	if($handle = fopen($filename, 'a')){
		$log = strftime('%m-%d-%Y %H:%M')." | ".$action.": ".$message."\n";
		fwrite($handle, $log);
		fclose($handle);
	}else{
		echo $filename." cannot be written to";
	}
}

function datetime_to_text($datetime=""){
	$unixdatetime = strtotime($datetime);
	return strftime("$B %d, %Y at %I:%M %p", $unixdatetime);
}

function sendAjaxResponse($add){
	header("Content-Type: application/x-javascript");
	if($add){
		header( 'Status: 201' );
		echo( json_encode($add) );
	} else {
		header( 'Status: 400' );
	}
}

function sendStandardResponse($add){
	if($add){
		header( 'Location: calendar.php');
	} else {
		header( 'Location: calendar.php?error=Unable to create' );
	}
}

?>