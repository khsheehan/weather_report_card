<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller {

	public function index()
	{
		if(sizeof($_POST)):
			$this->load->model('user_model');
			// Check password
			if($_POST['pass'] != $_POST['pass_conf']):
				echo "Your passwords do not match";
				exit;
			endif;
			// Check username / email
			if($this->user_model->check_user($_POST['name'],$_POST['email'])):
				echo "That username or email is taken. Please choose another.";
				exit;
			endif;
			unset($_POST['pass_conf']);
			$this->db->insert('users',$_POST);
			// Update password
			$id = $this->db->insert_id();
			$salt = md5($id);
			$hash = md5($_POST['pass']);
			$pass = $salt.$hash;
			$this->db->where('name',$_POST['name']);
			$this->db->update('users',array('pass' => $pass));
			$this->user_model->build_user($_POST['name'],$_POST['pass']);
		else:
			$data['page'] = 'signup_view';
			$this->load->view('layouts/template',$data);
		endif;
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */