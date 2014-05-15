<?php

class MySQLDatabase{
	private $connection;
	public $last_query;
	private $magic_quotes_active;
	private $real_escape_string_exists;

	function __construct(){
		$this->open_connection();
		$this->magic_quotes_active = get_magic_quotes_gpc();
		$this->real_escape_string_exists = function_exists( "mysql_real_escape_string" );//newer versions of php
	
	}
	public function open_connection(){
		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		//TEST if connection succeeded
		if(!$this->connection){
			die("Database connection failed: " . mysqli_error());
		}else{
			$db_select = mysqli_select_db($this->connection, DB_NAME);
			if(!$db_select){
				die("Database connection failed: " . mysqli_error());
			}
		}
	}
	public function close_connection(){
		if(isset($this->connection)){
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}	
	public function query($sql){
		$this->last_query = $sql;
		$result = mysqli_query($this->connection, $sql);
		$this->confirm_query($result);
		return $result;	
	}
	public function fetch_array($result_set){
		return mysqli_fetch_array($result_set);
	}
	private function confirm_query($result){
		if(!$result){
			echo "Last query: {$this->last_query}";
			die("Database connection failed: " . mysqli_error($this->connection) . mysqli_errno($this->connection));
		}
	}
	public function escape_value( $value ){
		if($this->real_escape_string_exists){//PHP v4.3.0+
			//undo magic quote effects so mysql_real_escape_string can do the work
			if( $this->magic_quotes_active ){ $value=stripslashes($value); }
			$value = mysqli_real_escape_string($this->connection, $value );
		}else{//Pre PHP v4.3.0
			//if magic quotes arent already on then add slashes manually
			if(!$this->magic_quotes_active){ $value = addslashes($value); }
		}
		return $value;
	}
	public function num_rows($result_set){
		return mysqli_num_rows($result_set);
	}
	public function insert_id($result_set=0){
		//returns last id inserted over the current db connection
		return mysqli_insert_id($this->connection);
	}
	public function affected_rows(){
		return mysqli_affected_rows($this->connection);
	}
}
$db = new MySQLDatabase();

?>