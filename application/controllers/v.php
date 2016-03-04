<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class V extends CI_Controller {

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
		
		redirect(base_url().'weareonjobolo');
		
	}
	
	function getpagetitle(){
		$ID = 6;
			
		$Query = "SELECT * FROM pages WHERE ID = '$ID' ";
    	$SQ = $this->db->query($Query);
		$PortInfo = $SQ->result();
		return $PortInfo;

	}
	
	public function i($SEO = NULL){
		
		if($SEO != NULL){
		$Query = "SELECT a.*, b.Name AS ProductName, 
					b.Category, b.Description AS ProdDesc, b.Price, b.Percentage, b.Static, b.LunchSpecial, b.Cust_Percentage, NoDiscount,
					c.PCatName, c.ID AS CatID,
					d.Name AS Location,
					e.ImageName AS CatImage
					FROM restaurants AS a 
					LEFT JOIN products AS b ON a.ID = b.VID 
					LEFT JOIN product_cat AS c ON b.Category = c.ID
					LEFT JOIN location AS d ON a.Location = d.ID
					LEFT JOIN image_cat AS e ON e.VID = b.VID AND e.CatID = b.Category
					WHERE a.Status = 'on' AND b.Status = 'on' AND c.Status = 'on' AND a.SEO = '$SEO' ORDER BY c.SortOrder ASC, b.SortOrder ASC, b.Category ASC";
    	$SQ = $this->db->query($Query);
    	 
    		if($SQ->num_rows() > 0){
    		
    			$data['Info'] = $SQ->result();
    			
    			$Query	=	"SELECT c.PCatName, c.ID AS CatID
								FROM restaurants AS a 
								LEFT JOIN products AS b ON a.ID = b.VID 
								LEFT JOIN product_cat AS c ON b.Category = c.ID
								WHERE a.Status = 'on' AND b.Status = 'on' AND c.Status = 'on' AND a.SEO = '$SEO' GROUP BY c.ID ORDER BY c.SortOrder ASC, b.SortOrder ASC, b.Category ASC";
								
				$CatQ	=	$this->db->query($Query);
				$data['Cats']	=	$CatQ->result();

				$RD	=	$data['Info'][0];
				$Query	=	'SELECT * FROM specialoffers 
						WHERE VID = ' . $RD->ID . ' AND Status = "on"'; 
						$Run	=	$this->db->query($Query);
				$data['SpecialOffers']	=	$Run->result();
				
				/*
				
				
				
				$Query	=	'SELECT * FROM project_details WHERE PID = ' . $RD->ID;
				$Run	=	$this->db->query($Query);
				$data['Gallery']	=	$Run->result();
				
				$Result	=	$this->middle_scripts->get_alltags(10);
				$data['BlogTags']	=	$Result['Result'];
				*/
				
			} else {
			
				redirect("home");
				
			}
			
		$this->load->view('products', $data);
		
		} else {
			
			redirect("home");
			
		}
		
	}
	
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */