<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zip_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	function proximity_zip($zip){
		$url = 'http://api.geonames.org/findNearbyPostalCodesJSON?postalcode='.$zip.'&country=US&maxRows=500&radius=30&username=khsheehan';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		$page = curl_exec($curl);
		$data = json_decode($page);

		$closest_zips = array();
		foreach ($data->postalCodes as $point) {
			array_push($closest_zips, $point->postalCode);
		}

		$sql = "SELECT zip FROM locations";
		$query = $this->db->query($sql)->result_array();
		$available_zips = array();

		foreach ($query as $zip) {
			array_push($available_zips, $zip['zip']);
		}
		$zips = array_intersect($available_zips, $closest_zips);
		if(sizeof($zips)):
			return reset($zips);
		else:
			return NULL;
		endif;
	}

}