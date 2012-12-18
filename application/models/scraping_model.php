<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scraping_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	function scrape(){
		// Get all locations in array
		$this->db->select('COUNT(*) as c');
		$count = $this->db->get('locations')->result_array();
		$c = $count[0]['c'];
		for ($i=1; $i < $c; $i++) { 
			$this->weather_channel($i);
		}
	}

	function weather_channel($id){
		$today = date('Y-m-d');
		$three_day = mktime(0, 0, 0, date("m")  , date("d")+3, date("Y"));
		$three_day = date('Y-m-d',$three_day);
		$scrape = array();
		$source_id = 1; // 1 is the source id for weather channel

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
		require_once('application/libraries/Simple_html_dom.php');
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

		$this->db->insert('forecasts',$scrape);

		return $scrape;
	}

}