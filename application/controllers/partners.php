<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Partners extends CI_Controller {

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
	
	$CatQ	=	"SELECT a.* FROM supplier_cat AS a LEFT JOIN suppliers AS b ON a.ID = b.CatID WHERE a.Status = 'on' AND b.Status = 'on' GROUP BY a.ID";
	$CatQR	=	$this->db->query($CatQ);;
	$data['Cat']	=	$CatQR->result();
	
	$SupQ	=	"SELECT a.*, b.CategoryTitle FROM suppliers AS a LEFT JOIN supplier_cat AS b ON a.CatID = b.ID WHERE a.Status = 'on' AND b.Status = 'on'";
	$SupQR	=	$this->db->query($SupQ);
	$data['Sup']	=	$SupQR->result();	
    	
		$this->load->view("supplier", $data);
		
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