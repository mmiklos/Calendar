<?php

Class Country_ip extends databaseObject{
	protected static $table_name="geoip_country";
	protected static $db_fields=array('id','start_ipv6_address','end_ipv6_address','netmask_1','netmask_2', 'country_code', 'country_name');
	
	public $id;
	public $start_ipv6_address;
	public $end_ipv6_address;
	public $netmask_1;
	public $netmask_2;
	public $country_code;
	public $country_name;
/*NEVER CALL THIS FUNCTION AGAIN! 
	public function load_country_db(){
			$file_and_dir = SITE_ROOT.DS.'public'.DS.'GeoIPv6.csv';
			if($handle = fopen($file_and_dir, 'r')){
				//set_time_limit(500);
				while(!feof($handle)){
					$db_info = fgets($handle);
					$data = str_getcsv($db_info, ',', '"');
					$this->start_ipv6_address = $data[0];
					$this->end_ipv6_address = $data[1];
					$this->netmask_1 = $data[2];
					$this->netmask_2 = $data[3];
					$this->country_code = $data[4];
					$this->country_name = $data[5];
					self::create();
				}
			fclose($handle);
		}
	}
*/

	public function ip_lookup(){
		//$iplong = ip2long($_SERVER['REMOTE_ADDR']);
		$iplong = sprintf("%u", ip2long('209.85.227.147'));
		//$iplong = inet_pton('2607:f0d0:1002:51::4');
		//might need to use inet_pton
		$sql = "
		    SELECT country_name
		    FROM geoip_country
		    WHERE " .$iplong. " BETWEEN start_ipv6_address AND end_ipv6_address
		    LIMIT 1";
		return self::find_by_sql($sql);
	
		//if needed later convert back to readable ip address:
		//long2ip(sprintf("%d", $ip_address));
	}
}

$countries = new Country_ip();
?>
