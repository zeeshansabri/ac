<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller {

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
		$this->load->model('mailer');
		$this->load->model('middle_scripts');
		$this->load->helper('text');	
	}
 
	 
	public function index(){
		
		$ID = 8;
		
		/* $this->output->cache(10); */
			
		$Query = "SELECT * FROM pages WHERE ID = '$ID' ";
    	$SQ = $this->db->query($Query);
		$data['Contact'] = $SQ->result();	
		
			
		$this->load->view('contact', $data);
	}
	
	public function do_contact(){
		
		
		if(!$_POST) exit;


		// Configuration option.
		// Enter the email address that you want to emails to be sent to.
		// Example $address = "john.doe@yourdomain.com";

		$emailTo = "info@jobolo.pk";


		// Email address verification, do not edit.
		function isEmail($email) {
			return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
			}

			if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");

			$name = $_POST['name'];
			$from = $_POST['email'];
			$subject = 'Message From Jobolo';
			$phone	=	$_POST['phone'];
			$message= $_POST['message'];
			/* $emailTo	=	$_POST['emailTo']; */

			if(!isEmail($from)) {
				echo '<div class="alert alert-warning error"><p><strong>Attention!</strong> You have entered an invalid e-mail address, try again.</p></div>';
			exit();
			}

			if(get_magic_quotes_gpc()) {
				$message = stripslashes($message);
			}
			$Result = $this->mailer->contactform($from, $emailTo, $name, $message, $subject, $phone);
			if($Result == 'success') {
			
			$this->mailer->contactformThankyou($from, $emailTo, $name, $message, $phone);
			
				// Email has sent successfully, echo an error message.
				echo '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><p><i class="fa fa-paper-plane-o"></i>Thank you <strong>'.$name.'</strong>, your message has been sent to our team and we will get back to you.</p></div>';
			
			} else {
				// Email has NOT been sent successfully, echo an error message.
				echo '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><div class="alert alert-danger"><strong>ERROR!</strong> The email was not sent, either try again or later.</div>';
			}
			
	}
	
	public function do_callback(){
		
		
		if(!$_POST) exit;


		// Email address verification, do not edit.
		
			if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");

			$name = $_POST['name'] . ' ' . $_POST['lastname'];
			$from = 'info@mmsservices.co.uk';
			$phone = $_POST['phone'];
			$message= $_POST['message'];
			$emailTo	=	$_POST['emailTo'];
			$subject = 'Call me back';

			if(get_magic_quotes_gpc()) {
				$message = stripslashes($message);
			}
			$Result = $this->mailer->contactform($from, $emailTo, $name, $message, $subject, $phone);
			if($Result == 'success') {
			
				// Email has sent successfully, echo an error message.
				echo '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><p>Thank you <strong>'.$name.'</strong>, your message has been sent to our team and we will get back to you.</p></div>';
			
			} else {
				// Email has NOT been sent successfully, echo an error message.
				echo '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><div class="alert alert-danger"><strong>ERROR!</strong> The email was not sent, either try again or later.</div>';
			}
			
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */