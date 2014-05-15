<?php

Class CountryIP extends databaseObject{
	protected static $table_name="country_ip";
	protected static $db_fields=array('id','start_ipv4_address','end_ipv4_address','netmask_1','netmask_2', 'country_code', 'country_name');
	
	public $id;
	public $start_ipv4_address;
	public $end_ipv4_address;
	public $netmask_1;
	public $netmask_2;
	public $country_code;
	public $country_name;

	private $ip;

	function __construct(){
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->country_code = self::iptocc($this->ip);
	}

	public function get_ip(){
		return $this->ip;
	}


	public function iptocc($iplong){
		$iplong = ip2long($iplong);
		if($iplong){
			$sql = "
			    SELECT country_code 
			    FROM country_ip
			    WHERE " .$iplong. " BETWEEN start_ipv4_address AND end_ipv4_address
			    LIMIT 1";
			$cc =(array) self::find_by_sql($sql);
			$cc = (array)$cc[0];
			$cc = $cc['country_code'];
			return $cc;
		}
		else{
			return 'US';
		}
	}

	public function countrytocc($country){
		$sql = "
		    SELECT country_code 
		    FROM country_ip
		    WHERE country_name = '".$country."' 
		    LIMIT 1";
		if($cc =self::find_by_sql($sql)){
			$cc = (array)$cc;
			$cc = (array)$cc[0];
			$cc = $cc['country_code'];
			$this->country_code = $cc;
			return $cc;
		}else{
			return false;
		}
	}
}

$countryIP = new CountryIP();

/* DO NOT USE
	public function load_country_db(){
			$file_and_dir = SITE_ROOT.DS.'public'.DS.'GeoIPCountryWhois.csv';
			if($handle = fopen($file_and_dir, 'r')){
				set_time_limit(3600);
				while(!feof($handle)){
					$db_info = fgets($handle);
					$data = str_getcsv($db_info, ',', '"');
					$this->start_ipv4_address = ip2long($data[0]);
					$this->end_ipv4_address = ip2long($data[1]);
					$this->netmask_1 = $data[2];
					$this->netmask_2 = $data[3];
					$this->country_code = $data[4];
					$this->country_name = $data[5];
					self::create();
				}
			fclose($handle);
		}
	}
*/?>