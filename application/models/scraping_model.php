<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scraping_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	function scrape($type){
		require_once('application/libraries/Simple_html_dom.php');
		$today = date('Y-m-d');
		if($type == 'forecasts' or $type == 'all'):
			if(!$this->already_have_forecasts()):
				$this->weather_channel();
				$this->accuweather();
				$this->wunderground();
			endif;
		endif;
		if($type == 'results' or $type == 'all'):
			if(!$this->already_have_results()):
				$this->results();
			endif;
		endif;
		if($type == 'grade' or $type == 'all'):
			if(!$this->already_have_grades()):
				$yesterday = date("Y")."-".date("m").'-'.str_pad(((date("d"))-1),2,0,STR_PAD_LEFT);
				$this->grade($yesterday);
			endif;
		endif;
	}

	function results(){

		$MIN_RAIN = .5;

		$date = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
		$day = date('d',$date);
		$date = date('Y-m-d',$date);

		$sql = 'SELECT scrape_url FROM sources WHERE source_id = 1';
		$query = $this->db->query($sql)->result_array();
		$scrape_url = $query[0]['scrape_url'];

		$this->db->select('COUNT(*) as c');
		$count = $this->db->get('locations')->result_array();
		$c = $count[0]['c'];

		$scrape['date'] = $date;

		for ($id=1; $id <= $c; $id++) { 
			$sql = 'SELECT code FROM location_codes WHERE source_id = 1 AND location_id = '.$id;
			$query = $this->db->query($sql)->result_array();
			$code = $query[0]['code'];
			$url = $scrape_url.$code;

			$html = new Simple_html_dom();
			$html->load_file($url);

			$hi = array();
			$lo = array();
			$p = array();

			foreach($html->find('table',3)->find('tr') as $tr) {
				if($tr->find('td',0)){
					if($tr->find('td',0)->plaintext == $day){
						if($tr->find('td',8)->plaintext){
							array_push($hi, $tr->find('td',8)->plaintext);
							array_push($lo, $tr->find('td',9)->plaintext);
							array_push($p, $tr->find('td',17)->plaintext);
						}
					}
				}
			}

			$hi = (array_sum($hi)/4);
			$lo = (array_sum($lo)/4);

			if(array_sum($p) < $MIN_RAIN):
				$p = 0;
			else:
				$p = 100;
			endif;

			$scrape['location_id'] = $id;
			$scrape['temp_hi'] = $hi;
			$scrape['temp_lo'] = $lo;
			$scrape['precipitation'] = $p;

			$this->db->insert('results',$scrape);		
		}

	}

	function weather_channel(){
		$this->db->select('COUNT(*) as c');
		$count = $this->db->get('locations')->result_array();
		$c = $count[0]['c'];

		$today = date('Y-m-d');
		$three_day = mktime(0, 0, 0, date("m")  , date("d")+3, date("Y"));
		$three_day = date('Y-m-d',$three_day);

		$scrape = array();
		$source_id = 2; // 2 is the source id for weather channel

		$sql = 'SELECT * FROM sources WHERE source_id = ?';
		$vars = array($source_id);
		$query = $this->db->query($sql,$vars)->result_array();
		$scrape_url = $query[0]['scrape_url'];

		$scrape['date_3_day'] = $three_day;
		$scrape['date_prediction'] = $today;
		$scrape['source_id'] = $source_id;

		for ($id=1; $id <= $c; $id++) { 
			$sql = 'SELECT code FROM location_codes WHERE source_id = ? AND location_id = ?';
			$vars = array($source_id,$id);
			$query = $this->db->query($sql,$vars)->result_array();
			$code = $query[0]['code'];

			$url = $scrape_url.$code;

			// Scrape actual page
			$html = new Simple_html_dom();
			$html->load_file($url);
			$temp_hi = $html->find('p[class=wx-temp]',3);
			$temp_lo = $html->find('p[class=wx-temp-alt]',3);
			$pop = $html->find('div[class=wx-details]',3)->find('dl dd',0);

			$scrape['location_id'] = $id;
			$scrape['temp_hi'] = $temp_hi->plaintext;
			$scrape['temp_lo'] = $temp_lo->plaintext;
			$scrape['pop'] = $pop->plaintext;

			unset($html);

			$this->db->insert('forecasts',$scrape);
		}

	}

	function accuweather(){

		$this->db->select('COUNT(*) as c');
		$count = $this->db->get('locations')->result_array();
		$c = $count[0]['c'];

		$today = date('Y-m-d');
		$three_day = mktime(0, 0, 0, date("m")  , date("d")+3, date("Y"));
		$three_day = date('Y-m-d',$three_day);

		$scrape = array();
		$source_id = 3; // 3 is the source id for accuweather

		$sql = 'SELECT * FROM sources WHERE source_id = ?';
		$vars = array($source_id);
		$query = $this->db->query($sql,$vars)->result_array();
		$scrape_url = $query[0]['scrape_url'];

		$scrape['date_3_day'] = $three_day;
		$scrape['date_prediction'] = $today;
		$scrape['source_id'] = $source_id;

		for ($id=1; $id <= $c; $id++) { 
			$sql = 'SELECT code FROM location_codes WHERE source_id = ? AND location_id = ?';
			$vars = array($source_id,$id);
			$query = $this->db->query($sql,$vars)->result_array();
			$code = $query[0]['code'];

			$url = $scrape_url.$code;

			// Scrape actual page
			$html = new Simple_html_dom();
			$html->load_file($url);
			$temp_hi = $html->find('div[id=details]',0)->find('div[class=day]',0)->find('span[class=temp]',0);
			$temp_lo = $html->find('div[id=details]',0)->find('div[class=night]',0)->find('span[class=temp]',0);
			$pop = $html->find('div[id=details]',0)->find('div[class=day]',0)->find('span[class=realfeel]',1);

			$scrape['location_id'] = $id;
			$scrape['temp_hi'] = $temp_hi->plaintext;
			$scrape['temp_lo'] = $temp_lo->plaintext;
			$scrape['pop'] = $pop->plaintext;

			unset($html);

			$this->db->insert('forecasts',$scrape);
		}

	}

	function wunderground(){

		$this->db->select('COUNT(*) as c');
		$count = $this->db->get('locations')->result_array();
		$c = $count[0]['c'];

		$today = date('Y-m-d');
		$three_day = mktime(0, 0, 0, date("m")  , date("d")+3, date("Y"));
		$three_day = date('Y-m-d',$three_day);

		$scrape = array();
		$source_id = 4; // 4 is the source id for weather underground

		$sql = 'SELECT * FROM sources WHERE source_id = ?';
		$vars = array($source_id);
		$query = $this->db->query($sql,$vars)->result_array();
		$scrape_url = $query[0]['scrape_url'];

		$scrape['date_3_day'] = $three_day;
		$scrape['date_prediction'] = $today;
		$scrape['source_id'] = $source_id;

		error_reporting(E_ALL);
		ini_set('display_errors',1);

		for ($id=1; $id <= $c; $id++) {

			$sql = 'SELECT code FROM location_codes WHERE source_id = ? AND location_id = ?';
			$vars = array($source_id,$id);
			$query = $this->db->query($sql,$vars)->result_array();
			$code = $query[0]['code'];

			$url = $scrape_url.$code;

			$html = new Simple_html_dom();
			$html->load_file($url);

			$temp_hi = $html->find('div[class=fctInactive]',2)->find('div[class=fctDayContent]',0)->find('div[class=fctHiLow]',0)->find('span[class=b]',0);
			$temp_lo = $html->find('div[class=fctInactive]',2)->find('div[class=fctDayContent]',0)->find('div[class=fctHiLow]',0);
			preg_match_all('/<\/span>(.*)/', $temp_lo, $matches);
			$temp_lo = preg_replace("/[^0-9,.]/", "", $matches[0][0]);
			$pop = $html->find('div[class=fctInactive]',2)->find('div[class=fctDayContent]',0)->find('div[class=fctDayPop]',0)->find('div[class=popValue]',0);

			$scrape['location_id'] = $id;
			$scrape['temp_hi'] = $temp_hi->plaintext;
			$scrape['temp_lo'] = $temp_lo;
			$scrape['pop'] = $pop->plaintext;

			$html->clear();
			unset($html);
			$html = NULL;

			$this->db->insert('forecasts',$scrape);
		}

	}

	function grade($date){

		$this->db->select('COUNT(*) as c');
		$count = $this->db->get('sources')->result_array();
		$c = $count[0]['c'];

		for ($id=2; $id <= $c+1; $id++) {
			$sql = "SELECT * FROM (SELECT forecasts.location_id, forecasts.date_3_day, forecasts.source_id, forecasts.temp_hi as pred_hi, forecasts.temp_lo as pred_lo, forecasts.pop as pred_pop FROM forecasts WHERE source_id = ? AND date_3_day = ?) q1 LEFT JOIN (SELECT results.date, results.location_id, results.temp_hi as real_hi, results.temp_lo as real_lo, results.precipitation as real_pop FROM results WHERE `date` = ?) q2 ON q1.date_3_day = q2.date AND q1.location_id = q2.location_id";
			$vars = array($id,$date,$date);
			$query = $this->db->query($sql,$vars)->result_array();

			$grades = array();

			foreach ($query as $data) {
				$delta_h = abs($data['pred_hi']-$data['real_hi']);
				$delta_l = abs($data['pred_lo']-$data['real_lo']);
				$delta_p = abs($data['pred_pop']-$data['real_pop']);
				$t = (0.3*($delta_l) + 0.94*($delta_h));
				$p = (abs(((50-abs($delta_p))/50)-1))*10;
				$g = 100 - $t - $p;
				$g = ($g<0) ? 0 : $g;
				$insert['date'] = $date;
				$insert['grade'] = $g;
				$insert['location_id'] = $data['location_id'];
				$insert['source_id'] = $data['source_id'];
				$this->db->insert('grades',$insert);
				array_push($grades, $g);
			}

		}

	}

	function already_have_forecasts(){
		$today = date('Y-m-d');
		$sql = "SELECT COUNT(*) as c FROM forecasts WHERE date_prediction = ?";
		$query = $this->db->query($sql,array($today))->result_array();
		if($query[0]['c']):
			return true;
		else:
			return false;
		endif;
	}

	function already_have_results(){
		$yesterday = date('Y-m-').str_pad(((date("d"))-1),2,0,STR_PAD_LEFT);
		$sql = "SELECT COUNT(*) as c FROM results WHERE `date` = ?";
		$query = $this->db->query($sql,array($yesterday))->result_array();
		if($query[0]['c']):
			return true;
		else:
			return false;
		endif;
	}

	function already_have_grades(){
		$yesterday = date('Y-m-').str_pad(((date("d"))-1),2,0,STR_PAD_LEFT);
		$sql = "SELECT COUNT(*) as c FROM grades WHERE `date` = ?";
		$query = $this->db->query($sql,array($yesterday))->result_array();
		if($query[0]['c']):
			return true;
		else:
			return false;
		endif;
	}

	function get_grades($date=NULL,$zip=NULL){
		$this->load->model('zip_model');
		if(!$date){
			$date = date("Y")."-".date("m").'-'.str_pad(((date("d"))-1),2,0,STR_PAD_LEFT);
		}
		else{
			$date = explode('/', $date);
			$date = $date[2]."-".$date[0].'-'.$date[1];
		}
		if($zip!=NULL){
			$zip = $this->zip_model->proximity_zip($_GET['zip']);
		}
		$grades = array();
		$sql = "SELECT COUNT(*) as c FROM sources";
		$num_sources_raw = $this->db->query($sql)->result_array();
		$num_sources = $num_sources_raw[0]['c'];
		for ($i=2; $i <= $num_sources; $i++) {
			$sql = "SELECT forecasts.temp_hi as pred_hi, forecasts.temp_lo as pred_lo, forecasts.pop as pred_pop, results.temp_hi as real_hi, results.temp_lo as real_lo, results.precipitation as real_pop, sources.name, grades.grade, grades.date, locations.name, locations.zip
			FROM sources
			LEFT JOIN grades
			ON grades.source_id = sources.source_id
			LEFT JOIN locations
			ON locations.location_id = grades.location_id
			LEFT JOIN forecasts on forecasts.location_id = grades.location_id
			AND forecasts.source_id = grades.source_id
			LEFT JOIN results on results.location_id = grades.location_id
			WHERE grades.date = ? AND grades.source_id = ?";
			if($zip){
				$sql = $sql." AND locations.zip = ?
				ORDER BY grades.source_id";
				$all_grades[$i] = $this->db->query($sql,array($date,$i,$zip))->result_array();
			}
			else{
				$all_grades[$i] = $this->db->query($sql,array($date,$i))->result_array();
			}
		}

		if(sizeof($all_grades[2])==0){ // No results have been found
			return NULL;
		}

		for ($i=2; $i <= $num_sources; $i++) {
			$sql = "SELECT sources.name, sources.source_id, SUM(grades.grade) as total, COUNT(grades.grade) as num FROM sources LEFT JOIN grades ON grades.source_id = sources.source_id WHERE grades.date = ? AND grades.source_id = ?";
			$query = $this->db->query($sql,array($date,$i))->result_array();
			$grades[$i] = $query[0];
		}

		$return = array($all_grades,$grades);

		return $return;
	}

}