<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller {

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
 
	 
	public function index()
	{
		$ID	=	3;
		/* $this->output->cache(10); */
		$Query = "SELECT * FROM pages WHERE ID = '$ID' ";
    	$SQ = $this->db->query($Query);
		$data['Services'] = $SQ->result();
		
		$Result	=	$this->middle_scripts->get_featured_services(6);
		$data['ShortServices']	=	$Result['Result'];
		
		$this->load->view('services', $data);
		
	}
	
	public function i($SEO = NULL){
		
		if(!isset($SEO)){
			redirect("home");
		} else {
			
		$Query = "SELECT * FROM services WHERE Status = 'on' AND SEO = '$SEO'";
		
		
    	$SQ = $this->db->query($Query);
    	 
    		if($SQ->num_rows() > 0){
    		
				$Services = $SQ->result();
				$data['Services']	=	$Services;
				
				$SID	=	$Services[0];
				$Query 	= 	"SELECT * FROM services WHERE Status = 'on' AND ID != " . $SID->ID;
				$Run	=	$this->db->query($Query);
				$data['AllServices']	=	$Run->result();
				
			} else {
			
				redirect("home");
				
			}
		}
		$this->load->view('servicesdetails', $data);
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */