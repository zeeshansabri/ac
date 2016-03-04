<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice extends CI_Controller {

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
		
		$data['Section'] = 'dashboard.php';	
			
		$this->load->view('userarea', $data);
	}
	
	
	public function view_invoice($ID){
	  
	  	  $Code =  base64_decode($this->input->get('Code'));
	  
		  $Query 	=	"SELECT a.*, 
							d.Country, 
							e.Network, 
							c.Rate, 
							f.RouteType, f.Code, 
							h.OrderTotal,  
							i.Minutes, i.Amount, min(i.StartDate) AS StartDate, max(i.EndDate) AS EndDate,
							d2.cnt AS RouteSum, d2.mcnt AS MinutesSum
							from orders AS a
							
						LEFT JOIN order_routes AS b ON a.ID = b.OrderID
						LEFT JOIN userrate     AS c ON c.ID = b.RouteID
					    LEFT JOIN ratelist     AS f ON f.ID = c.RateListID
						LEFT JOIN country      AS d ON d.ID = f.CountryID
						LEFT JOIN network      AS e ON e.ID = f.NetworkID
						LEFT JOIN order_details AS h ON h.OrderID = a.ID
						LEFT JOIN transactions AS i ON i.RouteID = c.ID
						LEFT JOIN (SELECT RouteID, SUM(Amount) AS cnt, SUM(Minutes) AS mcnt FROM transactions WHERE Status = '$ID' GROUP BY 1) AS d2 ON c.ID = d2.RouteID
						WHERE a.ID = '$ID' AND a.Code = '$Code' AND i.Status =  '$ID' GROUP BY i.RouteID";
			$SQuery	=	$this->db->query($Query);
			$data['Data_Array']	=	$SQuery->result();
		  
		  if($SQuery->num_rows >= 1){
		  
		  $Query 	= 	"SELECT ID, Address, Phone, EmailTo, Logo FROM settings WHERE ID = 1";
		  $Run 	=	$this->db->query($Query);
		  
		  $data['Company']	=	$Run->result();
		 
		  $Query	=	"SELECT a.ID, a.CompanyName, a.Address, a.Phone, a.EmailAddress, a.AccountType FROM users AS a
		  				LEFT JOIN orders AS b ON a.ID = b.UserID
		  				WHERE b.ID = $ID";
		  $Run		=	$this->db->query($Query);
		  $Client	=	$Run->result();
		  $data['Client']	=	$Client;
		
		$Query 	=	"SELECT OrderTotal, Discount, Dispute, PrevBal, PrevPay, InvType, TDiscount, Consumed, TotalPaid FROM order_details WHERE OrderID = $ID";
		$Run	=	$this->db->query($Query);
		$data['Details']	=	$Run->result();
		
		$Query	=	"SELECT SUM(Consumed) AS Consumed, SUM(TotalPaid) AS TotalPaid FROM order_details AS a
						LEFT JOIN orders AS b ON a.OrderID = b.ID
						WHERE OrderID < $ID AND b.UserID = " . $this->session->userdata('ID');
		$Run	=	$this->db->query($Query);
		$data['PreStat']	=	$Run->result();
		
		$data['OrderID'] = $ID;
		  
		  $folder = 'users';
		  
		  $this->load->view('mmsadmin/'.$folder.'/invoice', $data);
		  
		  } else {
			  
			  redirect(base_url().'userarea');
			  
		  }
	  
	  }

	
		public function view_invoice_pdf($ID){
	  
	  	  $Code =  base64_decode($this->input->get('Code'));
	  
		  $Query 	=	"SELECT a.*, 
							d.Country, 
							e.Network, 
							c.Rate, 
							f.RouteType, f.Code, 
							h.OrderTotal,  
							i.Minutes, i.Amount, min(i.StartDate) AS StartDate, max(i.EndDate) AS EndDate,
							d2.cnt AS RouteSum, d2.mcnt AS MinutesSum
							from orders AS a
							
						LEFT JOIN order_routes AS b ON a.ID = b.OrderID
						LEFT JOIN userrate     AS c ON c.ID = b.RouteID
					    LEFT JOIN ratelist     AS f ON f.ID = c.RateListID
						LEFT JOIN country      AS d ON d.ID = f.CountryID
						LEFT JOIN network      AS e ON e.ID = f.NetworkID
						LEFT JOIN order_details AS h ON h.OrderID = a.ID
						LEFT JOIN transactions AS i ON i.RouteID = c.ID
						LEFT JOIN (SELECT RouteID, SUM(Amount) AS cnt, SUM(Minutes) AS mcnt FROM transactions WHERE Status = '$ID' GROUP BY 1) AS d2 ON c.ID = d2.RouteID
						WHERE a.ID = '$ID' AND a.Code = '$Code' AND i.Status =  '$ID' GROUP BY i.RouteID";
			$SQuery	=	$this->db->query($Query);
			$data['Data_Array']	=	$SQuery->result();
		  
		  if($SQuery->num_rows > 1){
		  
		  $Query 	= 	"SELECT ID, Address, Phone, EmailTo, Logo FROM settings WHERE ID = 1";
		  $Run 	=	$this->db->query($Query);
		  
		  $data['Company']	=	$Run->result();
		 
		  $Query	=	"SELECT a.ID, a.CompanyName, a.Address, a.Phone, a.EmailAddress, a.AccountType FROM users AS a
		  				LEFT JOIN orders AS b ON a.ID = b.UserID
		  				WHERE b.ID = $ID";
		  $Run		=	$this->db->query($Query);
		  $data['Client']	=	$Run->result();
		
		$Query 	=	"SELECT OrderTotal, Discount, Dispute, PrevBal, PrevPay, TDiscount FROM order_details WHERE OrderID = $ID";
		$Run	=	$this->db->query($Query);
		$data['Details']	=	$Run->result();
		
		$data['OrderID'] = $ID;
		  
		  $folder = 'users';
		  
		  $this->load->view('userarea/invoice_report', $data);
		  
		  $filename = 'invoice-'.$ID;
		  $pdfFilePath = "assets/upload/invoice/$filename.pdf";
		  $data['page_title'] = 'MarwahTech Invoice - ' . $filename; // pass data to the view
	
		  if (file_exists($pdfFilePath) != FALSE){
			  unlink($pdfFilePath);
		  }
		  
		  ini_set('memory_limit','92M'); 
	    
	    $html =	$this->load->view('userarea/invoice_report', $data, true); 
	     
		$this->load->library('pdf');
		$pdf = $this->pdf->load();
	
		$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 
	
		$mpdf->SetDisplayMode('fullpage');
	
		$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
	
		// LOAD a stylesheet
		$stylesheet = file_get_contents('assets/web/js/mainmenu/bootstrap.min.css');
		$stylesheet2 = file_get_contents('assets/web/invoice/main.css');
	
		$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
		$mpdf->WriteHTML($stylesheet2,1);
		$mpdf->WriteHTML($html,2);
	
		$mpdf->Output('assets/upload/invoice/'.$filename.'.pdf','F');
		
		redirect(PATH."upload/invoice/$filename.pdf"); 
		exit;
		  
		  } else {
			  
			  redirect(base_url().'userarea');
			  
		  }
	  
	  }
	
		
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */