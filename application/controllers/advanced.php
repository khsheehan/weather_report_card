<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advanced extends CI_Controller {

	public function index()
	{
		$this->load->model('scraping_model');
		$data['formatted_date'] = date("M d, Y",strtotime($_GET['date']));
		$data['grades'] = $this->scraping_model->get_grades($_GET['date'],$_GET['zip']);
		if($data['grades'] == NULL){
			redirect();
		}
		$data['page'] = 'advanced_view';
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