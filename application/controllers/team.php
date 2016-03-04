<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Team extends CI_Controller {

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
 
	 
	public function index()
	{
	
		$Query = "SELECT * FROM team WHERE Status = 'on' ORDER BY SortOrder ASC";
    	$SQ = $this->db->query($Query);
		$data['Team'] = $SQ->result();
		
		$this->load->view("team", $data);
		
	}
	
	public function details($SEO){
		
			
		$Query = "SELECT * FROM team WHERE Status = 'on' AND SEO = '$SEO'";
    	$SQ = $this->db->query($Query);
    	 
    		if($SQ->num_rows() > 0){
    		
				$data['Team'] = $SQ->result();
				
			} else {
			
				redirect("home");
				
			}
		$this->load->view('team_details', $data);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */