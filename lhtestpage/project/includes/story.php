<?php
require_once("database.php");

Class Story extends DatabaseObject{
	protected static $table_name = "story";
	protected static $db_fields = array('id', 'author', 'created', 'title', 'body');
	
	public $id;
	public $author;
	public $created;
	public $title;
	public $body;
	
	public static function make($id, $title, $content, $author="Anonymous"){
		if(!empty($id) && !empty($author) && !empty($content)){
			$story = new Story();
			$story->created = strftime("%Y-%m-%d %H:%M:%S", time());
			$story->author = $author;
			$story->title = $title;
			$story->body = $content;
			if($success = $story->create()){
			}
			return $story;
		}else{
			return false;
		}
	}
	
	public function stories_per_page ($per_page){
		global $db;
		$sql  = "SELECT * FROM ".self::$table_name;
		$sql .=" ORDER BY created DESC";
		$sql .=" LIMIT {$per_page}";
		$sql .=" OFFSET ".Pagination::offset();
		return self::find_by_sql($sql);
	}
	public function top_ten_stories ($per_page){
		global $db;
		$sql  = "SELECT * FROM ".self::$table_name;
		$sql .=" ORDER BY created DESC";
		$sql .=" LIMIT {$per_page}";
		$sql .=" OFFSET ".Pagination::offset();
		return self::find_by_sql($sql);
	}
}