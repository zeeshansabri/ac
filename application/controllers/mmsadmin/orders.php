<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller {

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
		$this->load->library('encrypt');
		$this->load->model('scripts');

	}
	
	public function getvalues($Get){
		$return = '';
		
		if($Get == 'table'){
			$return = 'products';
		} else if($Get == 'folder'){
			$return = 'orders';
		} else if($Get == 'add_edit'){
			$return = 'orders';
		} else if($Get == 'controller'){
			$return = 'orders';
		} else if($Get == 'pagetitle'){
			$return = 'Order';
		} else if($Get == 'filelocation'){
			$return	=	'assets/upload/orders/';
		} else if($Get == 'Headings'){
			$return =  array('Order Number', 'Name', 'House No', 'Street', 'Order Total');
		} else if($Get == 'TableHeadings'){
			$return =  array('ID', 'UserName', 'HouseNo', 'Street', 'OrderTotal');
		}
		return $return;
	}

			
	public function view_all(){
		
		// Get cURL resource
		
		/* ALL DATA */
		$RQ		 = 	' SELECT * FROM `order`';
		$RQR	 =	$this->db->query($RQ);
		$result  = 	$RQR->result();
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
	
	public function view_all_by_status(){
		
		// Get cURL resource
		
		$Status = 'Pending';
		
		$url = 'https://gocleanlaundry.herokuapp.com/api/orders/status/' . $Status;
		$result = $this->scripts->get_data_api($url);
		$data['data_row'] = $result;
		
		/*
if($result == NULL){
			redirect("mmsadmin/home/logout");
		}
*/
		
		$url = 'https://gocleanlaundry.herokuapp.com/api/users/drivers';
		$result = $this->scripts->get_data_api($url);
		$data['data_driver'] = $result;
		
		
		
		
		if($this->session->userdata('is_admin_login') == true) {
		
		
			
		$this->load->view('mmsadmin/view_orders',$data);
		
		} else {
			
        redirect(base_url().'mmsadmin/');
		
        }
		
		
	}
	
	public function do_assign(){
		
		$DriverID 	= $_POST['DriverNumber'];
		$OrderID	= $_POST['OrderNumber'];
		$Status		= $_POST['DriverSatusString'];
			
				
				$url = 'https://gocleanlaundry.herokuapp.com/api/orders/'. $OrderID;
					
				$fields = array(
						'driver' 			=> 	$DriverID,
						'status' 			=> 	$Status
					);
					
				$result = $this->scripts->api_edit($url, $fields);
				
			if(isset($result->_id)){
				echo json_encode($result);
				
			} else {
				echo 0;
			}
		
	}
	
	public function check_status(){
	
							$Status = strtolower($_POST['Status']);
		
								if($Status == 'pending'){
	                               $Status = 'Out_for_collection';
	                              
                               } else if($Status == 'out_for_collection'){
                               	  	$Status = 'Received_at_facility';
                               	  
                               } else if($Status == 'received_at_facility'){
	                               	$Status = 'Ready';
	                              
                               } else if($Status == 'ready' || $Status == 'out_for_delivery'){
	                               	$Status = 'Received_at_facility';
	                              
                               } else if($Status == 'delivered'){
	                               	$Status = 'Delievered';
                               } else if($Status == 'cancelled'){
	                               $Status = 'Refunded';
                               }
                               
                               echo $Status;
		
	}
	
	public function get_correct_status(){
	
							$Status = strtolower($_POST['Status']);
		
							$Status = $this->scripts->get_correct_status($Status);
                               
                            echo $Status;
		
	}
	
	public function do_status_rec(){
		
		$OrderID	= $_POST['OrderNumber'];
			
				$url = 'https://gocleanlaundry.herokuapp.com/api/orders/'. $OrderID;
					
				$fields = array(
						'_id' 			=> 	$OrderID,
						'status' 		=> 	"Received_at_facility"
					);
					
				$result = $this->scripts->api_edit($url, $fields);
				
			if(isset($result->_id)){
				echo json_encode($result);
				
			} else {
				echo 0;
			}
		
	}
	
	public function do_status_ref(){
		
		$OrderID	= $_POST['OrderNumber'];
			
				$url = 'https://gocleanlaundry.herokuapp.com/api/orders/'. $OrderID;
					
				$fields = array(
						'_id' 			=> 	$OrderID,
						'status' 		=> 	"Refunded"
					);
					
				$result = $this->scripts->api_edit($url, $fields);
				
			if(isset($result->_id)){
				echo json_encode($result);
				
			} else {
				echo 0;
			}
		
	}
	
	public function get_new_data(){
		
				if(isset($_GET['Status'])){
					$Status = $_GET['Status'];
					$url = 'https://gocleanlaundry.herokuapp.com/api/orders/status/' . $Status;
				} else {
					$url = 'https://gocleanlaundry.herokuapp.com/api/orders';	
					
				}
			
				$result = $this->scripts->get_data_api($url);
				$data['data_row'] = $result;
					
				$this->load->view('mmsadmin/order_table',$data);
			
	}
	
	
		


	
		public function add_new(){
			
			$folder = $this->getvalues('folder');
			$data['Controller'] = $folder;
			$data['PageTitle'] = $this->getvalues('pagetitle');
			$table =  $this->getvalues('table');
			
			$Query	=	'SELECT * FROM restaurants WHERE Status = "on"';
			$Run	=	$this->db->query($Query);
			$data['Restaurants']	=	$Run->result();
			
			$Query	=	'SELECT * FROM users';
			$Run	=	$this->db->query($Query);
			$data['Users']	=	$Run->result();
			
			$Query	=	'SELECT * FROM location';
			$Run	=	$this->db->query($Query);
			$data['Location']	=	$Run->result();
			
			$Query =	'SELECT a.*, b.Name AS VendorName, c.PCatName FROM ' . $table . ' 
							AS a LEFT JOIN `restaurants` AS b ON b.ID = a.VID 
							LEFT JOIN product_cat AS c ON a.Category = c.ID ORDER BY a.Name ASC ';
			$Run	=	$this->db->query($Query);
			$result = $Run->result();
			$data['Products'] = $result;
			
			if ($this->session->userdata('is_admin_login') == true) {
				
			$this->load->view('mmsadmin/orders/add_edit', $data);
	
	        } else {
				
	        redirect(base_url().'mmsadmin/');
			
	        }
			
		}
		
		public function do_getuser(){
			
			$UserID	=	$this->input->post('UserID');
			
			$Query	=	'SELECT * FROM users WHERE ID = ' . $UserID;
			$Run	=	$this->db->query($Query);
			$Result	=	$Run->result();
			
			echo json_encode($Result);
			
		}
		
		
		public function do_add() {
		  
		  		    $UserID			=	$this->input->post('UserID'); 
		  		    
		  		    	$Name		=	$this->input->post('Name');
		  		    	$Phone		=	$this->input->post('Phone');
		  		    	$HouseNo	=	$this->input->post('House');
		  		    	$Street		=	$this->input->post('Street');
		  		    	$Area		=	$this->input->post('Location');
		  				$City		=	'Karachi';
		  				$Country	=	'Pakistan';
		  				
		  				$DCharge	=	$this->input->post('DCharge');
		  				$D2Charge	=	$this->input->post('D2Charge');
		  				$OrderTotal	=	$this->input->post('TPrice');
		  				/* echo $OrderTotal; */
		  			
		  			$FieldSet	=	count($_POST['FieldCount']);
		  			
		  			if(empty($UserID)){
		  			
		  			$UserSet	=	array(
		  					'Name'			=>	$Name,
		  					'Phone'			=>	$Phone,
		  					'HouseNo'		=>	$HouseNo,
		  					'Street'		=>	$Street,
		  					'LID'			=>	$Area,
		  					'City'			=>	$City,
		  					'Country'		=>	$Country,
		  					'Status'		=>	'on'
		  			);
		  			
		  			$Result		=	$this->scripts->do_add('users', $UserSet);
		  			$UserID			=	$Result['Created'];
		  			
		  			} else if(empty($Name)) {
			  			
			  			$this->session->set_userdata('QueryResult', false);
			  			redirect(base_url().'mmsadmin/orders/add_new/');
			  			
		  			}
		  			
		  			$NewOrder	=	array(
		  					'UserID'		=>	$UserID,
		  					'UserName'		=>	$Name,
		  					'Phone'			=>	$Phone,
		  					'HouseNo'		=>	$HouseNo,
		  					'Street'		=>	$Street,
		  					'LID'			=>	$Area,
		  					'DCharge'		=>	$DCharge,
		  					'D2Charge'		=>	$D2Charge,
		  					'OrderTotal'	=>	$OrderTotal,
		  					'Status'		=>	'on'
		  			);
		  			
		  			$Result		=	$this->scripts->do_add('order', $NewOrder);
		  			$OrderID	=	$Result['Created'];
		  			
		  			for($i = 1; $i < $FieldSet; $i++){
		  			
		  			$Qty	=	$this->input->post('A'.$i.'ProdQty');
		  			
		  			if($Qty != 0){
		  			
			  			$fields		=	array(
			  				'OrderID'	=>	$OrderID,
			  				'PID'		=>	$this->input->post('A'.$i.'Prod'),
			  				'ProdPrice'	=>	$this->input->post('A'.$i.'ProdPrice'),
			  				'ProdQty'	=>	$Qty,
			  				'ProdTotal'	=>	$this->input->post('A'.$i.'Sub'),
			  				'Status'	=>	'on'
			  			);
			  			$Result		=	$this->scripts->do_add('order_prod', $fields);
			  			
		  			}
		  		    
		  			}
		  			
		  			
		  			$txt			 =  "Updated";
		  								
					if($_POST['save'] == 'save'){
					
					
							$this->session->set_userdata('QueryResult',"Record Has Been Successfully ".$txt."...");
							redirect(base_url().'mmsadmin/orders/edit/'.rtrim(base64_encode($OrderID), '='));
						
					
					} else if($_POST['save'] == 'close'){
					
						$this->session->set_userdata('Success',"Record Has Been Successfully ".$txt."...");
						redirect(base_url().'mmsadmin/orders/show_invoice/' . $OrderID);
						
					}
															
	  }

		public function show_invoice($OrderID)
		{

			$order_detail_array = array();
			$vendors_detail_array = array();
			$vendors_order_detail_array = array();

			//fetching order details for slip
			$sql = "SELECT UserName,UserID, Phone, HouseNo, Street FROM `order` WHERE ID = '$OrderID'";
			$rslt_sql	=	$this->db->query($sql);
			if($rslt_sql->num_rows >= 1)
			{
				foreach ($rslt_sql->result_array() as $row)
				{
					$name =  $row['UserName'];
					$phone = $row['Phone'];
					$customer_id = $row['UserID'];
					$houseNo =  $row['HouseNo'];
					$street = $row['Street'];
				}

				$invoice_data['order_id'] = $OrderID;
				$invoice_data['name'] = $name;
				$invoice_data['customer_id'] = $customer_id;
				$invoice_data['phone'] = $phone;
				$invoice_data['address'] = "House No. ".$houseNo.", ".$street;



				$total_customer = 0;

				$sql2 = "SELECT PID, ProdPrice, ProdQty, ProdTotal FROM `order_prod` WHERE OrderID = '$OrderID'";
				$order_detail	=	$this->db->query($sql2);
				foreach ($order_detail->result_array() as $row)
				{
					$PID = $row['PID'];
					$sql_prod_detail = "SELECT `Name`,`Description`, VID FROM products WHERE ID = '$PID'" ;
					$rslt8 = $this->db->query($sql_prod_detail);
					foreach ($rslt8->result_array() as $row2)
					{
						$ProdName = $row2['Name'];
						if(isset($row2['Description'])){$ProdDescription = $row2['Description'];}else {$ProdDescription = "";}
						$ProdVendor = $row2['VID'];
						$sql_get_vendor = "SELECT `Name` FROM restaurants WHERE ID = '$ProdVendor'";
						$rslt9 = $this->db->query($sql_get_vendor);
						foreach ($rslt9->result_array() as $row9)
						{
							$ProdVendorName = $row9['Name'];
						}
					}
					$ProdPrice = round($row['ProdPrice'],2);
					$ProdQty = $row['ProdQty'];
					$ProdTotal = round($row['ProdTotal'],2);
					$total_customer = $total_customer + $ProdTotal;

					$order_detail_array[] = array($ProdName ,$ProdDescription, $ProdVendorName, $ProdQty, $ProdPrice ,$ProdTotal);
				}


			}

			//fetching vendors details of current order
			$sql5 = "SELECT  b.VID,
					c.Name AS VendorName,
					d.Name as VendorLocation
					FROM order_prod AS a
					LEFT JOIN products as b ON a.PID = b.ID
					LEFT JOIN restaurants as c ON b.VID = c.ID
					LEFT JOIN location as d ON c.Location = d.ID
					WHERE OrderID = '$OrderID' GROUP BY b.VID ASC";

			$rslt_sql5 = $this->db->query($sql5);
			$Result	=	$rslt_sql5->result();
			if($rslt_sql5->num_rows >= 1) {
				foreach($Result as $row)
				{
					$_VendorID =  $row->VID;
					$_VendorName =  $row->VendorName;
					$_VendorLocation =  $row->VendorLocation;
					$vendors_detail_array[] = array($_VendorID ,$_VendorName, $_VendorLocation);

				}
			}

			//getting detail for vendor copy
			$sql = "SELECT a.PID, a.ProdQty,
						b.Name AS Prod_name ,  b.Description AS Prod_description, b.Price, b.Percentage, b.Static, b.LunchSpecial, b.VID,
						c.Name AS VendorName, d.Name as VendorLocation
						FROM order_prod AS a
						LEFT JOIN products as b ON a.PID = b.ID
						LEFT JOIN restaurants as c ON b.VID = c.ID
						LEFT JOIN location as d ON c.Location = d.ID
						WHERE OrderID = '$OrderID' ORDER BY b.VID ASC ";

			$venders = $this->db->query($sql);
			$Result	=	$venders->result();

			if($venders->num_rows >= 1)
			{
				foreach($Result as $row)
				{
					$_VendorID =  $row->VID;
					$_VendorName =  $row->VendorName;
					$_VendorLocation =  $row->VendorLocation;

					$_prod_ID = $row->PID;
					$_prod_Qty = $row->ProdQty;
					$_prod_name = $row->Prod_name;
					$_prod_description = $row->Prod_description;
					$_prod_price = $row->Price;
					$_prod_Percentage = $row->Percentage;
					$_prod_Static = $row->Static;
					$_prod_LunchSpecial = $row->LunchSpecial;

					if(!empty($_prod_Static) AND $_prod_Static > 0 AND $_prod_Static != null)
					{
						$_prod_cost = $_prod_Static;
					}
					else
					{
						$_prod_cost = $_prod_price - (($_prod_price*$_prod_Percentage)/100);
					}



					$vendors_order_detail_array[] = array($_VendorID ,$_VendorName, $_VendorLocation, $_prod_ID, $_prod_name,$_prod_description, $_prod_Qty ,$_prod_cost);


				}
			}



			$invoice_data['order_detail_array'] = $order_detail_array;
			$invoice_data['vendors_detail_array'] = $vendors_detail_array;
			$invoice_data['vendors_order_detail_array'] = $vendors_order_detail_array;
			$invoice_data['total'] = $total_customer;
			$this->load->view('mmsadmin/orders/view_invoice', $invoice_data);
		}
	 
		public function edit($ID){
			
			$ID = base64_decode($ID);
			
			$data['ID'] = $ID;
			
			$RQ		 = 	' SELECT * FROM `order` WHERE ID = ' . $ID;
			$RQR	 =	$this->db->query($RQ);
			$result  = 	$RQR->result();
			$data['Info'] = $result;
			
			$folder = $this->getvalues('folder');
			$data['Controller'] = $folder;
			$data['PageTitle'] = $this->getvalues('pagetitle');
			$table =  $this->getvalues('table');
			
						
			$Query	=	'SELECT * FROM users';
			$Run	=	$this->db->query($Query);
			$data['Users']	=	$Run->result();
			
			$Query	=	'SELECT * FROM location';
			$Run	=	$this->db->query($Query);
			$data['Location']	=	$Run->result();
			
			$Query =	'SELECT a.*, b.Name AS VendorName, c.PCatName FROM ' . $table . ' 
							AS a LEFT JOIN `restaurants` AS b ON b.ID = a.VID 
							LEFT JOIN product_cat AS c ON a.Category = c.ID ORDER BY a.Name ASC ';
			$Run	=	$this->db->query($Query);
			$result = $Run->result();
			$data['Products'] = $result;
			
			$CheckQuery		=		'SELECT a.*, b.Name AS ProdName, b.Description AS ProdDesc FROM order_prod AS a LEFT JOIN products AS b ON b.ID = a.PID WHERE a.OrderID = ' . $ID;
		  	$Run			=		$this->db->query($CheckQuery);
		  	$Count			=		$Run->num_rows();
		  	
		  	
		  	$data['OrderProd']	=	'';
			
			if($Count != 0){
		  	
		  		$data['OrderProd']			=		$Run->result(); 
				
		  	}
		  	
			if ($this->session->userdata('is_admin_login') == true) {
			$this->load->view('mmsadmin/' . $folder . '/add_edit', $data);
			
			
			} else {
				
			redirect('mmsadmin/');
			
			}
		}
		
		public function do_checkProd(){
			
			$ID = $_POST['ProductID'];
			
			
			
			$result = $this->scripts->get_data_api($url);
		
			
				
				echo $result->price;
			
		}
		
		public function view($ID){
			
			$ID = base64_decode($ID);
			
			$data['ID'] = $ID;
			
			$url = 'https://gocleanlaundry.herokuapp.com/api/orders/' . $ID;
			$result = $this->scripts->get_data_api($url);
			$data['data_row'] = $result;
			
			$url = 'https://gocleanlaundry.herokuapp.com/api/products/';
			$result = $this->scripts->get_data_api($url);
		
			foreach($result as $prod_row){
			
				$data['Prod']['name'][$prod_row->_id] = $prod_row->name;
				$data['Prod']['price'][$prod_row->_id] = $prod_row->price;
				$data['Prod']['category'][$prod_row->_id] = $prod_row->category;
						
			}

			
			if ($this->session->userdata('is_admin_login') == true) {
			$this->load->view('mmsadmin/orders', $data);
			
			
			} else {
				
			redirect('mmsadmin/');
			
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
		  
	   public function do_edit() {
		  			
		  			
		  			$id				=	$this->input->post('ID');
		  					  		    
		  		    	$Name		=	$this->input->post('Name');
		  		    	$Phone		=	$this->input->post('Phone');
		  		    	$HouseNo	=	$this->input->post('House');
		  		    	$Street		=	$this->input->post('Street');
		  		    	$Area		=	$this->input->post('Location');
		  		    	$AddDesc	=	$this->input->post('AddDesc');
		  				$City		=	'Karachi';
		  				$Country	=	'Pakistan';
		  				
		  				$DCharge	=	$this->input->post('DCharge');
		  				$D2Charge	=	$this->input->post('D2Charge');
		  				$OrderTotal	=	$this->input->post('TPrice');
		  					  			
		  			$NewOrder	=	array(
		  					'UserName'		=>	$Name,
		  					'Phone'			=>	$Phone,
		  					'HouseNo'		=>	$HouseNo,
		  					'Street'		=>	$Street,
		  					'LID'			=>	$Area,
		  					'AddDesc'		=>	$AddDesc,
		  					'DCharge'		=>	$DCharge,
		  					'D2Charge'		=>	$D2Charge,
		  					'OrderTotal'	=>	$OrderTotal,
		  					'Status'		=>	'on'
		  			);
		  			
		  			$Result		=	$this->scripts->do_edit('order', $NewOrder, $id);
		  			
		  			$FieldSet	=	count($_POST['FieldCount']);

		  			for($i = 1; $i <= $FieldSet; $i++){
		  			
		  			$Qty		=	$this->input->post('A'.$i.'ProdQty');
		  			$LineID		=	$this->input->post('A'.$i.'LineID');
		  			$ProdPrice	=	$this->input->post('A'.$i.'ProdPrice');
		  			
		  			if($Qty != 0){
		  			
			  			$fields		=	array(
			  				'ProdQty'	=>	$Qty,
			  				'ProdTotal'	=>	$Qty * $ProdPrice,
			  				'Status'	=>	'on'
			  			);
			  			$Result		=	$this->scripts->do_edit('order_prod', $fields, $LineID);
			  			
		  			} else {
			  			
			  			$this->db->where('ID', $LineID);
			  			$this->db->delete('order_prod');
			  			
		  			}
		  		    
		  			}
		  			
		  			
		  			$txt			 =  "Updated";
		  								
					if($_POST['save'] == 'save'){
					
					$this->session->set_userdata(array(
					
					'QueryResult' => true,
					'Message' 	  => "Record Has Been Successfully ".$txt."..."
					
					));
					
					redirect(base_url().'mmsadmin/orders/edit/'.rtrim(base64_encode($id),'='));
					
					} else if($_POST['save'] == 'close'){
						
					$this->session->set_userdata(array(
					
						'QueryResult'	=> true,
						'Message'		=> "Record Has Been Successfully ".$txt."..."
					
						)
					);
					
					redirect(base_url().'mmsadmin/orders/view_all');	
						
					}
										
	  }
	  
	  


	  public function status() {
		  
		  		    $i            =  $this->input->get('i');
		  		    $id			  =  $this->input->get('id');
					
					
					if(isset($i) && isset($id)){
					
						if($i==1){
							 $Status = false;
							 $txt = " Disabled";
							 }else{
							 $Status = true;
							 $txt = " Enabled";
						 }
					
						
					$url = 'https://gocleanlaundry.herokuapp.com/api/categories/'. $id;
					
					$fields = array(
						'_id' => $id,
						'alive' => $Status
					);
					
					$this->scripts->api_change_status($url, $fields);
					
					$this->session->set_userdata('Success',"Record Has Been Successfully ".$txt."...");
					redirect(base_url().'mmsadmin/category/view_all');
										
					}
		  }


}

/* End of file page_types.php */
/* Location: ./application/controllers/page_types.php */
