<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
		$this->load->library('form_validation');
		$this->load->model('scripts');
	}
	
	public function index(){
		
		$Query = "SELECT b.ExpenseTitle, SUM(a.Amount) AS Total FROM `expense` AS a LEFT JOIN exp_cat AS b ON a.Category = b.ID GROUP BY a.Category";
    	$SQ = $this->db->query($Query);
		$data['Exp'] = $SQ->result();
		
		$data['PageTitle'] = 'Dashboard - MMS Admin';
		if ($this->session->userdata('is_admin_login') == true) {
		$this->load->view('mmsadmin/dashboard',$data);
		} else {
        redirect('mmsadmin/', $data);
        }
		
		
		
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/dashboard.php */