<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

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
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->library('form_validation');
		/* $this->load->library('clean'); */
		$this->load->library('encrypt');
		$this->load->model('scripts');

	}
	
	public function getvalues($Get){
		$return = '';
		
		if($Get == 'table'){
			$return = 'users';
		} else if($Get == 'folder'){
			$return = 'users';
		} else if($Get == 'add_edit'){
			$return = 'users';
		} else if($Get == 'controller'){
			$return = 'users';
		} else if($Get == 'pagetitle'){
			$return = 'Users';
		} else if($Get == 'Headings'){
			$return =  array('Name', 'Email', 'Phone', 'House No', 'Street');
		} else if($Get == 'TableHeadings'){
			$return =  array('Name', 'Email', 'Phone', 'HouseNo', 'Street');
		}		

		return $return;
	}
	
	public function getfielddata(){
		
		$Status			=	$this->input->post('Status');
		$Feedback		=	$this->input->post('Feedback');
		$AccountType	=	$this->input->post('AccountType');
		$AccountLimit	=	$this->input->post('AccountLimit');
  			
  		    if($Status == 1){
			  			
			  			$Status = 'on';
			  			
		  			} else {
			  			
			  			$Status = 'off';
		  			}

  			$fields = array(
						'Status' 			=> 	$Status,
						'Feedback'			=>	$Feedback,
						'AccountType'		=>	$AccountType,
						'AccountLimit'		=>	$AccountLimit
			);
			
			if (isset($_FILES['Image']) && $_FILES['Image']['tmp_name']){
					$config['upload_path']   = 'assets/upload/services/';
					$config['allowed_types'] = '*';
					$config['max_size']		 = '10000000000';
					
			if(isset($_POST['oldImage'])){
				if(is_file('assets/upload/services/'.$_POST['oldImage'])){
				unlink('assets/upload/services/'.$_POST['oldImage']);
				}
			}
			
			$fields['Image'] = $this->do_upload($config, 'Image');	
			
  			}
  						
			return $fields;
	}
			
	public function view_all(){
		
		// Get cURL resource
		
		/* ALL SLIDER DATA */
		$table =  $this->getvalues('table');
		$where = '';
		$order = ' Order By SortOrder ASC';
		$result = $this->scripts->SelectOrder($table, $where, $order);
		$data['data_row'] = $result;
		/* ALL SLIDER DATA */
		$folder = $this->getvalues('folder');
		
		$data['PageTitle'] = $this->getvalues('pagetitle');
		$data['Headings'] = $this->getvalues('Headings');
		$data['TableHeadings'] = $this->getvalues('TableHeadings');
		
				
		if($this->session->userdata('is_admin_login') == true) {
			
		$this->load->view('mmsadmin/'.$folder.'/view_all',$data);
		
		} else {
			
        redirect(base_url().'mmsadmin/');
		
        }
		
		
	}
	
	public function edit($ID){
			
			$ID = base64_decode($ID);
			
			$data['ID'] = $ID;
			$folder = $this->getvalues('folder');
			$data['Controller'] = $folder;
			$data['PageTitle'] = $this->getvalues('pagetitle');
			
			$table =  $this->getvalues('table');
			$where = ' WHERE ID = ' . $ID;
			$order = '';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Info'] = $result;
			
			$table =  'contactdetails';
			$where = ' WHERE UserID = ' . $ID . ' AND Type = "Main"';
			$order = '';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Main'] = $result;
			
			$table =  'contactdetails';
			$where = ' WHERE UserID = ' . $ID . ' AND Type = "Billing"';
			$order = '';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Billing'] = $result;
			
			$table =  'contactdetails';
			$where = ' WHERE UserID = ' . $ID . ' AND Type = "Rates"';
			$order = '';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Rates'] = $result;
			
			$table =  'contactdetails';
			$where = ' WHERE UserID = ' . $ID . ' AND Type = "NOC"';
			$order = '';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['NOC'] = $result;
			
			$table =  'reference';
			$where = ' WHERE UserID = ' . $ID;
			$order = '';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Reference'] = $result;
			
			if ($this->session->userdata('is_admin_login') == true) {
			$this->load->view('mmsadmin/'.$folder.'/add_edit', $data);
			
			
			} else {
				
			redirect('mmsadmin/');
			
			}
		}
		
		public function do_edit() {
		  
		  		    $id				 =  $this->input->post('ID');
		  		    $txt			 =  "Updated";
		  		    	
					$table =  $this->getvalues('table');
					$fields = $this->getfielddata();
					
					$this->scripts->do_edit($table, $fields, $id);
					$folder = $this->getvalues('folder');
					
					if($_POST['save'] == 'save'){
					
					$this->session->set_userdata(array(
					
					'QueryResult' => true,
					'Message' 	  => "Record Has Been Successfully ".$txt."..."
					
					));
					
					redirect(base_url().'mmsadmin/' . $folder . '/edit/'.rtrim(base64_encode($id), '='));
					
					} else if($_POST['save'] == 'close'){
						
					$this->session->set_userdata('QueryResult',"Record Has Been Successfully ".$txt."...");
					redirect(base_url().'mmsadmin/'. $folder .'/view_all');	
						
					}
										
	  }
	  
	  
	  
	  
	  
	  
	  
	
	public function orders($ID){
		
		// Get cURL resource
		
		/* ALL SLIDER DATA */
		$ID 	=	base64_decode($ID);
		$data['ComID'] =	$ID;
		
		$Query 	=	"SELECT a.*, b.OrderTotal, d2.cnt AS TotalPayment FROM orders AS a
						LEFT JOIN order_details AS b ON a.ID = b.OrderID
						LEFT JOIN (SELECT OrderID, SUM(Amount) AS cnt FROM payments GROUP BY 1) AS d2 ON d2.OrderID = a.ID
						WHERE UserID = " . $ID;
		$Run	=	$this->db->query($Query);
		$data['data_row'] = $Run->result();
		
		$Query	=	"SELECT AccountType FROM users WHERE ID = $ID";
		$Run	=	$this->db->query($Query);
		$data['Company']	=	$Run->result();
		
		$folder = $this->getvalues('folder');
		
		$data['PageTitle'] = $this->getvalues('pagetitle');
		$data['Headings'] = array('Order Number', 'Order Date', 'Order Total', 'Total Payment', 'Status');
		$data['TableHeadings'] = array('ID', 'CreatedDate', 'OrderTotal', 'TotalPayment', 'Status');
		
				
		if($this->session->userdata('is_admin_login') == true) {
			
		$this->load->view('mmsadmin/'.$folder.'/view_all_orders',$data);
		
		} else {
			
        redirect(base_url().'mmsadmin/');
		
        }
		
		
	}
	
	public function add_new_order($ID){
	
		$ID = base64_decode($ID);
		
		$Query 	=	'SELECT a.* FROM users AS a WHERE a.ID = ' . $ID;
		$Run	=	$this->db->query($Query);
		$data['Company']	=	$Run->result();
		
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
		$Run = $this->db->query($Query);
		$data['Routes'] = $Run->result();
		
		$folder = $this->getvalues('folder');
		$data['Controller'] = $folder;
		$data['PageTitle'] = 'Orders';
		
		
		
		if($this->session->userdata('is_admin_login') == true) {
			
		$this->load->view('mmsadmin/'.$folder.'/add_edit',$data);
		
		} else {
			
        redirect(base_url().'mmsadmin/');
        
        }
		
	}
	
	public function do_newOrder($ID){
		
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
		
		$InvType		=	$this->input->post('AccountType');
		
		$UserID			=	$this->session->userdata('ID');
		$RateID			=	$ID;
		$Date			=	date("Y-m-d h:i");
		
		$table			=	'orders';
		
		$OrderFields	= 	array(
		
				'UserID'	=>		$ID,
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
				'OrderTotal'	=>	$Amount,
				'InvType'		=>	$InvType,
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
		
		$table = 'order_details';
		
		if(!empty($PrevT->Rec)){
			  
			  $DetailFields['PrevBal']	=	$PrevT->Rec;
		  }
		  if(!empty($PrevT->Paid)){
			  $DetailFields['PrevPay']	=	$PrevT->Paid;
		  }
		  if(!empty($PrevT->TotalDiscount)){
			  $DetailFields['TDiscount']	=	$PrevT->TotalDiscount;
		  }
		
		$this->scripts->do_add($table, $DetailFields);
		
		$this->session->set_userdata("OrderStatus", "Success");
		
		$this->load->model('mailer');
		$this->mailer->neworderadmin($this->session->userdata('CompanyName'), $Query['Created']);
		$this->mailer->neworderuser($this->session->userdata('CompanyName'), $Query['Created'], $this->session->userdata('CompanyEmail'));
		
		redirect(base_url().'mmsadmin/users/comorders/'.trim(base64_encode($Query['Created']), '='));
			
		}
		
		
		
	}
	
	
	public function comorders($ID){
			
			$ID = base64_decode($ID);
			
			$data['ID'] = $ID;
			$folder = $this->getvalues('folder');
			$data['Controller'] = $folder;
			$data['PageTitle'] = $this->getvalues('pagetitle');
			
			$Query 	=	'SELECT b.ID, a.CompanyName, b.ID AS OrderID FROM users AS a
						LEFT JOIN orders AS b ON a.ID = b.UserID
						 WHERE b.ID = ' . $ID;
			$Run	=	$this->db->query($Query);
			$data['Company']	=	$Run->result();
			
			$Query	=	'SELECT a.Status, a.CreatedDate, a.Comments, 
							b.OrderTotal, b.Discount, b.SignalingIP, b.MediaIP, b.Protocol, 
							b.Ports, b.Codec, b.DTMFRelay, b.FAX, b.DialPattern, 
							d.Rate, a.ID,
							e.RouteType, e.Code,
							f.Country, 
							g.Network 
							FROM orders AS a 
							LEFT JOIN order_details AS b ON a.ID = b.OrderID 
							LEFT JOIN order_routes AS c ON a.ID = c.OrderID 
							LEFT JOIN userrate AS d ON d.ID = c.RouteID 
							LEFT JOIN ratelist AS e ON e.ID = d.RateListID 
							LEFT JOIN country AS f ON f.ID = e.CountryID 
							LEFT JOIN network AS g ON g.ID = e.NetworkID WHERE a.ID =' . $ID;
			$Run	=	$this->db->query($Query);
			$data['OrderDetails']	=	$Run->result();
			
			if ($this->session->userdata('is_admin_login') == true) {
			$this->load->view('mmsadmin/'.$folder.'/add_edit', $data);
			
			
			} else {
				
			redirect('mmsadmin/');
			
			}
		}
		
		public function do_ordersave($ID){
		
			$OrderID	=	$ID;
			$Discount	=	$this->input->post('Discount');
			$Comments	=	$this->input->post('Comments');
			$Status 	=	$this->input->post('Status');
			
			$Query	= 	"UPDATE order_details SET Discount = '$Discount' WHERE OrderID = $OrderID";
			$this->db->query($Query);
			
			$Query	=	"UPDATE `orders` SET `Comments` = '$Comments'";
			if(!empty($Status)){
				$Query .= ", `Status` = '$Status'";
			}
			$Query .=	" WHERE `ID` = $OrderID";
			
			$this->db->query($Query);
			
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			
		}
	
		
	
	 
		
		
		public function transaction($ID){
			
			$ID = base64_decode($ID);
			
			$data['ID'] = $ID;
			$folder = $this->getvalues('folder');
			$data['Controller'] = $folder;
			$data['PageTitle'] = $this->getvalues('pagetitle');
			
			$Query =  'SELECT c.Country, d.Network, a.Rate, a.ID, b.Code, b.RouteType FROM userrate AS a
						LEFT JOIN ratelist 	AS b ON a.RateListID = b.ID
						LEFT JOIN country 	AS c ON c.ID = b.CountryID
						LEFT JOIN network	AS d ON d.ID = b.NetworkID
						 WHERE a.UserID = ' . $ID;
		
			$Run = $this->db->query($Query);
			$data['Info'] = $Run->result();
			
			$Query =  'SELECT e.RouteID, e.OrderID, c.Country, d.Network, a.Rate, a.ID, b.Code, b.RouteType 
						FROM order_routes AS e
							LEFT JOIN userrate  AS a ON a.ID = e.RouteID
							LEFT JOIN ratelist 	AS b ON a.RateListID = b.ID
							LEFT JOIN country 	AS c ON c.ID = b.CountryID
							LEFT JOIN network	AS d ON d.ID = b.NetworkID
							 WHERE a.UserID = ' . $ID;
		
			$Run = $this->db->query($Query);
			$data['Routes'] = $Run->result();
			
			$Query 	=	'SELECT CompanyName, AccountType FROM users WHERE ID = ' . $ID;
			$Run	=	$this->db->query($Query);
			$data['Company']	=	$Run->result();
			
			$Query	=	'SELECT * FROM orders WHERE UserID = ' . $ID;
			$Run	=	$this->db->query($Query);
			$data['Orders']		=	$Run->result();
			
			$Query = 'SELECT a.ID, a.StartDate, a.EndDate, a.Amount, a.Minutes, a.Status, b.RouteType,  c.Country, d.Network, f.Rate FROM transactions AS a
						LEFT JOIN userrate	AS f ON f.ID = a.RouteID
						LEFT JOIN ratelist 	AS b ON f.RateListID = b.ID
						LEFT JOIN country 	AS c ON c.ID = b.CountryID
						LEFT JOIN network	AS d ON d.ID = b.NetworkID
						LEFT JOIN users 	AS e ON e.ID = a.UserID
						 WHERE a.UserID = ' . $ID . ' ORDER BY StartDate DESC';
			$Run	=	$this->db->query($Query);
			$data['Transaction'] = $Run->result();
			
			if ($this->session->userdata('is_admin_login') == true) {
			$this->load->view('mmsadmin/'.$folder.'/add_edit', $data);
			
			
			} else {
				
			redirect('mmsadmin/');
			
			}
		}
		
	  public function do_transaction(){
		  
		  $UserID = $this->input->post('UserID');
		  $OrderID		=	 $this->input->post('OrderID');
		  
		  if($_POST['RouteID'] != "Other"){
		  
			  $Route = explode("~", $_POST['RouteID']);
			  $RouteID = $Route[0];
			  $Rate = $Route[1];
		  
		  } else {
			
			  $Route = explode("~", $_POST['OtherRouteID']);
			  $RouteID = $Route[0];
			  $Rate = $Route[1];
			  
			  $table = 'order_routes';
			  
			  $OtherRoute = array(
			  		'OrderID'	=>	$OrderID,
			  		'RouteID'	=>	$RouteID
			  );
			  
			  $this->scripts->do_add($table, $OtherRoute);			  
			  
		  }
		  
		  $txt			 =  "Added";
		  $folder = $this->getvalues('folder');

		  $StatDate		=	 date("Y-m-d", strtotime($this->input->post('StartDate')));
		  $EndDate		=	 date("Y-m-d", strtotime($this->input->post('EndDate')));
		  $Minutes		=	 $this->input->post('Minutes');
		  $Amount		=	 $Minutes * $Rate;
		  
		  
		  $table 		=	 'transactions';
		  
		  $fields		=	 array(
		  						
		  						'UserID'	=>		$UserID,
		  						'RouteID'	=>		$RouteID,
		  						'StartDate'	=>		$StatDate,
		  						'EndDate'	=>		$EndDate,
		  						'Amount'	=>		$Amount,
		  						'Minutes'	=>		$Minutes
		  					 
		  					 );
		  					 
		  	if(!empty($OrderID)){
			  	
			  	$fields['Status']	=	$OrderID;
			  	$Query	=	"UPDATE order_details SET Consumed = (SELECT SUM(Amount) FROM transactions WHERE Status = $OrderID) WHERE OrderID = $OrderID";
			  	$Run	=	$this->db->query($Query);	
			  	
		  	} else {
			  	
			  	$fields['Status']	=	'Pending';
			  	
		  	}
		  					 
		  	$this->scripts->do_add($table, $fields);
		  	
		  	
		 	
		 	
		 	 	
		  	if($_POST['save'] == 'save'){
					
					$this->session->set_userdata(array(
					
					'QueryResult' => true,
					'Message' 	  => "Record Has Been Successfully ".$txt."..."
					
					));
					
					redirect(base_url().'mmsadmin/' . $folder . '/transaction/'.rtrim(base64_encode($UserID), '='));
					
					} else if($_POST['save'] == 'close'){
						
					$this->session->set_userdata('QueryResult',"Record Has Been Successfully ".$txt."...");
					redirect(base_url().'mmsadmin/'. $folder .'/view_all');	
						
					}
		  
	  }
	  
	  public function deletetrans($i) {
		  		    
				if(!empty($i)){
					
					$folder = $this->getvalues('folder');
					
					$query = $this->scripts->delete('transactions', $i);
					
				}
				
				header('Location: ' . $_SERVER['HTTP_REFERER']);
				
	  }
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  public function invoice($ID){
	  		
	  	$ID = base64_decode($ID);
			
		$data['ID'] = $ID;
		$folder = $this->getvalues('folder');
		$data['Controller'] = $folder;
		$data['PageTitle'] = $this->getvalues('pagetitle');
		
			$Query 	=	'SELECT CompanyName, AccountType FROM users WHERE ID = ' . $ID;
			$Run	=	$this->db->query($Query);
			$data['Company']	=	$Run->result();
			
			$Query	=	"SELECT a.*, b.OrderTotal, b.Discount FROM orders AS a
							LEFT JOIN order_details AS b ON a.ID = b.OrderID
							WHERE a.UserID = $ID ORDER BY CreatedDate DESC";
			$Run	=	$this->db->query($Query);
			$data['Invoices']	=	$Run->result();
			
			if(isset($_POST['RunInvoice'])){
				$Run = $this->do_invoice();
				$data['Transactions'] 	= 	$Run['Trans']->result();
				$data['Dispute']		=	$Run['Dispute']->result();
				$data['StartDate']		=	$Run['StartDate'];
				$data['EndDate']		=	$Run['EndDate'];
				
			}

	
		  $txt			 =  "Added";
		  $folder = $this->getvalues('folder');
		
		if ($this->session->userdata('is_admin_login') == true) {
		
			if(isset($_POST['save']) && $_POST['save'] == 'Generate'){
					$this->do_invoice_generate($Run);
			} else {
			$this->load->view('mmsadmin/'.$folder.'/add_edit', $data);
			}
			
			} else {
				
			redirect('mmsadmin/');
			
			}  
		  
		  
	  }
	  
	  public function do_invoice(){
		  

		  $UserID		= 	$this->input->post('UserID');

		  $StatDate		=	 date("Y-m-d", strtotime($this->input->post('StartDate')));
		  $EndDate		=	 date("Y-m-d", strtotime($this->input->post('EndDate')));
		  
		  $Query = 'SELECT a.ID, a.StartDate, a.EndDate, a.Amount, a.Minutes, a.Status, a.RouteID, b.RouteType, b.Code,  c.Country, d.Network, f.Rate 
		  				FROM transactions AS a
							LEFT JOIN userrate	AS f ON f.ID = a.RouteID
							LEFT JOIN ratelist 	AS b ON f.RateListID = b.ID
							LEFT JOIN country 	AS c ON c.ID = b.CountryID
							LEFT JOIN network	AS d ON d.ID = b.NetworkID
							LEFT JOIN users 	AS e ON e.ID = a.UserID
							 WHERE a.UserID = ' . $UserID . ' AND StartDate >= "' . $StatDate . '" AND EndDate <= "' . $EndDate . '" AND a.Status = "Pending" ORDER BY StartDate DESC';
		  $Run['Trans']	=	$this->db->query($Query);
		  
		  $Query = ' SELECT * FROM dispute WHERE UserID = ' . $UserID . ' AND Status = "Pending"';
		  $Run['Dispute']	=	$this->db->query($Query);
		  
		  $Run['StartDate']	=	$StatDate;
		  $Run['EndDate']	=	$EndDate;
		  
		  return $Run;
		 
		  
	  }
	  
	  public function do_invoice_generate($TransQueries){
	  
	  	  $UserID =	$this->input->post('UserID');
	  	  
		  $Query 	= 	"SELECT Address, Phone, EmailTo, Logo FROM settings WHERE ID = 1";
		  $Run 	=	$this->db->query($Query);
		  $data['Company']	=	$Run->result();
		  
		 
		  $Query	=	"SELECT CompanyName, Address, Phone, EmailAddress, AccountType FROM users WHERE ID = $UserID";
		  $Run		=	$this->db->query($Query);
		  $data['Client']	=	$Run->result();
		  
		  $OrderID = $this->createorder($TransQueries);
		  
		  $filename = 'invoice-'.$OrderID;
		  $pdfFilePath = "assets/upload/invoice/$filename.pdf";
		  $data['page_title'] = 'MarwahTech Invoice - ' . $filename; // pass data to the view
	
		  redirect(base_url()."mmsadmin/users/view_invoice/".$OrderID);
		  
	  }
	  
	  public function createorder($TransQueries){
		  
		  $UserID =	$this->input->post('UserID');
		  $InvType =	$this->input->post('AccountType');
		  
		  $table = 'orders';
		  
		  $OrderArray	=	array(
		  		'UserID'	=>		$UserID,
		  		'Status'	=>		'Pending',
		  		'CreatedDate'	=>	date("Y-d-m")
		  );
		  
		  $Order = $this->scripts->do_add($table, $OrderArray);
		  
		  $Total = array();
		  
		  $table = 'order_routes';
		  $Transactions = $TransQueries['Trans']->result();
		  foreach($Transactions as $Trans){
			  
			  $Route_array		=		array(
			  
			  		'OrderID'		=>		$Order['Created'],
			  		'RouteID'		=>		$Trans->RouteID	
			  
			  );
			
			$this->scripts->do_add($table, $Route_array);  
			
			array_push($Total, $Trans->Amount);
			
			$Query	=	"UPDATE transactions SET Status = " . $Order['Created'] . " WHERE ID = " . $Trans->ID;
			$Run	=	$this->db->query($Query); 
			  
		  }
		  
		  $Dispute = $TransQueries['Dispute']->result();
		  $Dispute_array	=	array();
		  foreach($Dispute as $Dis){
		  
		  	array_push($Dispute_array, $Dis->Amount);
		  
		  }
		  $Dispute = array_sum($Dispute_array);
		  
		  /*
$Query	=	"SELECT SUM(t2.omt) AS Rec, d2.smt AS Paid, SUM(t2.dmt) AS TotalDiscount
							 FROM `orders` AS a 
								LEFT JOIN order_details AS b on a.ID = b.OrderID 
							  	LEFT JOIN users AS c on c.ID = a.UserID 
							  	LEFT JOIN (SELECT OrderID, SUM(OrderTotal) as omt, SUM(Discount) as dmt FROM order_details GROUP BY 1) AS t2 ON t2.OrderID = a.ID
							  	LEFT JOIN (SELECT UserID, SUM(Amount) AS smt FROM payments GROUP BY 1) AS d2 on d2.UserID = c.ID 
							  WHERE c.ID = $UserID AND a.ID < " . $Order['Created'];
		  $Run		=	$this->db->query($Query);
		  $PreviousTotal	=	$Run->result();
		  $PrevT	=	$PreviousTotal[0];
*/
		  $table = 'order_details';
		  
		  $Total_Sum = array_sum($Total);
		  $Discount	 = $this->input->post('Discount');
		  
		  $OrderDetails = array(
		  		'OrderID'			=>		$Order['Created'],
		  		'OrderTotal'		=>		$Total_Sum,
		  		'Discount'			=>		$Discount,
		  		'Dispute'			=>		$Dispute,
		  		'InvType'			=>		$InvType
		  
		  );
		  
		  /*
if(!empty($PrevT->Rec)){
			  $OrderDetails['PrevBal']	=	$PrevT->Rec;
		  }
		  
		  if(!empty($PrevT->Paid)){
			  $OrderDetails['PrevPay']	=	$PrevT->Paid;
		  }
		  
		  if(!empty($PrevT->TotalDiscount)){
			  $OrderDetails['TDiscount'] =	$PrevT->TotalDiscount;
		  }
*/
		  
		  $this->scripts->do_add($table, $OrderDetails);
		  
		  $Query	=	"UPDATE order_details SET TotalPaid = (SELECT SUM(Amount) FROM payments WHERE OrderID = " . $Order['Created'] . ") WHERE OrderID = " . $Order['Created'];
		  $Run	=	$this->db->query($Query);
		  
		  
		  $Query	=	"UPDATE order_details SET Consumed = (SELECT SUM(Amount) FROM transactions WHERE Status = " . $Order['Created'] . ") WHERE OrderID = " . $Order['Created'];
		  $Run	=	$this->db->query($Query);
		  
		  return $Order['Created'];
		  
	  }
	  
	  public function view_invoice($ID){
	  
	  	  
	  
	  	  $Query 	= 	"SELECT ID, Address, Phone, EmailTo, Logo FROM settings WHERE ID = 1";
		  $Run 	=	$this->db->query($Query);
		  $Company	=	$Run->result();
		  $data['Company']	=	$Company;
		 
		  $Query	=	"SELECT a.ID, a.CompanyName, a.Address, a.Phone, a.EmailAddress, a.AccountType FROM users AS a
		  				LEFT JOIN orders AS b ON a.ID = b.UserID
		  				WHERE b.ID = $ID";
		  $Run		=	$this->db->query($Query);
		  $Client	=	$Run->result();
		  $data['Client']	=	$Client;
		  $UD		=	$Client[0];
		  
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
						WHERE a.ID = " . $ID . ' AND i.Status =  ' . $ID . ' GROUP BY i.RouteID';		
		$SQuery	=	$this->db->query($Query);
		$InvoiceDetails		=	$SQuery->result();
		$data['Data_Array']	=	$InvoiceDetails;
		
		$Query 	=	"SELECT OrderTotal, Discount, Dispute, PrevBal, PrevPay, InvType, TDiscount, TotalPaid, Consumed FROM order_details WHERE OrderID = $ID";
		$Run	=	$this->db->query($Query);
		$data['Details']	=	$Run->result();
		
		$Query	=	"SELECT SUM(Consumed) AS Consumed, SUM(TotalPaid) AS TotalPaid FROM order_details AS a
						LEFT JOIN orders AS b ON a.OrderID = b.ID
						WHERE OrderID < $ID AND b.UserID = " . $UD->ID;
		$Run	=	$this->db->query($Query);
		$data['PreStat']	=	$Run->result();
		
		$data['OrderID'] = $ID;
		  
		  $folder = $this->getvalues('folder');
		  
		  $this->load->view('mmsadmin/'.$folder.'/invoice', $data);
		  
	  }
	  
	  public function sendinvoice($ID){
		  
		  $Query 	=		"SELECT a.*, b.CompanyName FROM contactdetails AS a
		  						LEFT JOIN users AS b ON a.UserID = b.ID
		  						WHERE UserID = $ID AND Type = 'Billing'";
		  $Run		=		$this->db->query($Query);
		  $Result	=		$Run->result();
		  $Result 	=		$Result[0];
		  
		  $OrderID	=		$this->input->post('InvoiceID');
		  
		  $Code 	=		$this->generateRandomString(rand(1,10));
		  
		  $Query 	=		"UPDATE orders SET Code = '$Code', Status = 'Email Sent' WHERE ID = $OrderID";
		  $Run		=		$this->db->query($Query);
		  
		  $Code		=		trim(base64_encode($Code), '=');
		  echo $Code;
		  die();
	
		  $this->load->model("mailer");
		  
		  $this->mailer->sendinvoice($Result->Name, $Result->Email, $Result->CompanyName, $Code, $OrderID);
		  
		  redirect(base_url().'mmsadmin/users/invoice/'.trim(base64_encode($ID), '='));
		  
	  }
	  
	  function markpaid($ID){
		  
		  $table  = 'orders';
		  $Fields =	array(			
		  				'Status'	=>		'Paid'
		  			);
		  $this->scripts->do_edit($table, $Fields, $ID);
		  
		  header('Location: ' . $_SERVER['HTTP_REFERER']);
		  			
		  
	  }
	  
	  function generateRandomString($length) {
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$randomString = '';
					for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
	  }
	  
	  
	  
	  
	  
	  
	  
	  
	  public function dispute($ID){
			
			$ID = base64_decode($ID);
			
			$data['ID'] = $ID;
			$folder = $this->getvalues('folder');
			$data['Controller'] = $folder;
			$data['PageTitle'] = $this->getvalues('pagetitle');
			
			$Query 	=	'SELECT CompanyName FROM users WHERE ID = ' . $ID;
			$Run	=	$this->db->query($Query);
			$data['Company']	=	$Run->result();
			
			$Query = 'SELECT * FROM dispute WHERE UserID = ' . $ID . ' ORDER BY UpdateDate DESC ';
			$Run	=	$this->db->query($Query);
			$data['Dispute'] = $Run->result();
			
			if ($this->session->userdata('is_admin_login') == true) {
			$this->load->view('mmsadmin/'.$folder.'/add_edit', $data);
			
			
			} else {
				
			redirect('mmsadmin/');
			
			}
		}
		
	  public function do_dispute(){
		  
		  $UserID = $this->input->post('UserID');
		  
		  $txt			 =  "Added";
		  $folder = $this->getvalues('folder');

		  $CreatedDate		=	 date("Y-m-d");
		  $Amount			=	 $this->input->post('Amount');
		  $Comments			=	 $this->input->post('Comments');
		  
		  $table 		=	 'dispute';
		  
		  $fields		=	 array(
		  						
		  						'UserID'	=>		$UserID,
		  						'CreatedDate'	=>		$CreatedDate,
		  						'Amount'	=>		$Amount,
		  						'Comments'	=>		$Comments,
		  						'Status'	=>		'Pending'
		  					 
		  					 );
		  					 
		  	$this->scripts->do_add($table, $fields);
		  	
		  	if($_POST['save'] == 'save'){
					
					$this->session->set_userdata(array(
					
					'QueryResult' => true,
					'Message' 	  => "Record Has Been Successfully ".$txt."..."
					
					));
					
					redirect(base_url().'mmsadmin/' . $folder . '/dispute/'.rtrim(base64_encode($UserID), '='));
					
					} else if($_POST['save'] == 'close'){
						
					$this->session->set_userdata('QueryResult',"Record Has Been Successfully ".$txt."...");
					redirect(base_url().'mmsadmin/'. $folder .'/view_all');	
						
					}
		  
	  }
	  
	  public function deletedispute($i) {
		  		    
				if(!empty($i)){
					
					$folder = $this->getvalues('folder');
					
					$query = $this->scripts->delete('dispute', $i);
					
				}
				
				header('Location: ' . $_SERVER['HTTP_REFERER']);
				
	  }
	  
	  public function disputeresolved($i) {
		  		    
				if(!empty($i)){
					
					$table = 'dispute';
					$i 		= base64_decode($i);
					
					if($this->input->get('Status') == 'Resolved'){
						$Status = 'Pending';
					} else {
						$Status = 'Resolved';
					}
					
					$StatusArray = array(
							'Status'	=>		$Status
					);
					
					$this->scripts->do_edit($table, $StatusArray, $i);
					
				}
				
				header('Location: ' . $_SERVER['HTTP_REFERER']);
				
	  }



	  
	  
	  
	  
	  public function payments($ID){
			
			$ID = base64_decode($ID);
			
			$data['ID'] = $ID;
			$folder = $this->getvalues('folder');
			$data['Controller'] = $folder;
			$data['PageTitle'] = 'Payments';
			
			$Query 	=	'SELECT a.CompanyName, b.ID, b.UserID, c.OrderTotal FROM users AS a 
							LEFT JOIN orders AS b ON a.ID = b.UserID
							LEFT JOIN order_details AS c ON b.ID = c.OrderID
							WHERE b.ID = ' . $ID;
			$Run	=	$this->db->query($Query);
			$data['Company']	=	$Run->result();
		
			
			$Query = 'SELECT a.* FROM payments AS a
							WHERE a.OrderID = ' . $ID . ' ORDER BY UpdateDate DESC ';
			$Run	=	$this->db->query($Query);
			$data['Payments'] = $Run->result();
			
			$Query	=	'SELECT SUM(Amount) AS TP FROM payments WHERE OrderID = ' . $ID;
			$Run	=	$this->db->query($Query);
			$data['TotalPyament']	=	$Run->result();
			
			if ($this->session->userdata('is_admin_login') == true) {
			$this->load->view('mmsadmin/'.$folder.'/add_edit', $data);
			
			
			} else {
				
			redirect('mmsadmin/');
			
			}
		}
		
	public function do_payments(){
		  
		  $UserID = $this->input->post('UserID');
		  $OrderID = $this->input->post('OrderID');
		  
		  $txt			 =  "Added";
		  $folder = $this->getvalues('folder');

		  $CreatedDate		=	 date("Y-m-d");
		  $Amount			=	 $this->input->post('Amount');
		  $Comments			=	 $this->input->post('Comments');
		  $Max				=	 $this->input->post('Max');
		  
		  $table 		=	 'payments';
		  
		  $fields		=	 array(
		  						
		  						'UserID'		=>		$UserID,
		  						'OrderID'		=>		$OrderID,
		  						'CreatedDate'	=>		$CreatedDate,
		  						'Amount'		=>		$Amount,
		  						'Comments'		=>		$Comments,
		  						'Status'		=>		'Pending'
		  					 
		  					 );
		  					 
		  	$this->scripts->do_add($table, $fields);
		  	
		  	if($Amount == $Max){
			  	
			  	$OrderStatus	= array(
			  						
			  						 'Status'	=>		'Paid'
			  					);
			  					
			  	$table	=	'orders';
			  	
			  	$this->scripts->do_edit($table, $OrderStatus, $OrderID);
			  	
		  	}
		  	
		  	$Query	=	"UPDATE order_details SET TotalPaid = (SELECT SUM(Amount) FROM payments WHERE OrderID = $OrderID) WHERE OrderID = $OrderID";
		  	$Run	=	$this->db->query($Query);
		  	
		  	if($_POST['save'] == 'save'){
					
					$this->session->set_userdata(array(
					
					'QueryResult' => true,
					'Message' 	  => "Record Has Been Successfully ".$txt."..."
					
					));
					
					redirect(base_url().'mmsadmin/' . $folder . '/payments/'.rtrim(base64_encode($OrderID), '='));
					
					} else if($_POST['save'] == 'close'){
						
					$this->session->set_userdata('QueryResult',"Record Has Been Successfully ".$txt."...");
					redirect(base_url().'mmsadmin/'. $folder .'/view_all');	
						
					}
		  
	  }

	  
	  
	  
	  
	  public function delete() {
		  
		  		    $i            	      =  $this->input->get('i', TRUE);
		  		    $delete            	  =  $this->input->get('delete', TRUE);
					
					
					if(!empty($i) && $delete == "yes"){
					
					$this->load->model('mmsadmin/scripts');
					$Page = 'category';
					$Pic = $this->input->get('pic', TRUE);
					
					if($Pic > 0){
					
					$PageID = $this->input->get('pageid');
					
					$query = $this->scripts->deletepic($i, 'CatImages', $Page, $PageID);	
						
					} else {
					
					$query = $this->scripts->delete($i, 'Category', $Page);
					
					}
					
					}
		  }
		  
	   
	  
	  public function do_add() {
		  
		  		    
		  		    $txt			 =  "Created";
		  		    
		  			$table =  $this->getvalues('table');
					$fields = $this->getfielddata();
					
					$query = $this->scripts->do_add($table, $fields);
					$folder = $this->getvalues('folder');
					
					if($query['Created']){
					
					$result = 'success';
					$id = $query['Created'];
					
					
					if($_POST['save'] == 'save'){
					
					
							$this->session->set_userdata('QueryResult',"Record Has Been Successfully ".$txt."...");
							redirect(base_url().'mmsadmin/' . $folder . '/edit/'.rtrim(base64_encode($id),'='));
						
					
					} else if($_POST['save'] == 'close'){
						
							$this->session->set_userdata('Success',"Record Has Been Successfully ".$txt."...");
							redirect(base_url().'mmsadmin/' . $folder . '/view_all');	
						
					}
					
					} else {
						
						$this->session->set_userdata('QueryResult',"There has been an error. Please check fields or try again...");
						
						$data['name'] 			= $Name;
						$data['description'] 	= $Description;
						$data['icon'] 			= $Icon;
						$data['status'] 		= $Status;
						$data['sortorder']		= $SortOrder;
						
						$this->load->view('mmsadmin/' . $folder . '/add_edit', $data);
						
						
					}
										
	  }


	   public function status() {
		  
		  		    $i            =  $this->input->get('i');
		  		    $id			  =  $this->input->get('id');
					
					
					if(isset($i) && isset($id)){
					
						if($i=='on'){
							 $Status = 'off';
							 $txt = " Disabled";
							 }else{
							 $Status = 'on';
							 $txt = " Enabled";
						 }
					
						
					$table = $this->getvalues('table');
					$Controller = $this->getvalues('controller');
					
					$fields = array(
						'Status' => $Status
					);
					
					$this->scripts->do_edit($table, $fields, $id);
					
					$this->session->set_userdata('Success',"Record Has Been Successfully ".$txt."...");
					redirect(base_url().'mmsadmin/' . $Controller . '/view_all');
										
					}
		  }
		  
		  	function do_upload($config, $filename){

			    $this->load->library('upload');
			    	
			    $this->upload->initialize($config);
			    $PImage = '';
			    
			    if ( $this->upload->do_upload($filename))
					{
					
					$upload_data = $this->upload->data();
					$PImage	= $upload_data['file_name'];
						
					} else {
						
						$error = $this->upload->display_errors();
						echo $error;
						die();
					}
			    
				return $PImage;
			
			}


}

/* End of file page_types.php */
/* Location: ./application/controllers/page_types.php */