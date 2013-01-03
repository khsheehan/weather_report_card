<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zip extends CI_Controller {

	public function index()
	{
		if(array_key_exists('zip', $_GET)):
			$this->load->model('zip_model');
			$zip = $this->zip_model->proximity_zip($_GET['zip']);
		else:
			if(array_key_exists('city', $_GET)):
				$zip = $_GET['city'];
			endif;
		endif;

		if(!$zip):
			redirect();
		endif;

		if(array_key_exists('date', $_GET)):
			$data['date'] = date('Y-m-d',strtotime($_GET['date']));
		else:
			$data['date'] = date("Y")."-".date("m").'-'.str_pad(((date("d"))-1),2,0,STR_PAD_LEFT);
		endif;

		$this->load->model('scraping_model');
		$data['grades'] = $this->scraping_model->get_grades($data['date'],$zip);
		$sql = "SELECT name, zip FROM locations ORDER BY name";
		$query = $this->db->query($sql)->result_array();
		$data['locations'] = $query;	
		foreach ($data['grades'][1] as $grade => $stats) {
			if($stats['num']):
				$data['grades'][1][$grade]['letter'] = $this->_lettergrade($stats['total']/$stats['num']);
			else:
				$data['grades'][1][$grade]['letter'] = NULL;
			endif;
		}
		$data['page'] = 'zip_view';
		$sql = "SELECT name FROM locations WHERE zip  = ?";
		$data['zip'] = $zip;
		$query = $this->db->query($sql,array($zip))->result_array();
		$data['location'] = $query[0]['name'];
		$this->load->view('layouts/template',$data);
	}

	private function _lettergrade($fn){
		$fl = "";
		$fl = ($fn<58) ? "F" : $fl;
		$fl = ($fn>=58) ? "F+" : $fl;
		$fl = ($fn>=60) ? "D-" : $fl;
		$fl = ($fn>=64) ? "D" : $fl;
		$fl = ($fn>=68) ? "D+" : $fl;
		$fl = ($fn>=70) ? "C-" : $fl;
		$fl = ($fn>=74) ? "C" : $fl;
		$fl = ($fn>=78) ? "C+" : $fl;
		$fl = ($fn>=80) ? "B-" : $fl;
		$fl = ($fn>=84) ? "B" : $fl;
		$fl = ($fn>=88) ? "B+" : $fl;
		$fl = ($fn>=90) ? "A-" : $fl;
		$fl = ($fn>=94) ? "A" : $fl;
		$fl = ($fn>=98) ? "A+" : $fl;
		return $fl;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */