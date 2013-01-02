<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		if(sizeof($_POST)):
			$this->load->model('user_model');
			$this->user_model->build_user($_POST['name'],$_POST['pass']);
		else:
			$data['page'] = 'login_view';
			$this->load->view('layouts/template',$data);
		endif;
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect();
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */