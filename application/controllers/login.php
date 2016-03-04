<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct() {
        parent::__construct();
        $this->load->database();
		$this->load->helper('url');
		$this->load->library('session');	
		$this->load->model('scripts');
		$this->load->helper('text');	
	}
 
	 
	public function index(){
		
		$ID = 1;
			
		$Query = "SELECT * FROM pages WHERE ID = '1' ";
    	$SQ = $this->db->query($Query);
		$data['Contact'] = $SQ->result();	
		
			
		$this->load->view('login', $data);
	}
	
	public function do_login(){
	
		
		if(isset($_POST['login'])){
			
			$email = $_POST['Email'];
			
			$table   = 'users';
			
			$key = 'Marwah-786!';
			$Password = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key),$this->input->post('Password'), MCRYPT_MODE_CBC, md5(md5($key))));
						
			$query = $this->scripts->login($email, $Password, $table);
			if($query->num_rows() == 1) {
			
			$SC = $query->result();
			
			$StatusCheck = $SC[0];
			
			if($StatusCheck->Status == 'on'){
			
					foreach ($query->result() as $res) {
					
						$this->session->set_userdata(array(
							'ID'             => $res->ID,
							'CompanyName'    => $res->CompanyName,
							'Status'		 => $res->Status,
							'AccountType'	 =>	$res->AccountType,
							'CompanyEmail'	 =>	$res->EmailAddress,
							'User_login'	 =>	true
							)
						);
						
					}
						$LastLogin           = date("Y-m-d H:i:s");
						$ID                  =(int)$this->session->userdata('ID');
						$data = array(
						   'LastLogin' => $LastLogin
						);
				
				$query 	 = $this->scripts->do_edit($table, $data, $ID);
				redirect(base_url().'userarea');
				
				} else {
				
				$this->session->set_userdata('Status', 'Waiting Approval');
				redirect(base_url().'login');
				}
				
			
				} else {
				
				$this->session->set_userdata('Status',"Invalid Email Address/Password...");
				redirect(base_url().'login');	
					
				}
			
		} else{
			
		redirect(base_url().'login');
			
		}
		
		
	}

	
	public function logout() {
	$this->load->model('scripts');
	$this->load->helper('url');
	    $this->session->unset_userdata('ID');
        $this->session->unset_userdata('EmailAddress');
        $this->session->unset_userdata('CompanyName');
        $this->session->unset_userdata('AccountType');   
        $this->session->unset_userdata('Status');   
        $this->session->unset_userdata('User_login'); 
        $this->session->sess_destroy();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
		redirect(base_url().'home');
    }
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */