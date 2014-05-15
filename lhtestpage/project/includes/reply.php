<?php
require_once("database.php");

class Reply extends DatabaseObject{
	protected static $table_name = "replies";
	protected static $db_fields = array('id', 'comment_id');
	
	public $id;
	public $comment_id;
	
	
	public static function make($id, $comment_id){
		if(!empty($comment_id)){
			$reply = new Reply();
			$reply->comment_id = (int)$comment_id;	
			$reply->id = (int)$id;

			$success = $reply->create();
			echo $success;
			return $reply;
		}else{
			return false;
		}
	}

	public static function find_replies_on ($comment_id){
		global $db;
		$sql  = "SELECT * FROM ".self::$table_name;
		$sql .=" WHERE comment_id = ".$db->escape_value($comment_id);
		$sql .=" LIMIT 1";
		return self::find_by_sql($sql);
	}
	
	public function find_indexed_comment ($comment_id){
		echo $comment_id;
		$obj = self::find_by_id($comment_id);
		echo $obj->comment_id;
		$this->comment_id = $obj->comment_id;
		$this->id = $obj->id;
		echo $this->comment_id;
		//var_dump((array)$obj);
	}
}
	
?>