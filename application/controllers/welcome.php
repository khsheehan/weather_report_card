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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */