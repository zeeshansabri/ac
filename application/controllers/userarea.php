<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserArea extends CI_Controller {

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
		
		$this->checklogin();
		
		$data['Section'] = 'dashboard.php';	
			
		$this->load->view('userarea', $data);
	}
	
	public function myrates(){
		
		$this->checklogin();
		$ID = $this->session->userdata('ID');
			
		$Query = "SELECT a.ID, a.Status, a.Rate, b.Country, d.Code, d.RouteType, c.Network, e.AccountType 
						FROM userrate AS a 
						LEFT JOIN ratelist AS d ON a.RateListID = d.ID 
						LEFT JOIN country AS b ON d.CountryID = b.ID 
						LEFT JOIN network AS c ON d.NetworkID = c.ID
						LEFT JOIN users AS e ON a.UserID = e.ID
						WHERE a.Status = 'on' AND a.UserID = " . $ID ;
    	$SQ = $this->db->query($Query);
		$data['Data_Array'] = $SQ->result();
		
		$data['Section']	=	'specialrates.php';	
		
			
		$this->load->view('userarea', $data);
	}
	
	/* ORDER START */
	
	public function placeorder($ID){
	
		$OrderID = base64_decode($ID);
		$this->checklogin();
		$ID = $this->session->userdata('ID');
		
		$Query = "SELECT a.ID, a.Status, a.Rate, 
						 b.Country, 
						 d.Code, d.RouteType, 
						 c.Network,
						 e.SignalingIP,
						 e.MediaIP,
						 e.Manufacturer,
						 e.Model,
						 e.SoftwareVersion,
						 e.Protocol,
						 e.Ports,
						 e.CodecPreference,
						 e.DTMFRelay,
						 e.Fax,
						 e.DialPattern
						 
						FROM userrate AS a 
						LEFT JOIN ratelist AS d ON a.RateListID = d.ID 
						LEFT JOIN country AS b ON d.CountryID = b.ID 
						LEFT JOIN network AS c ON d.NetworkID = c.ID
						LEFT JOIN users AS e ON a.UserID = e.ID
						WHERE a.Status = 'on' AND a.UserID = " . $ID;
		$SQ = $this->db->query($Query);
		$data['Data_Array'] = $SQ->result();
		
		$data['Section'] =	'placeorder.php';
		$this->load->view('userarea', $data);
		
		
	}
	
	public function do_placeorder($ID){
		
		$Country		=	$this->input->post('Country');
		$Network		=	$this->input->post('Network');
		$Rate			=	$this->input->post('Rate');
		$RouteType		=	$this->input->post('RouteType');
		$Code			=	$this->input->post('Code');	
		
		$PrefixReq		=	$this->input->post('PrefixReq');
		$PortsReq		=	$this->input->post('PortsReq');
		$RoutesReq		=	$this->input->post('SR');		
		
		$Amount			=	$this->input->post('Amount');


		$SignalingIP	=	$this->input->post('SignalingIP');
		$MediaIP		=	$this->input->post('MediaIP');
		$Manufacturer	=	$this->input->post('Manufacturer');
		$Model			=	$this->input->post('Model');
		$Software		=	$this->input->post('Software');
		$Protocol		=	$this->input->post('Protocol');
		$Ports			=	$this->input->post('Ports');
		$Codec			=	$this->input->post('Codec');
		$DTMF			=	$this->input->post('DTMF');
		$FAX			=	$this->input->post('FAX');
		$DialPattern	=	$this->input->post('DialPattern');
		
		$UserID			=	$this->session->userdata('ID');
		$InvType		=	$this->session->userdata('AccountType');
		
		$RateID			=	$ID;
		$Date			=	date("Y-m-d h:i");
		
		$table			=	'orders';
		
		$OrderFields	= 	array(
		
				'UserID'	=>		$UserID,
				'RateID'	=>		$RateID,
				'CreatedDate'	=>	$Date,
				'Status'	=>		'pending'
				
		);
		
		$Query	=	$this->scripts->do_add($table, $OrderFields);
		
		if(!empty($Query['Created'])){
		
			foreach($RoutesReq as $RR){
			$table = 'order_routes';
				$RR			=	array(
					'OrderID'		=>		$Query['Created'],
					'RouteID'		=>		$RR
				);
				$this->scripts->do_add($table, $RR);
			}
			
			$SumQuery	=	"SELECT SUM(t2.omt) AS Rec, SUM(d2.smt) AS Paid, SUM(t2.dmt) AS TotalDiscount
							 FROM `orders` AS a 
								LEFT JOIN order_details AS b on a.ID = b.OrderID 
							  	LEFT JOIN users AS c on c.ID = a.UserID 
							  	LEFT JOIN (SELECT OrderID, SUM(OrderTotal) as omt, SUM(Discount) as dmt FROM order_details GROUP BY 1) AS t2 ON t2.OrderID = a.ID
							  	LEFT JOIN (SELECT UserID, SUM(Amount) AS smt FROM payments GROUP BY 1) AS d2 on d2.UserID = c.ID 
							  WHERE c.ID = $UserID AND a.ID < " . $Query['Created'];
							  
		  $Run		=	$this->db->query($SumQuery);
		  $PreviousTotal	=	$Run->result();
		  $PrevT	=	$PreviousTotal[0];
			
			$DetailFields	=	array(
			
				'OrderID'		=>	$Query['Created'],
				'Rate'			=>	$Rate,
				'OrderTotal'	=>	$Amount,
				'InvType'		=>	$InvType,
				'Country'		=>	$Country,
				'Network'		=>	$Network,
				'RouteType'		=>	$RouteType,
				'Code'			=>	$Code,
				'OrderTotal'	=>	$Amount,
				'PrefixReq'		=>	$PrefixReq,
				'PortsReq'		=>	$PortsReq,
				'SignalingIP'	=>	$SignalingIP,
				'MediaIP'		=>	$MediaIP,
				'Manufacturer'	=>	$Manufacturer,
				'Model'			=>	$Model,
				'SoftwareVersion'	=>	$Software,
				'Protocol'		=>	$Protocol,
				'Ports'			=>	$Ports,
				'Codec'			=>	$Codec,
				'DTMFRelay'		=>	$DTMF,
				'FAX'			=>	$FAX,
				'DialPattern'	=>	$DialPattern,
				
			);
			
			if(!empty($PrevT->Rec)){
			  $DetailFields['PrevBal']	=	$PrevT->Rec;
			  }
			  
			if(!empty($PrevT->Paid)){
			  $DetailFields['PrevPay']	=	$PrevT->Paid;
			 }
			 
			 if(!empty($PrevT->TotalDiscount)){
			  $DetailFields['TDiscount']	=	$PrevT->TotalDiscount;
			 }

		
		$table = 'order_details';
		
		$this->scripts->do_add($table, $DetailFields);
		
		$this->session->set_userdata("OrderStatus", "Success");
		
		$this->load->model('mailer');
		$this->mailer->neworderadmin($this->session->userdata('CompanyName'), $Query['Created']);
		$this->mailer->neworderuser($this->session->userdata('CompanyName'), $Query['Created'], $this->session->userdata('CompanyEmail'));
		
		redirect(base_url().'userarea/myorders');
			
		}
		
	}
	
	public function myorders(){
	
		$this->checklogin();
		$ID = $this->session->userdata('ID');
		
		$Query 	=	"SELECT a.ID, a.CreatedDate, a.Status, a.Code AS PassCode, b.OrderTotal, 
					e.Rate, f.RouteType, f.Code, g.Country, h.Network, d2.cnt AS OrderCount, d3.tcnt AS TransCount 
					FROM orders AS a
					    LEFT JOIN order_details AS b ON a.ID = b.OrderID
					    LEFT JOIN order_routes AS d ON a.ID = d.OrderID
					    LEFT JOIN (SELECT OrderID, COUNT(*) AS cnt FROM order_routes GROUP BY 1) AS d2 ON a.ID = d2.OrderID
					    LEFT JOIN (SELECT Status, COUNT(Status) AS tcnt FROM transactions GROUP BY 1) AS d3 ON d3.Status = a.ID
					    LEFT JOIN userrate AS e ON e.ID = d.RouteID
					    LEFT JOIN ratelist AS f ON f.ID = e.RateListID
					    LEFT JOIN country AS g ON f.CountryID = g.ID
					    LEFT JOIN network AS h ON f.NetworkID = h.ID
					    WHERE a.UserID = " . $ID . " ORDER BY a.ID DESC";
					    
		$SQuery	=	$this->db->query($Query);
		$data['Data_Array']	=	$SQuery->result();
		
		$data['Section']	= 	'myorders.php';
		$this->load->view("userarea", $data);
	}
	
	public function myinvoices(){
	
		$this->checklogin();
		$ID = $this->session->userdata('ID');
		
		$Query 	=	"SELECT a.ID, a.CreatedDate, a.Status, a.Code AS PassCode, b.OrderTotal, 
					e.Rate, f.RouteType, f.Code, g.Country, h.Network, d2.cnt AS OrderCount, d3.tcnt AS TransCount 
					FROM orders AS a
					    LEFT JOIN order_details AS b ON a.ID = b.OrderID
					    LEFT JOIN order_routes AS d ON a.ID = d.OrderID
					    LEFT JOIN (SELECT OrderID, COUNT(*) AS cnt FROM order_routes GROUP BY 1) AS d2 ON a.ID = d2.OrderID
					    LEFT JOIN (SELECT Status, COUNT(Status) AS tcnt FROM transactions GROUP BY 1) AS d3 ON d3.Status = a.ID
					    LEFT JOIN userrate AS e ON e.ID = d.RouteID
					    LEFT JOIN ratelist AS f ON f.ID = e.RateListID
					    LEFT JOIN country AS g ON f.CountryID = g.ID
					    LEFT JOIN network AS h ON f.NetworkID = h.ID
					    WHERE a.UserID = " . $ID . " ORDER BY a.ID DESC";
					    
		$SQuery	=	$this->db->query($Query);
		$data['Data_Array']	=	$SQuery->result();
		
		$data['Section']	= 	'myinvoices.php';
		$this->load->view("userarea", $data);
	}
	
	public function myusage(){
	
		$this->checklogin();
		$ID = $this->session->userdata('ID');
		
		$Query = 'SELECT a.ID, a.StartDate, a.EndDate, a.Amount, a.Minutes, a.Status, b.RouteType,  c.Country, d.Network, f.Rate FROM transactions AS a
						LEFT JOIN userrate	AS f ON f.ID = a.RouteID
						LEFT JOIN ratelist 	AS b ON f.RateListID = b.ID
						LEFT JOIN country 	AS c ON c.ID = b.CountryID
						LEFT JOIN network	AS d ON d.ID = b.NetworkID
						LEFT JOIN users 	AS e ON e.ID = a.UserID
						 WHERE a.UserID = ' . $ID . ' ORDER BY StartDate DESC';
			$Run	=	$this->db->query($Query);
		$data['Data_Array']	=	$Run->result();
		
		$data['Section']	= 	'myusage.php';
		$this->load->view("userarea", $data);
	}
	
	public function view_invoice($ID){
	
	$this->checklogin();
	$UserID = $this->session->userdata('ID');
				
	$OrderID = base64_decode($ID);
	$filename = 'invoice-'.$OrderID;
	$pdfFilePath = "assets/upload/invoice/$filename.pdf";
	$data['page_title'] = 'MarwahTech Invoice - ' . $filename; // pass data to the view
	
		$Query 	=	"SELECT a.*, d.Country, e.Network, c.Rate, f.RouteType, f.Code, g.CompanyName, g.Address, g.Phone, g.EmailAddress, g.AccountType, h.OrderTotal, h.PrevBal, h.PrevPay, h.Discount, h.InvType, h.TDiscount from orders AS a
						LEFT JOIN order_routes AS b ON a.ID = b.OrderID
						LEFT JOIN userrate     AS c ON c.ID = b.RouteID
					    LEFT JOIN ratelist     AS f ON f.ID = c.RateListID
						LEFT JOIN country      AS d ON d.ID = f.CountryID
						LEFT JOIN network      AS e ON e.ID = f.NetworkID
						LEFT JOIN users		   AS g ON g.ID = a.UserID
						LEFT JOIN order_details AS h ON h.OrderID = a.ID
						WHERE a.ID = " . $OrderID . ' AND a.UserID = ' . $UserID;
						
		$SQuery	=	$this->db->query($Query);
		$data['Data_Array']	=	$SQuery->result();
		
		if($SQuery->num_rows != 0){
		
		$Query 	= 	"SELECT Address, Phone, EmailTo, Logo FROM settings WHERE ID = 1";
		$Run 	=	$this->db->query($Query);
		$data['Company']	=	$Run->result();
		
		if (file_exists($pdfFilePath) != FALSE){
		
		unlink($pdfFilePath);
		
		}
		
	    ini_set('memory_limit','92M'); 
	    $html =	$this->load->view('userarea/readyinvoice', $data, true); 
	     
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
		
		redirect(PATH."/upload/invoice/$filename.pdf"); 
		exit;
		    
	 
	 } else {
		 
		 echo "<h2>Sorry, Invoice not found please check with our accounts department</h2>";
		 
	 }
 	
		
	}

	
	/* ORDER ENDS *./
	
	
	/* REQUEST RATE START */
	
	public function requestrate(){
		
		$data['Section']	=	'requestrate.php';
		$this->load->view('userarea', $data);
	}
	
	public function do_request(){
	
		$this->load->model('mailer');
		$Country 	= 	$this->input->post('Country');
		$Network	=	$this->input->post('Network');
		$RouteType	=	$this->input->post('RouteType');
		$Prefix 	=	$this->input->post('Prefix');
		$Email		=	$this->session->userdata('CompanyEmail');
		$CompanyName	=	$this->session->userdata('CompanyName');
		
		$this->mailer->requestrate($Country, $Network, $RouteType, $Prefix, $Email, $CompanyName);
		
		$this->session->set_userdata(
				array(
					'Success' => true,
					'SuccessMessage'	=>	'Thanks for your request, one of our team member will be in touch with you soon'
					));
		
		redirect(base_url()."userarea/requestrate");
		
	}
	
	/* REQUEST RATE ENDS */
	
	/* TECHINICAL START */
	
	public function technical(){
		
		$data['Section']	=	'technical.php';
		$this->load->view('userarea', $data);
	}
	
	/* TECHINICAL ENDS */
	
	/* ACCOUNT DETAILS START */
	
	public function accountdetails(){
	
		$this->checklogin();
		
		$UserID		=	$this->session->userdata('ID');
		
		$Query		=		"SELECT * FROM users WHERE ID = " . $UserID;
		$Run		=		$this->db->query($Query);
		$data['Info']	=	$Run->result();
		
		
		$data['Section']	=	'account_details.php';
		$this->load->view('userarea', $data);
	}
	
	public function do_accountdetails(){
	
		$this->checklogin();
		
		$ComName		=	$this->input->post('CompanyName');
		$ComEmail		=	$this->input->post('CompanyEmail');
		$key = 'Marwah-786!';
		$Password 		= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key),$this->input->post('Password1'), MCRYPT_MODE_CBC, md5(md5($key))));
		$ComAddress		=	$this->input->post('CompanyAddress');
		$ComCity		=	$this->input->post('CompanyCity');
		$ComCountry		=	$this->input->post('CompanyCountry');
		$ComPhone		=	$this->input->post('CompanyPhone');
		$ComFax			=	$this->input->post('CompanyFax');
		$BusinessType	=	$this->input->post('BusinessType');
		$Registration	=	$this->input->post('Registration');
		$Website		=	$this->input->post('Website');
		
		$UserID		=	$this->session->userdata('ID');
		
		$Field_array	=	array(
		
							'EmailAddress'		=>		$ComEmail,
							'Password'			=>		$Password,
							'CompanyName'		=>		$ComName,
							'Address'			=>		$ComAddress,
							'City'				=>		$ComCity,
							'Country'			=>		$ComCountry,
							'Phone'				=>		$ComPhone,
							'FaxNumber'			=>		$ComFax,
							'Website'			=>		$Website,
							'RegNumber'			=>		$Registration,
							'BusinessType'		=>		$BusinessType
								
							);
		$table = 'users';					
		$this->scripts->do_edit($table, $Field_array, $UserID);
		
		$this->session->set_userdata(
									array(
									'Success'	=>	true,
									'SuccessMessage'	=>	'Account details updated'
									)
									);
		
		redirect(base_url().'userarea/accountdetails');
		
	}
	
	/* ACCOUNT DETAILS ENDS */
	
	/* USER TECHINICAL DETAILS */
	
	public function utechnical(){
	
		$this->checklogin();
		
		$UserID		=	$this->session->userdata('ID');
		
		$Query		=		"SELECT * FROM users WHERE ID = " . $UserID;
		$Run		=		$this->db->query($Query);
		$data['Info']	=	$Run->result();
		
		
		$data['Section']	=	'USER_techinical.php';
		$this->load->view('userarea', $data);
	}
	
	public function do_utechnical(){
	
		$this->checklogin();
		
		$SignalingIP	=	$this->input->post('SignalingIP');
		$MediaIP		=	$this->input->post('MediaIP');
		$Manufacturer	=	$this->input->post('Manufacturer');
		$Model			=	$this->input->post('Model');
		$Software		=	$this->input->post('Software');
		$Protocol		=	$this->input->post('Protocol');
		$Ports			=	$this->input->post('Ports');
		$Codec			=	$this->input->post('Codec');
		$DTMF			=	$this->input->post('DTMF');
		$FAX			=	$this->input->post('FAX');
		$DialPattern	=	$this->input->post('DialPattern');
		
		$UserID		=	$this->session->userdata('ID');
		
		$Field_array	=	array(
		
							'SignalingIP'		=>		$SignalingIP,
							'MediaIP'			=>		$MediaIP,
							'Manufacturer'		=>		$Manufacturer,
							'Model'				=>		$Model,
							'SoftwareVersion'	=>		$Software,
							'Protocol'			=>		$Protocol,
							'Ports'				=>		$Ports,
							'CodecPreference'	=>		$Codec,
							'DTMFRelay'			=>		$DTMF,
							'FAX'				=>		$FAX,
							'DialPattern'		=>		$DialPattern
		
							);
							
		$table	=	'users';
		
		$this->scripts->do_edit($table, $Field_array, $UserID);
		
		$this->session->set_userdata(
									array(
									'Success'	=>	true,
									'SuccessMessage'	=>	'Technical details updated'
									)
									);
		
		redirect(base_url().'userarea/utechnical');
		
	}
	
	
	/* USER TECHINICAL DETAILS END */


	/* CONTACT DETAILS */
	
	/* MAIN STARTS */
	
	public function maincontact(){
	
		$this->checklogin();
		
		$UserID		=	$this->session->userdata('ID');
		
		$Query		=		"SELECT * FROM contactdetails WHERE UserID = " . $UserID . " AND Type = 'Main'";
		$Run		=		$this->db->query($Query);
		$data['Info']	=	$Run->result();
		
		
		$data['Section']	=	'contact.php';
		$data['update']		=	'do_maincontact';
		$data['CTitle']		=	'Main';
		$this->load->view('userarea', $data);
	}
	
	/* MAIN ENDS */
	
	/* Billing STARTS */
	
	public function billingcontact(){
	
		$this->checklogin();
		
		$UserID		=	$this->session->userdata('ID');
		
		$Query		=		"SELECT * FROM contactdetails WHERE UserID = " . $UserID . " AND Type = 'Billing'";
		$Run		=		$this->db->query($Query);
		$data['Info']	=	$Run->result();
		
		
		$data['Section']	=	'contact.php';
		$data['update']		=	'do_maincontact';
		$data['CTitle']		=	'Billing';
		$this->load->view('userarea', $data);
	}
	
	/* Billing ENDS */
	
	/* Rates STARTS */
	
	public function ratescontact(){
	
		$this->checklogin();
		
		$UserID			=		$this->session->userdata('ID');
		
		$Query			=		"SELECT * FROM contactdetails WHERE UserID = " . $UserID . " AND Type = 'Rates'";
		$Run			=		$this->db->query($Query);
		$data['Info']	=		$Run->result();
		
		
		$data['Section']	=	'contact.php';
		$data['update']		=	'do_maincontact';
		$data['CTitle']		=	'Rates';
		$this->load->view('userarea', $data);
	}
	
	/* Rates ENDS */
	
	/* NOC STARTS */
	
	public function noccontact(){
	
		$this->checklogin();
		
		$UserID			=		$this->session->userdata('ID');
		
		$Query			=		"SELECT * FROM contactdetails WHERE UserID = " . $UserID . " AND Type = 'NOC'";
		$Run			=		$this->db->query($Query);
		$data['Info']	=		$Run->result();
		
		
		$data['Section']	=	'contact.php';
		$data['update']		=	'do_maincontact';
		$data['CTitle']		=	'NOC';
		$this->load->view('userarea', $data);
	}
	
	/* NOC ENDS */
	
	public function do_maincontact(){
	
		$this->checklogin();
		
		$CName			=	$this->input->post('CName');
		$CEmail			=	$this->input->post('CEmail');
		$CPhone			=	$this->input->post('CPhone');
		$CFax			=	$this->input->post('CFax');
		$CIM			=	$this->input->post('CIM');
		
		$UserID		=	$this->session->userdata('ID');
		$ID			=	$this->input->post('ID');
		
		$Field_array	=	array(
		
							'Name'		=>		$CName,
							'Email'		=>		$CEmail,
							'Phone'		=>		$CPhone,
							'Fax'		=>		$CFax,
							'IM'		=>		$CIM
		
							);
							
		$table	=	'contactdetails';
		
		$this->scripts->do_edit($table, $Field_array, $ID);
		
		$this->session->set_userdata(
									array(
									'Success'	=>	true,
									'SuccessMessage'	=>	'Contact details updated'
									)
									);
		
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		
	}
	
	
	/* CONTACT END */
	
	/* Referecne Starts */
	
	public function references(){
		
		$this->checklogin();
		
		$UserID			=		$this->session->userdata('ID');
		
		$Query			=		"SELECT * FROM reference WHERE UserID = " . $UserID;
		$Run			=		$this->db->query($Query);
		$data['Info']	=		$Run->result();
		
		
		$data['Section']	=	'references.php';
		$this->load->view('userarea', $data);
		
	}
	
	public function add_references(){
		
		$this->checklogin();
		
		$UserID			=		$this->session->userdata('ID');
		
		$data['Section']	=	'add_references.php';
		$this->load->view('userarea', $data);
		
	}
	
	public function do_references(){
	
		$this->checklogin();
		
		$RName			=	$this->input->post('RName');
		$RAddress		=	$this->input->post('RAddress');
		$REmail			=	$this->input->post('REmail');
		$RWebsite		=	$this->input->post('RWebsite');
		
		$UserID		=	$this->session->userdata('ID');
		
		$Field_array	=	array(
							
							'UserID'			=>		$UserID,
							'Company'			=>		$RName,
							'Address'			=>		$RAddress,
							'EmailAddress'		=>		$REmail,
							'Website'			=>		$RWebsite,
							'Status'			=>		'off'
		
							);
							
		$table	=	'reference';
		
		$this->scripts->do_add($table, $Field_array);
		
		$this->session->set_userdata(
									array(
									'Success'	=>	true,
									'SuccessMessage'	=>	'New References added'
									)
									);
		
		redirect(base_url().'userarea/references');
		
	}
	
	
	/* Reference Ends */
	
	
	public function checklogin(){
		
		if($this->session->userdata('User_login') != true){
			redirect(base_url().'login');
		}
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */