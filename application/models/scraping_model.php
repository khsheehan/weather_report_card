<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scraping_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	function scrape($id){
		$scrape = array();
		$scrape['data'] = $this->gather_data($id);
		$scrape['weather_channel'] = $this->weather_channel($id);
		return $scrape;
	}

	function gather_data($id){
		$data = array();
		$sql = 'SELECT name FROM locations WHERE location_id = ?';
		$vars = array($id);
		$query = $this->db->query($sql,$vars)->result_array();
		$data['location'] = $query[0]['name'];
		return $data;
	}

	function weather_channel($id){
		$scrape = array();
		$source_id = 1; // 1 is the source id for weather channel

		$sql = 'SELECT * FROM sources WHERE source_id = ?';
		$vars = array($source_id);
		$query = $this->db->query($sql,$vars)->result_array();
		$scrape['source'] = $query[0];

		$sql = 'SELECT code FROM location_codes WHERE source_id = ? AND location_id = ?';
		$vars = array($source_id,$id);
		$query = $this->db->query($sql,$vars)->result_array();
		$scrape['code'] = $query[0];

		$scrape['url'] = $scrape['source']['scrape_url'].$scrape['code']['code'];

		return $scrape;
	}

}