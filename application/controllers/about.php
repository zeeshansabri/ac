<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends CI_Controller {

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
		$this->load->model('middle_scripts');
		$this->load->helper('text');	
	}
 
	 
	public function index(){
		
		$ID = 1;
		
		/* $this->output->cache(10); */
		
		$Query = "SELECT * FROM pages WHERE ID = '$ID' ";
    	$SQ = $this->db->query($Query);
		$data['About'] = $SQ->result();
			
		/*
		$Result	=	$this->middle_scripts->get_shortservices(4, 'about');
		$data['ShortServices']	=	$Result['Result'];
		
		$Result	=	$this->middle_scripts->get_projects(5, 'rand');
		$data['Projects']	=	$Result['Result'];
		
		$Result	=	$this->middle_scripts->get_counters();
		$data['Counters']	=	$Result['Result'];
		
		$Query = "SELECT * FROM clients WHERE Status = 'on' ORDER BY SortOrder ASC";
    	$SQ = $this->db->query($Query);
		$data['Clients'] = $SQ->result();
		
		$Result	=	$this->middle_scripts->get_alltags(10);
		$data['BlogTags']	=	$Result['Result'];
		*/	
			
		$this->load->view('about', $data);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */