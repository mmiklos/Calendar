<?php
require_once("database.php");

class DatabaseObject {
	protected static $table_name;
	
	public static function find_all(){
		return self::find_by_sql("SELECT * FROM ".static::$table_name);
	}
	public static function find_by_id($id=0){
		global $db;
		$result_array = self::find_by_sql("SELECT * FROM ".static::$table_name." WHERE id={$db->escape_value($id)} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false ;
	}
	public static function find_by_username($username=""){
		global $db;
		$result_array = self::find_by_sql("SELECT * FROM ".static::$table_name." WHERE username='{$db->escape_value($username)}' LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false ;
	}
	public static function find_by_sql($sql=""){
		global $db;
		$result_set = $db->query($sql);
		$object_array = array();
		while($row = $db->fetch_array($result_set)){
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}
	public static function count_all(){
		global $db;
		
		$sql = "SELECT COUNT(*) FROM ".static::$table_name;
		$result_set = $db->query($sql);
		$row = $db->fetch_array($result_set);
		return array_shift($row);
	}
	private static function instantiate($record){
		$object = new static;
		//$object->id			 = $record['id'];
		//$object->username 	 = $record['username'];
		//$object->password 	 = $record['password'];
		//$object->first_name	 = $record['first_name'];
		//$object->last_name 	 = $record['last_name'];
		
		foreach($record as $attribute=>$value){
			if($object->has_attribute($attribute)){
				$object->$attribute = $value;
			}
		}
		
		return $object;
	}
	private function has_attribute($attribute){
		//$object_vars = get_object_vars($this);
		$object_vars = $this->attributes();
		return array_key_exists($attribute, $object_vars);
	}
	//Password encryption
	protected function passwordEncrypt($password){
		$salt_length = 22;
		$hash_format = "$2y$10$";
	 
		$salt = self::generate_salt($salt_length);
		$format_and_salt = $hash_format . $salt;
		$hash = crypt($password, $format_and_salt);
		return $hash;
	}
	private function generate_salt($length){
		$unique_random_string = md5(uniqid(mt_rand(), true));
		
		//Valid characters [a-zA-Z0-9./]
		$base64_string = base64_encode($unique_random_string);
		
		//except + also, so we replace + with . here
		$modified_base64_string = str_replace('+', '.', $base64_string);
		
		$salt = substr($modified_base64_string, 0, $length);
		return $salt;
	}
	protected function password_check($password, $existing_hash){
		$hash = crypt($password, $existing_hash);
		if ($hash === $existing_hash){
			return true;
		}else{
			return false;
		}
	}
	
	protected function attributes(){
		//return an array of attributes names and their values
		$attributes = array();
		foreach(static::$db_fields as $field){
			if(property_exists($this, $field)){
				$attributes[$field] = $this->$field;
			}
		}
		return	$attributes;
	}
	protected function sanitized_attributes(){
		global $db;
		$clean_attributes = array();
		foreach($this->attributes() as $key => $value){
			$clean_attributes[$key] = $db->escape_value($value);
		}
		return $clean_attributes;
	}
	
	public function save(){
		return isset($this->id) ? $this->update() : $this->create();
	}
//these functions require an instantiated object to be called
	protected function create(){
		global $db;
		$attributes = $this->sanitized_attributes();
		$sql  = "INSERT INTO ".static::$table_name." (";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		
		if($db->query($sql)){
			$this->id = $db->insert_id()+1;
			return true;
		}else{
			return false;
		}
	}
	protected function update(){
		global $db;
		$attributes = $this->sanitized_attributes();
		foreach($attributes as $key => $value){
			$attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql  = "UPDATE ".static::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id='".$db->escape_value($this->id);
		$db->query($sql);
		return($db->affected_rows() == 1) ? true : false;
	}
	public function delete(){
		global $db;
		
		$sql  = "DELETE FROM ".static::$table_name;
		$sql .= " WHERE id=".$db->escape_value($this->id);
		$sql .= " LIMIT 1";
		$db->query($sql);
		return($db->affected_rows() == 1) ? true : false;
	}

}

?>