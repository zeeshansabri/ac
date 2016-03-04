<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

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
		
		$data = '';
		$data['PageTitle'] = 'Login - MMS Admni';

		if ($this->session->userdata('is_admin_login') == true) {
        redirect('mmsadmin/dashboard',$data);
        } else {
		$this->load->view('mmsadmin/home',$data);
        }
		
	}
	
	/* Login Script*/	
	public function do_login() {
	
				
				$Email    = $this->input->post('email');
				$Password = $this->input->post('password');
				$key = '';		
				$PasswordEncrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key),$Password, MCRYPT_MODE_CBC, md5(md5($key))));
				$query = $this->scripts->login($Email, $PasswordEncrypted, 'accounts');
				
				if($query->num_rows() == 1){
				
				foreach ($query->result_array() as $recs => $res) {
						$this->session->set_userdata(array(
							'ID'             => $res['ID'],
							'FirstName'      => $res['FirstName'],
							'LastName'       => $res['LastName'],
							'EmailAddress'   => $res['EmailAddress'],
							'is_admin_login' => true,
							'AccountType'    => $res['AccountType']
							)
						);
					}
						$LastLogin           = date("Y-m-d H:i:s");
						$ID                  =(int)$this->session->userdata('ID');
						$data = array(
						   'LastLogin' => $LastLogin
						);
				$table   = 'accounts';
				$query 	 = $this->scripts->do_edit($table, $data, $ID);
				redirect(base_url().'mmsadmin/dashboard');
				
				} else {
					
				$this->session->set_userdata(array(
                            'is_admin_login' => false,
                            'Error'			 => true,
                            'Message'		 => 'User name or password is incorrect'
                            )
                        );
                        
                redirect('mmsadmin/home');
					
				}
				
		 }
		 
	/* Logout Script*/	
	public function logout() {
	    $this->session->unset_userdata('Id');
        $this->session->unset_userdata('FirstName');
        $this->session->unset_userdata('LastName');
        $this->session->unset_userdata('EmailAddress');
        $this->session->unset_userdata('AccountType');
        $this->session->unset_userdata('Token');
        $this->session->unset_userdata('is_admin_login');   
        $this->session->sess_destroy();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
		redirect(base_url().'mmsadmin');
    }
   	 	 
		 
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */