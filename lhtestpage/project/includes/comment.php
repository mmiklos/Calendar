<?php
require_once("database.php");
require_once("reply.php");

class Comment extends DatabaseObject{
	protected static $table_name = "comments";
	protected static $db_fields = array('id', 'story_id', 'created', 'author', 'body', 'reply');
	
	public $id;
	public $story_id;
	public $created;
	public $author;
	public $body;
	public $reply;
	
	private $commentList = array();
	
	//Used to create a comment
	public static function make($id, $author="Anonymous", $content, $reply="false"){
		if(!empty($id) && !empty($content)){
			if($author==""){
				$author = "Anonymous";
			}
			$comment = new Comment();
			$comment->story_id = (int)$id;
			$comment->created = strftime("%Y-%m-%d %H:%M:%S", time());
			$comment->author = $author;
			$comment->body = $content;

			if($reply!="false"){
				$comment->reply = true;
			}else{
				$comment->reply = false;
			}
			$success = $comment->create();
			if($reply!="false"){
				$create_reply = Reply::make($comment->id, $reply);
			}
			return $comment;
		}else{
			return false;
		}
	}

	//Used to populate the comments on a particular story
	public static function find_comments_on ($id=0){
		global $db;
		$sql  = "SELECT * FROM ".self::$table_name;
		$sql .=" WHERE story_id = ".$db->escape_value($id);
		$sql .=" ORDER BY created DESC";
		return self::find_by_sql($sql);
	}
	
		
}
?>