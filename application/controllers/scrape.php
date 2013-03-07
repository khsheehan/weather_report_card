<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scrape extends CI_Controller {

	public function forecasts()
	{
		if(!$this->session->userdata('is_super') && php_sapi_name() != 'cli'):
			redirect();
		endif;
		$this->load->model('scraping_model');
		$this->scraping_model->scrape('forecasts');
		echo "Forecasts scraped\n";
		exit;
	}

	public function results()
	{
		if(!$this->session->userdata('is_super') && php_sapi_name() != 'cli'):
			redirect();
		endif;
		$this->load->model('scraping_model');
		$this->scraping_model->scrape('results');
		echo "Results scraped\n";
		exit;
	}

	public function grade()
	{
		if(!$this->session->userdata('is_super') && php_sapi_name() != 'cli'):
			redirect();
		endif;
		$this->load->model('scraping_model');
		$this->scraping_model->scrape('grade');
		echo "Grades calculated\n";
		exit;
	}

	public function all()
	{
		if(!$this->session->userdata('is_super') && php_sapi_name() != 'cli'):
			redirect();
		endif;
		$this->load->model('scraping_model');
		$this->scraping_model->scrape('all');
		echo "Forecasts scraped\n";
		echo "Results scraped\n";
		echo "Grades calculated\n";
		exit;
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */