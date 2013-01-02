<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	function check_user($user=NULL,$email=NULL){
		$this->db->select("COUNT('*') as c");
		$this->db->where("name",$user);
		$this->db->or_where("email",$email);
		$c = $this->db->get('users')->result_array();
		if($c[0]['c']):
			return TRUE;
		else:
			return FALSE;
		endif;
	}

	function build_user($user,$pass){
		$sql = "SELECT id FROM users WHERE name = ?";
		$query = $this->db->query($sql,array($user))->result_array();
		$id = $query[0]['id'];
		$salt = md5($id);
		$hash = md5($pass);
		$pass = $salt.$hash;
		$sql = "SELECT COUNT('*') as c FROM users WHERE name = ? AND pass = ?";
		$query = $this->db->query($sql,array($user,$pass))->result_array();
		$login = $query[0]['c'];
		$sql = "SELECT name, is_super, zip FROM users WHERE name = ? AND pass = ?";
		$query = $this->db->query($sql,array($user,$pass))->result_array();
		if($login):
			$user_data = array(
				'is_super' => $query[0]['is_super'],
				'name' => $query[0]['name'],
				'zip' => $query[0]['zip']
			);
			$this->session->set_userdata($user_data);
			redirect();
		else:
			redirect();
		endif;
	}

}