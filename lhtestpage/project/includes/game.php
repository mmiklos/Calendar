<?php
require_once("databaseObject.php");

class Game extends DatabaseObject {
//Static properties should only be called from the class level
//Static properties/functions dont need an instantiated class
	protected static $table_name="topgames";
	protected static $db_fields = array('id', 'title', 'genre', 'tags');
	public $title;
	public $genre;
	public $tags;
	public $id;
	

	public function add_game($var){//$var represents a form $_POST variable
	//search for game
		$lowerTitle = strtolower($var['file_name']); //TODO: Also will need to replace/remove characters such as :, - later
		$this->title = htmlentities($var['file_name']);
		$this->genre = htmlentities($var['genre']);
		str_replace(array(","), " ", $var['tags']); 
		$this->tags = $var['tags'];
	
			//Values 'should' be made safe in the create() call; not sure if happening
			//should escape html values before this step tho, incase failure
		return $this->create();
	}
	

}