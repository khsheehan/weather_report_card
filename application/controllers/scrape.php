<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scrape extends CI_Controller {

	public function forecasts()
	{
		if(!$this->session->userdata('is_super')):
			redirect();
		endif;
		$this->load->model('scraping_model');
		$this->scraping_model->scrape('forecasts');
		echo "Forecasts scraped";
		exit;
	}

	public function results()
	{
		if(!$this->session->userdata('is_super')):
			redirect();
		endif;
		$this->load->model('scraping_model');
		$this->scraping_model->scrape('results');
		echo "Results scraped";
		exit;
	}

	public function grade()
	{
		if(!$this->session->userdata('is_super')):
			redirect();
		endif;
		$this->load->model('scraping_model');
		$this->scraping_model->scrape('grade');
		echo "Grades calculated";
		exit;
	}

	public function all()
	{
		if(!$this->session->userdata('is_super')):
			redirect();
		endif;
		$this->load->model('scraping_model');
		$this->scraping_model->scrape('all');
		echo "Forecasts scraped";
		echo "Results scraped";
		echo "Grades calculated";
		exit;
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */