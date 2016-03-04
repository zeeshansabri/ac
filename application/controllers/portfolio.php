<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portfolio extends CI_Controller {

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
		$this->load->library("pagination");	
		
	}
 
	 
	public function index()
	{
		$this->output->cache(10);
		
		$data['PortInfo']	=	$this->getpagetitle();
		
		$Result =	$this->middle_scripts->get_all_port_cat();
		$data['PortCat']	=	$Result['Result'];
		
		$Result				=	$this->middle_scripts->get_allport('portfolio', 12, 3);
		
		$data['Portfolio']	=	$Result['PResult']['Result'];
		$data["links"] 		=	$Result['links'];
		
		$Result	=	$this->middle_scripts->get_alltags(10);
		$data['BlogTags']	=	$Result['Result'];
		
		$this->load->view("portfolio", $data);
		
	}
	
	function getpagetitle(){
		$ID = 6;
			
		$Query = "SELECT * FROM pages WHERE ID = '$ID' ";
    	$SQ = $this->db->query($Query);
		$PortInfo = $SQ->result();
		return $PortInfo;

	}
	
	public function details($SEO){
		
		$this->output->cache(10);
			
		$Query = "SELECT a.*, b.CategoryTitle FROM projects AS a LEFT JOIN project_cat AS b ON a.CatID = b.ID WHERE a.Status = 'on' AND a.SEO = '$SEO'";
    	$SQ = $this->db->query($Query);
    	 
    		if($SQ->num_rows() > 0){
    		
				$data['Project'] = $SQ->result();
				$RD	=	$data['Project'][0];
				
				$Query	=	'SELECT * FROM projects 
						WHERE Status = "on" 
						AND SEO != "' . $SEO . '"
						AND CatID = ' . $RD->CatID;
						$Run	=	$this->db->query($Query);
				$data['OtherProjects']	=	$Run->result();
				
				$Query	=	'SELECT * FROM project_details WHERE PID = ' . $RD->ID;
				$Run	=	$this->db->query($Query);
				$data['Gallery']	=	$Run->result();
				
				$Result	=	$this->middle_scripts->get_alltags(10);
				$data['BlogTags']	=	$Result['Result'];
				
			} else {
			
				redirect("home");
				
			}
		$this->load->view('projectdetails', $data);
	}
	
		
	function i(){
	
		$this->output->cache(10);
	
		$data['PortInfo']	=	$this->getpagetitle();
		
		$Result =	$this->middle_scripts->get_all_port_cat();
		$data['PortCat']	=	$Result['Result'];
		
		$Result				=	$this->middle_scripts->get_allport('portfolio', 12, 3);
		
		$data['Portfolio']	=	$Result['PResult']['Result'];
		$data["links"] 		=	$Result['links'];
		
		$Result	=	$this->middle_scripts->get_alltags(10);
		$data['BlogTags']	=	$Result['Result'];
		
		$this->load->view("portfolio", $data);
		
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */