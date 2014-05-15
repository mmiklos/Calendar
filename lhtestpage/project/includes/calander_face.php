<?php
require_once('../includes/initialize.php');

Class Calendar extends databaseObject{
	
	protected static $table_name="calendar";
	protected static $db_fields=array('id','date','activity_type','who','what', 'timezone', 'url', 'address', 'price', 'activity_list', 'optional_text');
	
	private $year;
	private $month;
	public $longitude;
	public $latitude;

	private $id;
	private $date;
	private $activity_type;
	private $who;
	private $what;
	private $timezone;
	private $url;
	private $address;
	private $price;
	private $activity_list;
	private $optional_text;

	function __construct($month = NULL, $year = NULL){
		if(!is_null($month)){
			$this->$month = $month;
		}else{
			$this->month = date('n');//strftime('%M', strtotime("now"));
		}

		if(!is_null($year)){
			$this->year = $year;
		}else{
			$this->year = date('Y');//strftime('%Y', strtotime("now"));	//Gets current year to 4 digit num
		}
	}

	public function get_year(){
		return $this->year;
	}
	public function get_month(){
		return $this->month;
	}

	public function change_year($num){
		$this->year = $num;
	}
		
	public function set_month($num){
		$this->month = $num;
	}
	
	public function subtract_month($num){
		$this->month = $this->month - $num;
	}

	public function populate_calendar(){

	}

	public function build_json_file($filename){
		$file_and_dir = SITE_ROOT.DS.'public'.DS.'json'.DS.$filename;

		$monthName = Date('F', mktime(0,0,0,$this->month));
		$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
		if(!file_exists($file_and_dir)){
			if($handle = fopen($file_and_dir, 'w')){
				$content = '{'.PHP_EOL."\t";
				for($i=1;$i<=$daysInMonth;$i++){
					$content .= '"'.$i.'": {'.PHP_EOL."\t";
					$content .= "\t".'},'.PHP_EOL."\t";
				}
				$content = substr($content, 0, strrpos($content, ','));
				$content .= PHP_EOL.'}';
				fwrite($handle, $content);
				fclose($handle);
			}
			
		}else{
			return false;
		}
	}

	public function input_event($var){

	}

	public function push_to_file($var){
		//test variables
		$datetime = $var['time'];//"2014-04-12 23:00";
		$acttype = $var['type'];//"Tournament";
		$who = $var['owner'];//"The Social Gamers Club";
		$what = $var['name'];//"Mega Awesome Tournament";
		$url = isset($var['url']) ? $var['url'] : NULL;//"www.www.socialgamersclub.com/mega-tournament-sign-ups";
		$gps =  $var['street'] . " " . $var['city'] . " " . $var['country'] ." ". (isset($var['zipcode'])?$var['zipcode']:"");//need to organize this string
		$country = str_replace(' ', '', $var['country']);
		$price = isset($var['price']) ? $var['price'] : NULL;//"$10.00";
		$activitylist = "12-1-34-11-12";//need to organize this string also
		$optionaltext = isset($var['add']) ? $var['add'] : NULL;
		$locale = isset($var['norl'])?$var['norl']:'Local';
		//end test variables

		if ($locale == 'Local'){
			$file = $this->year . "_" . $this->month . "_" . $var['country_code'] . "_" . $var['zipcode'] . "_" . $locale .".json";
		}else{
			$file = $this->year . "_" . $this->month . "_" . $var['country_code'] . "_" . $locale .".json";
		}
		$file_and_dir = SITE_ROOT.DS.'public'.DS.'json'.DS.$file;
		if(!file_exists($file_and_dir)){
			self::build_json_file($file);
		}
		if($handle = fopen($file_and_dir, 'r+')){
			$data = file_get_contents($file_and_dir);
			$pos1 = strpos($datetime, '-');
			$pos2 = strpos($datetime, '-', $pos1 + strlen('-'));		
			$day = substr($datetime, $pos2+1, 2);

			$pos3 = strpos($data, '"'.$day.'": {'.PHP_EOL."\t");
			$offset = strlen("\t\"".$day.'": {'.PHP_EOL."\t");
			$dataFirstHalf = substr($data, 0, $pos3+$offset);
			$dataSecondHalf = substr($data, $pos3+$offset);

			$time = substr($datetime, $pos2+1, -3);

			$build_string = PHP_EOL."\t\"".$acttype.'": {'.PHP_EOL;
			$build_string .= "\t\t"."\"Who\": \"".$who.'",'.PHP_EOL;
			$build_string .= "\t\t"."\"What\": {".PHP_EOL;
			$build_string .= "\t\t\t".'"'.$what.'": {'.PHP_EOL;
						if($activitylist != NULL){
				$build_string .= "\t\t\t\t".'"Games": {'.PHP_EOL;
				$build_string .= "\t\t\t\t\t".'"'.$activitylist.'": "temp.png"'.PHP_EOL;
				$build_string .= "\t\t\t\t\t".'}'.PHP_EOL;//fix later with the comma after the bracket if there are more activities
			}
			$build_string .= "\t\t\t\t".'}'.PHP_EOL;
			$build_string .= "\t\t\t".'},'.PHP_EOL;
			$build_string .= "\t\t".'"When": "'.$time.'",'.PHP_EOL;
			$build_string .= "\t\t".'"url": "'.$url.'",'.PHP_EOL;
			$build_string .= "\t\t".'"gps": "'.$gps;
			if($optionaltext != NULL){
				$build_string .= '",'.PHP_EOL;
				$build_string .= "\t\t".'"optionEntries": "'.$optionaltext;
			}
			$build_string .= '"'.PHP_EOL."\t\t"."}";

			$str = strtok($dataSecondHalf, PHP_EOL);
			$line = ($str == "\t\t}," || $str == "\t},") ? true : false;
			echo $str;
			echo $line;
			if(!$line){
				$build_string .= ",";
			}
			$build_string .= PHP_EOL;

			$newData = $dataFirstHalf . $build_string . $dataSecondHalf;
			fwrite($handle, $newData);
			fclose($handle);
		}
			//echo $dataFirstHalf. $newData . $dataSecondHalf;
	}
public function geoloc($address){	
     // Get lat and long by address         
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);
        $this->latitude = $output->results[0]->geometry->location->lat;
        $this->longitude = $output->results[0]->geometry->location->lng;

}

public function reverse_geoloc($lat, $long){

}

public function load_country_db(){
		$file_and_dir = SITE_ROOT.DS.'public'.'GeoIPv6.csv';
		if($handle = fopen($file_and_dir, 'r')){
			while(!feof($handle)){
				$db_info = fgets($handle);
			}
		fclose($handle);
		}
}














//get one month
	//build a 1 month calendar
/*	

public function create_month(){
		//for($month=1; $month<13; $month++){
			self::create_week();//loop
		//}
	}

	private function create_week(){
		//determine where the start day should be and go from there
		$monthStartDay = strftime('%w', strtotime("1 ".date("F", mktime(0, 0, 0, $this->month, 10)).$this->year));//gets day of week the month starts on
		$num_days[$this->month] = cal_days_in_month ( CAL_GREGORIAN , $this->month , $this->year );//gets the number of days in each month
		self::create_calendar($monthStartDay, $num_days[$this->month]);
	}

	private function create_calendar($first_day_of_month, $days_in_month){
		$day = array();
		$day = self::build_days_in_month($first_day_of_month, $days_in_month);

		//number of weeks included in month
		$num_weeks = round((max($day)['day'] - min($day)['day']) / 7, 0, PHP_ROUND_HALF_UP);

		//HTML SECTION
			$html = self::build_month();
			$html .= self::weeks_tab($num_weeks);
			
			for($i=0;$i<7;$i++){
				$html .= self::weekday_names($i);
			}
			
			$week=0;
			foreach($day as $key => $value){
				$current = $value['day']-min($day)['day']+1;
				//echo $value['day']." ";
				if($current%7===0 && $value['day']!==max($day)['day']){
					$week++;
				}
				$html .= self::create_day($key, $value, $current);
			}

		$html .= "</section>";
		echo($html);
		//END HTML SECTION
	}

	private function weekday_names($weekday_num){
		$day_of_week= date('D', strtotime("Sunday +{$weekday_num} days"));
		$html_names = "<div class='weekday_names' id='".$day_of_week."'>
							<p>".$day_of_week."</p>
						</div>";
		return $html_names;
	}

	private function create_day($key, $value, $current){
		$weekday = "<div class='weekday ";	
		$weekday .=		(($current%7==6) ? "end_of_week " : "");
		$weekday .=		($value['in_month']==false) ? "not_in_month' " : "' "; 
		$weekday .=	" 	id='day_".$value['day']."_".$this->month."'>";
		$weekday .= "	<p>".($value['day']+1)."</p>";
		$weekday .= "	<span class='events'></span>";
		$weekday .= "</div>";
		//echo $weekday;
		return $weekday;

	}

	private function weeks_tab($num_weeks){
			$week = "<aside id='week_sidebar'>";
			for($i=1;$i<=$num_weeks;$i++){
				$week .= "<div class='week_".$i." week'><p>Week ".$i."</p></div>";
			}
			$week .= "</aside>";

			return $week;
	}

	private function build_month(){
			$html = "<section id='".date("F", mktime(0, 0, 0, $this->month, 10));
			$html .=	date('n')==($this->month) ? "' class='current'>" : "' class='no_current'>";
			$html .= "<br /><h1><a href='#' class='switch' alt=''>Next</a><a href='#' class='switch' alt=''>Prev</a>".date("F", mktime(0, 0, 0, $this->month, 10))."&nbsp;<span id='year'>".$this->year."<span></h1>";
			
			return $html;
	}

	private function build_days_in_month($first_day_of_month, $days_in_month){
		$day = array();
		$last_day_of_month = (($days_in_month % 7) + $first_day_of_month);//determin the last weekday of the month
		$last_day_of_month = ($last_day_of_month > 6) ? $last_day_of_month - 8: $last_day_of_month-1;

		//days in week before month starts
		if($first_day_of_month != 0){
			while($first_day_of_month != 0){
				$day[0-$first_day_of_month] = array();
				$day[0-$first_day_of_month]['day'] = 0-$first_day_of_month;
				$day[0-$first_day_of_month]['in_month'] = false;
				$first_day_of_month--;
			}
		}

		//days in month
		for($i=0; $i<$days_in_month; $i++){
			$day[$i] = array();
			$day[$i]['day'] = $i;
			$day[$i]['in_month'] = true;
		}
		
		//days in week after month ends
		if($last_day_of_month != 6){
			$end = array();
			while($last_day_of_month != 6){
				$end[$days_in_month+(6-$last_day_of_month)] = array();
				$end[$days_in_month+(6-$last_day_of_month)]['day'] = $days_in_month-1+(6-$last_day_of_month);
				$end[$days_in_month+(6-$last_day_of_month)]['in_month'] = false;
				$last_day_of_month++;
			}
			 $end = array_reverse($end);
			 $day = array_merge($day, $end);
		}

		return $day;
	}
*/
}
$calendar = new Calendar;

















?>