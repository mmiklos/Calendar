<?php
require_once("databaseObject.php");

class User extends DatabaseObject {
//Static properties should only be called from the class level
//Static properties/functions dont need an instantiated class
	protected static $table_name="admins";
	protected static $db_fields = array('id', 'username', 'password', 'first_name', 'last_name');
	public $username;
	public $password;
	public $first_name;
	public $last_name;
	public $id;
	
	public function full_name(){
		if(isset($this->first_name)&&isset($this->last_name)){
			return $this->first_name . ' ' . $this->last_name;
		}else{
			return "";
		}
	}	
	public static function authenticate($username="", $password=""){
		global $db;
		$username = $db->escape_value($username);
		$password = self::passwordEncrypt($password);
		
		$sql  = "SELECT * FROM ".self::$table_name." ";
		$sql .= "WHERE username = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "LIMIT 1";
		
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false ;
	}
	
	public function make_user($var){
	//search for user
		if($exists = $this->find_by_username($var['username'])){
			return false;
		}else{
			$this->username = $var['username'];
			$this->first_name = isset($var['first_name']) ? $var['first_name'] : '';
			$this->last_name = isset($var['last_name']) ? $var['last_name'] : '';
		
			$password = self::passwordEncrypt($var['password']);	
			$this->password = $password;
	
			//Values 'should' be made safe in the create() call; not sure if happening
			//should escape html values before this step tho, incase failure
			return $this->create();
		}
	}
	
	public function attempt_login($username, $password){
	$user = self::find_by_username($username);
	$user = $user;
	if($user){	//found, now look for password;
		if(self::password_check($password, $user->password)){//password check is not working
			//password matches
			return $user;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
}

$user = new User();
?>