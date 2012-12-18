<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scraping_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	function scrape(){
		require_once('application/libraries/Simple_html_dom.php');
		// Get all locations in array
		$this->db->select('COUNT(*) as c');
		$count = $this->db->get('locations')->result_array();
		$c = $count[0]['c'];
		for ($i=1; $i < $c; $i++) { 
			$this->results($i);
			$this->weather_channel($i);
			$this->accuweather($i);
		}
	}

	function results($id){

		$MIN_RAIN = .5;

		$date = mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"));
		$day = date('d',$date);
		$date = date('Y-m-d',$date);
		$sql = 'SELECT scrape_url FROM sources WHERE source_id = 1';
		$query = $this->db->query($sql)->result_array();
		$scrape_url = $query[0]['scrape_url'];
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
		$scrape['date'] = $date;

		$this->db->insert('results',$scrape);

	}

	function weather_channel($id){
		$today = date('Y-m-d');
		$three_day = mktime(0, 0, 0, date("m")  , date("d")+3, date("Y"));
		$three_day = date('Y-m-d',$three_day);
		$scrape = array();
		$source_id = 2; // 1 is the source id for weather channel

		$sql = 'SELECT * FROM sources WHERE source_id = ?';
		$vars = array($source_id);
		$query = $this->db->query($sql,$vars)->result_array();
		$scrape_url = $query[0]['scrape_url'];

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

		$scrape['temp_hi'] = $temp_hi->plaintext;
		$scrape['temp_lo'] = $temp_lo->plaintext;
		$scrape['pop'] = $pop->plaintext;
		$scrape['location_id'] = $id;
		$scrape['date_3_day'] = $three_day;
		$scrape['date_prediction'] = $today;
		$scrape['source_id'] = $source_id;

		unset($html);

		$this->db->insert('forecasts',$scrape);

	}

	function accuweather($id){

		$today = date('Y-m-d');
		$three_day = mktime(0, 0, 0, date("m")  , date("d")+3, date("Y"));
		$three_day = date('Y-m-d',$three_day);
		$scrape = array();
		$source_id = 3; // 1 is the source id for accuweather

		$sql = 'SELECT * FROM sources WHERE source_id = ?';
		$vars = array($source_id);
		$query = $this->db->query($sql,$vars)->result_array();
		$scrape_url = $query[0]['scrape_url'];

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

		$scrape['temp_hi'] = $temp_hi->plaintext;
		$scrape['temp_lo'] = $temp_lo->plaintext;
		$scrape['pop'] = $pop->plaintext;
		$scrape['location_id'] = $id;
		$scrape['date_3_day'] = $three_day;
		$scrape['date_prediction'] = $today;
		$scrape['source_id'] = $source_id;

		unset($html);

		$this->db->insert('forecasts',$scrape);

	}

}