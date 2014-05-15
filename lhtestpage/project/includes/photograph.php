<?php

class Photograph extends DatabaseObject{

	protected static $table_name="images";
	protected static $db_fields=array('id','filename','type','size','alt');
	public $id;
	public $filename;
	public $type;
	public $size;
	public $alt;

	private $temp_path;
	protected $upload_dir = "img/game_thumbs";
	public $errors=array();
	protected $upload_errors = array(
		UPLOAD_ERR_OK			=>	"No errors.",
		UPLOAD_ERR_INI_SIZE		=>	"Larger than upload_max_filesize.",
		UPLOAD_ERR_FORM_SIZE	=>	"Larger than form MAX_FILE_SIZE.",
		UPLOAD_ERR_PARTIAL		=>	"Partial upload.",
		UPLOAD_ERR_NO_FILE		=>	"No file.",
		UPLOAD_ERR_NO_TMP_DIR	=>	"No temporary directory.",
		UPLOAD_ERR_CANT_WRITE	=>	"Can't write to disk.",
		UPLOAD_ERR_EXTENSION 	=>	"File upload stopped by extension."
	);
	
	public function attach_file($file){
	//Perform error checking
		if(!$file || empty($file) || !is_array($file)){
			$this->errors[] = "No file was uploaded.";
			return false;
		}elseif($file['error'] != 0){
			//error: report what php says went wrong
			$this->errors[] = $this->upload_errors[$file['error']];
			return false;
		}else{
			$this->temp_path =$file['tmp_name'];
			$this->filename =basename($file['name']);
			$this->type =$file['type'];
			$this->size =$file['size'];
			return true;
		}
	}

	public function image_path(){
		return $this->upload_dir.DS.$this->filename;
	}
	public function size_as_text(){
		if($this->size < 1024){
			return "{$this->size} bytes";
		}elseif($this->size < 1048576){
			$size_kb = round($this->size/1024);
			return "{$size_kb} KB";
		}else{
			$size_mb = round($this->size/1048576, 1);
			return "{$this->size} MB";
		}
	}
	public function save(){
		if(!isset($this->id) && $this->id!=''){
			echo "ID ISSET: ".$this->id;
			parent::update();
			//for updating caption
		}else{
			if(!empty($this->errors)){ return false;}//false if errors
			if(empty($this->filename) || empty($this->temp_path)){//false if path cant be gotten or found
				$this->errors[] = "The file location was not available.";
				return false;
			}
			$target_path = SITE_ROOT.DS.'public'.DS.$this->upload_dir.DS.$this->filename;
			if(file_exists($target_path)){//if the file already exists fail
				$this->errors[] = "The file {$this->filename} already exists. ";
				return false;
			}
			if(move_uploaded_file($this->temp_path, $target_path)){
				if(parent::create()){//if the file is successfully moved, create it
					unset($this->temp_path);
					return true;
				}
			}else{
				$this->errors[] = "The file upload failed.";
				return false;
			}	
		}
	}
	public function extension($ex){
		$parts = pathinfo($this->filename);
		$remove = $parts['extension'];
		return basename($this->filename, $remove).$ex;
	}
	public function destroy(){
		//Remove db entry
		if($this->delete()){
			$page_path = SITE_ROOT.DS."public".DS.$this->extension("php");
			//Remove file
			$target_path = SITE_ROOT.DS."public".DS.$this->image_path();
			if(unlink($page_path) && unlink($target_path)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}

	}
	
	public function comments(){
		return Comment::find_comments_on($this->id);
	}
	
	public function try_to_send_email_notification(){
		$mail = new PHPMailer();
		
		$mail->IsSMTP();
		$mail->Host			="localhost";
		$mail->Port			="25";
		$mail->SMTPAuth		=false;
		$mail->Username		="username";
		$mail->Password		="password";
		
		$mail->FromName		="Photo Gallery";
		$mail->From			="my_email@yahoo.com";
		$mail->AddAddress("to_email@yahoo.com", "Admin");
		$mail->Subject 		="New Photo Gallery Comment";
		$mail->Body			=<<<EMAILBODY
		
		A new comment has been received in the Photo Gallery.
		
		At {$this->created}, {$this->author} wrote:
		
		{$this->body}

EMAILBODY;
		
		$result = $mail->Send();
		return $result;
	}
}
