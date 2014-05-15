<?php

require_once('../include/functions.php');
require_once('../include/comment.php');

$ajax = ($_SERVER[ 'HTTP_X_REQUEST_WITH' ] === 'XMLHttpRequest');

$error = "";
$img = new Photograph();
$game = new Game();

//TODO: Fix this for uploading to photograph and to game, and fix the photograph class and add a  photograph table to the database
$added = $img->attach_file($_POST['img']);
if($added){
	$created = $game->add_game($_POST);
}else{
	$error = "Could not create Image File";
}
if($created){
	$made = $img->save();
}else{
	$error = "Could not create Game";
}
if($made){
	redirect_to('add_game.php');
}else{
	$error = "Could not save Image to Path";
}
var_dump($_POST);
echo($error);


/*if($ajax){
	sendAjaxResponse($added);
} else {
	sendStandardResponse($added);
}*/

?>