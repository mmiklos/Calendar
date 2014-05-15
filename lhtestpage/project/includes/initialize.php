<?php 

//Define the core paths
//Define them as absolute paths to make sure that require_once works as expected

//DIRECTORY_SEPARATOR is a php pre-defines constant
//(\ for Windows, / for Unix)
defined('DS')? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT')? null : define('SITE_ROOT','C:'.DS.'xampp'.DS.'htdocs'.DS.'lhtestpage'); 
defined('LIB_PATH')? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

//Config file should be loaded first
require_once(LIB_PATH.DS."config.php");

//load basic functions
require_once(LIB_PATH.DS."functions.php");
//require_once(LIB_PATH.DS."PHPMailer".DS."language".DS."phpmailer.lang-en.php");
//load core objects
require_once(LIB_PATH.DS."database.php");
require_once(LIB_PATH.DS."databaseObject.php");
require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."pagination.php");


//load database related classes
//require_once(LIB_PATH.DS."user.php");
//require_once(LIB_PATH.DS."photograph.php");
require_once(LIB_PATH.DS."game.php");
require_once(LIB_PATH.DS."calander_face.php");
require_once(LIB_PATH.DS."country_ip_data.php");
require_once(LIB_PATH.DS."countryipdata.php");



?>