<?php

class Session{
//Never store database objects in session
	private $logged_in;
	public $user_id;
	public $username;
	public $message;
	
	function __construct(){
		session_start();
		$this->check_login();
		$this->check_message();
	}
	public function is_logged_in(){
		return $this->logged_in;
	}
	
	public function login($user){
		if($user){
			$this->user_id = $_SESSION['user_id'] = $user->id;
			$this->username = $_SESSION['username'] = $user->username;
			$this->logged_in = true;
		}
	}
	public function logout(){
		unset($_SESSION['user_id']);
		unset($this->user_id);
		unset($this->username);
		unset($_SESSION['username']);
		$this->logged_in = false;
	}
	
	public function message($msg=""){
		//will set or send a message
		if(!empty($msg)){
		//Set a value
			$_SESSION["message"] = $msg;
		}else{
		//Get a value
			return $this->message;
		}
	}
	
	private function check_login(){
		if(isset($_SESSION['user_id'])){
			$this->user_id = $_SESSION['user_id'];
			$this->logged_in = true;
		}else{
			unset($this->user_id);
			$this->logged_in = false;
		}
	}
	private function check_message(){
		if(isset($_SESSION["message"])){
			$this->message = $_SESSION["message"];
			unset($_SESSION["message"]);
		}else{
			$this->message = "";
		}
	}

}
$session = new Session();
$message = $session->message();
?>