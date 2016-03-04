<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class InterConnect extends CI_Controller {

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
    	
		$this->load->view("interconnect", $data);
		
	}
	
	public function do_register(){
		
		$ComName		=	$this->input->post('CompanyName');
		$ComEmail		=	$this->input->post('CompanyEmail');
		$key = 'Marwah-786!';
		$Password = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key),$this->input->post('Password1'), MCRYPT_MODE_CBC, md5(md5($key))));
		$ComAddress		=	$this->input->post('CompanyAddress');
		$ComCity		=	$this->input->post('CompanyCity');
		$ComCountry		=	$this->input->post('CompanyCountry');
		$ComPhone		=	$this->input->post('CompanyPhone');
		$ComFax			=	$this->input->post('CompanyFax');
		$BusinessType	=	$this->input->post('BusinessType');
		$Registration	=	$this->input->post('Registration');
		$Website		=	$this->input->post('Website');
		
		$MainName		=	$this->input->post('MainName');
		$MainEmail		=	$this->input->post('MainEmail');
		$MainPhone		=	$this->input->post('MainPhone');
		$MainFax		=	$this->input->post('MainFax');
		$MainIM			=	$this->input->post('MainIM');
		
		$BillingName	=	$this->input->post('BillingName');
		$BillingEmail	=	$this->input->post('BillingEmail');
		$BillingPhone	=	$this->input->post('BillingPhone');
		$BillingFax		=	$this->input->post('BillingFax');
		$BillingIM		=	$this->input->post('BillingIM');
		
		$RatesName		=	$this->input->post('RatesName');
		$RatesEmail		=	$this->input->post('RatesEmail');
		$RatesPhone		=	$this->input->post('RatesPhone');
		$RatesFax		=	$this->input->post('RatesFax');
		$RatesIM		=	$this->input->post('RatesIM');
		
		$NOCName		=	$this->input->post('NOCName');
		$NOCEmail		=	$this->input->post('NOCEmail');
		$NOCPhone		=	$this->input->post('NOCPhone');
		$NOCFax			=	$this->input->post('NOCFax');
		$NOCIM			=	$this->input->post('NOCIM');
		
		$Beneficiary	=	$this->input->post('Beneficiary');
		$BankName		=	$this->input->post('BankName');
		$BankAddress	=	$this->input->post('BankAddress');
		$BankPhone		=	$this->input->post('BankPhone');
		$AccountNumber	=	$this->input->post('AccountNumber');
		$SwiftCode		=	$this->input->post('SwiftCode');
		$BankCode		=	$this->input->post('BankCode');
		
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
		
		
		
		$Fields			=	array(
		
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
				'BusinessType'		=>		$BusinessType,
				'Beneficiary'		=>		$Beneficiary,
				'BankName'			=>		$BankName,
				'BankAddress'		=>		$BankAddress,
				'BankPhone'			=>		$BankPhone,
				'AccountNumber'		=>		$AccountNumber,
				'SwiftCode'			=>		$SwiftCode,
				'BeneficiaryBankCode'	=>	$BankCode,
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
				'DialPattern'		=>		$DialPattern,
				'Status'			=>		'off'
		);
		
		$table			=		'users';
		
		$query = $this->scripts->do_add($table, $Fields);
		
		if($query['Created'] > 0){
		
		$UserID			=		$query['Created'];
		$table			=		'contactdetails';
		
			$MainData		=		array(
				'Name'		=>		$MainName,
				'Email'		=>		$MainEmail,
				'Phone'		=>		$MainPhone,
				'Fax'		=>		$MainFax,
				'IM'		=>		$MainIM,
				'UserID'	=>		$UserID,
				'Type'		=>		'Main'				
			);
			
			$this->scripts->do_add($table, $MainData);
			
			$BillingData	=		array(
				'Name'		=>		$BillingName,
				'Email'		=>		$BillingEmail,
				'Phone'		=>		$BillingPhone,
				'Fax'		=>		$BillingFax,
				'IM'		=>		$BillingIM,
				'UserID'	=>		$UserID,
				'Type'		=>		'Billing'				
			);
			
			$this->scripts->do_add($table, $BillingData);
			
			$RatesData	=		array(
				'Name'		=>		$RatesName,
				'Email'		=>		$RatesEmail,
				'Phone'		=>		$RatesPhone,
				'Fax'		=>		$RatesFax,
				'IM'		=>		$RatesIM,
				'UserID'	=>		$UserID,
				'Type'		=>		'Rates'				
			);
			
			$this->scripts->do_add($table, $RatesData);
			
			$NOCData	=		array(
				'Name'		=>		$NOCName,
				'Email'		=>		$NOCEmail,
				'Phone'		=>		$NOCPhone,
				'Fax'		=>		$NOCFax,
				'IM'		=>		$NOCIM,
				'UserID'	=>		$UserID,
				'Type'		=>		'NOC'				
			);
			
			$this->scripts->do_add($table, $NOCData);
			
			$table = 'reference';
			
			for($i = 1; $i < 4; $i++){
				
				$RefName	=		$this->input->post('RefName'.$i);
				$RefAddress	=		$this->input->post('RefAddress'.$i);
				$RefEmail	=		$this->input->post('RefEmail'.$i);
				$RefWebsite	=		$this->input->post('RefWebsite'.$i);
				
				$RefData	=	array(
						
						'Company'		=>		$RefName,
						'Address'		=>		$RefAddress,
						'EmailAddress'	=>		$RefEmail,
						'Website'		=>		$RefWebsite,
						'UserID'		=>		$UserID
						
				);
				
				$this->scripts->do_add($table, $RefData);
				
			}
				
		
			 $this->session->set_userdata(array(
					'Success'		=> 'Thanks for the filling the inter-connect form. We will be in touch soon'
				)
			);
		}  
		
		redirect("interconnect");
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */