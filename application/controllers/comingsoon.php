<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ComingSoon extends CI_Controller {

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
		$this->load->model('middle_scripts');
		$this->load->model('mailer');
		$this->load->helper('text');	
	}
 
	 
	public function index()
	{
		
		$this->load->view('comingsoon');
		
	}
	
	public function do_contact(){
		
		$Name			=		$this->input->post('Name');
		$Email			=		$this->input->post('Email');
		$Message		=		$this->input->post('Message');
		$EmailTo		=		$this->input->post('EmailTo');
		
		
		$this->mailer->contactform($Name, $Email, $Message, $EmailTo);
		
		$this->session->set_userdata('Contact', true);
		
		redirect(base_url());
		
	}
	
	public function changecurrency(){
		
		$this->session->unset_userdata('ConvertTo');
		$Currency	=	$this->input->post('Currency');
		$this->session->set_userdata('ConvertTo', $Currency);
		$this->setConRate();
		
		
	}
	
	public function setConRate(){
		
		
		$from_currency    = 'USD';
		
		if($this->session->userdata('ConvertTo') != ""){
		$to_currency    = $this->session->userdata('ConvertTo');
		} else {
		$to_currency    = 'USD';	
		}
		$amount            = 1;
		$results = $this->scripts->converCurrency($from_currency,$to_currency,$amount);
		$regularExpression     = '#\<span class=bld\>(.+?)\<\/span\>#s';
		preg_match($regularExpression, $results, $finalData);
		$RateDiv 	=	$finalData[0];
		$ExpDiv		=	explode(' ', $RateDiv);
		$ExpDiv		=	explode('>', $ExpDiv[1]);
		
		$this->session->set_userdata('CurrencyConv', $ExpDiv[1]);
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */