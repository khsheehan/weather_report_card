<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scraping_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	function scrape($type){
		require_once('application/libraries/Simple_html_dom.php');
		$today = date('Y-m-d');
		if($type == 'forecasts' or $type == 'all'):
			$this->weather_channel();
			$this->accuweather();
		endif;
		if($type == 'results' or $type == 'all'):
			$this->results();
		endif;
		if($type == 'grade' or $type == 'all'):
			$yesterday = date("M")." ".((date("d"))-1).', '.date("Y");
			$this->grade($yesterday);
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

		for ($id=1; $id < $c; $id++) { 
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
		$source_id = 2; // 1 is the source id for weather channel

		$sql = 'SELECT * FROM sources WHERE source_id = ?';
		$vars = array($source_id);
		$query = $this->db->query($sql,$vars)->result_array();
		$scrape_url = $query[0]['scrape_url'];

		$scrape['date_3_day'] = $three_day;
		$scrape['date_prediction'] = $today;
		$scrape['source_id'] = $source_id;

		for ($id=1; $id < $c; $id++) { 
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
		$source_id = 3; // 1 is the source id for weather channel

		$sql = 'SELECT * FROM sources WHERE source_id = ?';
		$vars = array($source_id);
		$query = $this->db->query($sql,$vars)->result_array();
		$scrape_url = $query[0]['scrape_url'];

		$scrape['date_3_day'] = $three_day;
		$scrape['date_prediction'] = $today;
		$scrape['source_id'] = $source_id;

		for ($id=1; $id < $c; $id++) { 
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

	function grade($date){

		$this->db->select('COUNT(*) as c');
		$count = $this->db->get('sources')->result_array();
		$c = $count[0]['c'];

		for ($id=2; $id <= $c; $id++) {
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
			$fn = (array_sum($grades)/sizeof($grades));
		}

	}

	function get_grades($date){
		$grades = array();
		$sql = "SELECT COUNT(*) as c FROM sources";
		$num_sources_raw = $this->db->query($sql)->result_array();
		$num_sources = $num_sources_raw[0]['c'];

		for ($i=2; $i <= $num_sources; $i++) {
			$sql = "SELECT forecasts.temp_hi as pred_hi, forecasts.temp_lo as pred_lo, forecasts.pop as pred_pop, results.temp_hi as real_hi, results.temp_lo as real_lo, results.precipitation as real_pop, sources.name, grades.grade, grades.date, locations.name FROM sources LEFT JOIN grades ON grades.source_id = sources.source_id LEFT JOIN locations ON locations.location_id = grades.location_id LEFT JOIN forecasts on forecasts.location_id = grades.location_id AND forecasts.source_id = grades.source_id LEFT JOIN results on results.location_id = grades.location_id WHERE grades.date = ? AND grades.source_id = ? ORDER BY grades.source_id";
			$all_grades[$i] = $this->db->query($sql,array($date,$i))->result_array();
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