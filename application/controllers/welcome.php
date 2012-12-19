<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$data['page'] = 'welcome_message';
		$this->load->view('layouts/template',$data);
	}

	public function scrape(){
		$this->load->model('scraping_model');
		$data['scrape'] = $this->scraping_model->scrape();
		$data['page'] = 'welcome_message';
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