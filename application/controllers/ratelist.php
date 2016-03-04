<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RateList extends CI_Controller {

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
	
		
	$ALLQ			=	"SELECT a.ID, a.Rate, a.RouteType, a.Code, b.Country, b.Image, c.Network FROM ratelist AS a LEFT JOIN country AS b ON a.CountryID = b.ID LEFT JOIN network AS c ON a.NetworkID = c.ID WHERE a.Status = 'on' ORDER BY b.Country ASC";
    $ALLQR			=	$this->db->query($ALLQ);
    $data['ALL'] 	=	$ALLQR->result();
    
    $PKQ			=	"SELECT a.ID, a.Rate, a.Code, a.RouteType, b.Country, b.Image, c.Network FROM ratelist AS a LEFT JOIN country AS b ON a.CountryID = b.ID LEFT JOIN network AS c ON a.NetworkID = c.ID WHERE a.Status = 'on' AND CountryID = 1 ORDER BY a.SortOrder ASC";
    $PKQR				=	$this->db->query($PKQ);
    $data['Pakistan'] 	=	$PKQR->result();
    
    $INQ			=	"SELECT a.ID, a.Rate, a.Code, a.RouteType, b.Country, b.Image, c.Network FROM ratelist AS a LEFT JOIN country AS b ON a.CountryID = b.ID LEFT JOIN network AS c ON a.NetworkID = c.ID WHERE a.Status = 'on' AND CountryID = 2 ORDER BY a.SortOrder ASC";
    $INQR				=	$this->db->query($INQ);
    $data['India'] 	=	$INQR->result();
    
    $BDQ		=	"SELECT a.ID, a.Rate, a.Code, a.RouteType, b.Country, b.Image, c.Network FROM ratelist AS a LEFT JOIN country AS b ON a.CountryID = b.ID LEFT JOIN network AS c ON a.NetworkID = c.ID WHERE a.Status = 'on' AND CountryID = 3 ORDER BY a.SortOrder ASC";
    $BDQR				=	$this->db->query($BDQ);
    $data['Bangladesh'] 	=	$BDQR->result();
    	
		$this->load->view("ratelist", $data);
		
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