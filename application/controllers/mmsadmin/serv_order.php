<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Serv_Order extends CI_Controller {

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
			$return = 'order';
		} else if($Get == 'folder'){
			$return = 'serv_order';
		} else if($Get == 'controller'){
			$return = 'serv_order';
		} else if($Get == 'pagetitle'){
			$return = 'Service Order';
		} else if($Get == 'Headings'){
			$return =  array('Name', 'House No', 'Street', 'Phone', 'Service Required');
		} else if($Get == 'TableHeadings'){
			$return =  array('UserName', 'HouseNo', 'Street', 'Phone', 'SPCatName');
		}		

		return $return;
	}
	
	public function checkDup($SEO){
		
		$DQ 	= 	"SELECT * FROM team WHERE SEO = '$SEO'";
		$DR		=	$this->db->query($DQ);
		if($DR->num_rows() > 0){
			return 1;
		} else {
			return 0;
		}
		
	}
	
	public function getfielddata($ID = NULL){
		
			$Country         =  $this->input->post('Country');
			$Network		 =	$this->input->post('Network');
			$Rate			 =	$this->input->post('Rate');
			$RouteType		 =	$this->input->post('RouteType');
			$Code			 =	$this->input->post('Code');
		    $Status			 =	$this->input->post('Status');
		    $Featured		 =	$this->input->post('Featured');
		    $SortOrder		 =	$this->input->post('SortOrder');
		    
  		    
  		    if($Status == 1){
			  			
			  			$Status = 'on';
			  			
		  			} else {
			  			
			  			$Status = 'off';
		  			}
		  			
		  	if($Featured == 1){
			  			
			  			$Featured = 'on';
			  			
		  			} else {
			  			
			  			$Featured = 'off';
		  			}
		  			
  			$fields = array(
						'Status' 			=> 	$Status,
						'CountryID'			=> 	$Country,
						'NetworkID'			=>	$Network,
						'Rate'				=>	$Rate,
						'Code'				=>	$Code,
						'RouteType'			=>	$RouteType,
						'SortOrder'			=>	$SortOrder,
						'Featured'			=>	$Featured
			);
			
			
			if (isset($_FILES['Image']) && $_FILES['Image']['tmp_name']){
					$config['upload_path']   = 'assets/upload/team/';
					$config['allowed_types'] = '*';
					$config['max_size']		 = '10000000000';
					
			if(isset($_POST['oldImage'])){
				if(is_file('assets/upload/team/'.$_POST['oldImage'])){
				unlink('assets/upload/team/'.$_POST['oldImage']);
				}
			}
			
			$fields['Image'] = $this->do_upload($config, 'Image');	
			
  			}
  						
			return $fields;
	}
			
	public function view_all(){
		
		// Get cURL resource
		
		/* ALL DATA */
		$RQ		 = 	' SELECT a.*, b.SPCatName FROM order_serv AS a LEFT JOIN sp_cat AS b ON a.SID = b.ID';
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
	
		public function add_new(){
			
			$folder = $this->getvalues('folder');
			$data['Controller'] = $folder;
			$data['PageTitle'] = $this->getvalues('pagetitle');
			
			$Query	=	'SELECT * FROM users';
			$Run	=	$this->db->query($Query);
			$data['Users']	=	$Run->result();
			
			$Query	=	'SELECT * FROM location';
			$Run	=	$this->db->query($Query);
			$data['Location']	=	$Run->result();
			
			$table =  'sp_cat';
			$where = '';
			$order = ' ORDER BY SPCatName ASC ';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Category'] = $result;
									
			if ($this->session->userdata('is_admin_login') == true) {
				
			$this->load->view('mmsadmin/'.$folder.'/add_edit', $data);
	
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
		  		    	$AddDesc	=	$this->input->post('AddressDescription');
		  		    	
		  				$City		=	'Karachi';
		  				$Country	=	'Pakistan';
		  				
		  				$SID		=	$this->input->post('Serv');
		  				
		  			
		  			if(empty($UserID)){
		  			
		  			$UserSet	=	array(
		  					'Name'			=>	$Name,
		  					'Phone'			=>	$Phone,
		  					'HouseNo'		=>	$HouseNo,
		  					'Street'		=>	$Street,
		  					'LID'			=>	$Area,
		  					'City'			=>	$City,
		  					'Country'		=>	$Country,
		  					'AddDesc'		=>	$AddDesc,
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
		  					'AddDesc'		=>	$AddDesc,
		  					'SID'			=>	$SID,
		  					'Status'		=>	'on'
		  			);
		  			
		  			$Result			=		$this->scripts->do_add('order_serv', $NewOrder);
		  			$OrderID		=		$Result['Created'];
		  			
		  			$txt			=  		"Updated";
		  								
					if($_POST['save'] == 'save'){
					
							$this->session->set_userdata('QueryResult',"Record Has Been Successfully ".$txt."...");
							redirect(base_url().'mmsadmin/serv_order/edit/'.rtrim(base64_encode($OrderID), '='));	
					
					} else if($_POST['save'] == 'close'){
					
							$this->session->set_userdata('Success',"Record Has Been Successfully ".$txt."...");
							redirect(base_url().'mmsadmin/serv_order/add_new');	
						
					}
															
	  }
	 
		public function edit($ID){
			
			$ID = base64_decode($ID);
			
			$data['ID'] = $ID;
			$folder = $this->getvalues('folder');
			$data['Controller'] = $folder;
			$data['PageTitle'] = $this->getvalues('pagetitle');
			
			$RQ		 = 	' SELECT * FROM order_serv WHERE ID = ' . $ID;
			$RQR	 =	$this->db->query($RQ);
			$result  = 	$RQR->result();
			$data['Info'] = $result;
			
			$Query	=	'SELECT * FROM location';
			$Run	=	$this->db->query($Query);
			$data['Location']	=	$Run->result();
			
			$table =  'sp_cat';
			$where = '';
			$order = ' ORDER BY SPCatName ASC ';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Category'] = $result;
			
			$table =  'service_provider';
			$where = '';
			$order = ' ORDER BY Name ASC ';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['ServiceProvider'] = $result;
			
			$CheckQuery		=		'SELECT * FROM order_link_sp WHERE SOID = ' . $ID;
		  	$Run			=		$this->db->query($CheckQuery);
		  	$Count			=		$Run->num_rows();
			
			$data['SPID']	=	'';
			
			if($Count != 0){
		  	
		  		$data['SPID']			=		$Run->result()[0]; 
				
		  	}

			
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
					
					$Name		=	$this->input->post('Name');
		  		    $Phone		=	$this->input->post('Phone');
		  		    $HouseNo	=	$this->input->post('House');
		  		    $Street		=	$this->input->post('Street');
		  		    $Area		=	$this->input->post('Location');
		  		    $AddDesc	=	$this->input->post('AddressDescription');
		  		    	
		  			$City		=	'Karachi';
		  			$Country	=	'Pakistan';
		  				
		  			$SID		=	$this->input->post('Serv');
		  			$SPID		=	$this->input->post('SP');
		  			
		  			$Status		=	$this->input->post('Status');
		  			
		  			if($Status == 1){
		  			
			  			$Status	=	'on';
			  			
		  			} else {
		  			
			  			$Status	=	'off';
			  			
		  			}
				  			
		  			$fields	=	array(
		  					'UserName'		=>	$Name,
		  					'Phone'			=>	$Phone,
		  					'HouseNo'		=>	$HouseNo,
		  					'Street'		=>	$Street,
		  					'LID'			=>	$Area,
		  					'AddDesc'		=>	$AddDesc,
		  					'SID'			=>	$SID,
		  					'Status'		=>	$Status
		  			);
					
					$folder = $this->getvalues('folder');
					
					if($_POST['save'] == 'save'){
					
					$this->scripts->do_edit('order_serv', $fields, $id);
					
					$this->session->set_userdata(array(
					
					'QueryResult' => true,
					'Message' 	  => "Record Has Been Successfully ".$txt."..."
					
					));
					
					redirect(base_url().'mmsadmin/' . $folder . '/edit/'.rtrim(base64_encode($id), '='));
					
					} else if($_POST['save'] == 'assign'){
					
					$fields	=	array(
		  					'SOID'			=>	$id,
		  					'SPID'			=>	$SPID,
		  					'SID'			=>	$SID,
		  					'Status'		=>	'on'
		  			);
		  			
		  			$CheckQuery		=		'SELECT * FROM order_link_sp WHERE SOID = ' . $id;
		  			$Run			=		$this->db->query($CheckQuery);
		  			$Count			=		$Run->num_rows();
		  			
		  			if($Count == 0){
		  				
		  				$this->scripts->do_add('order_link_sp', $fields);
		  				
		  			
		  			} else {
		  				
		  				/* $Result			=		$Run->result()[0]; */
		  				
		  				$fields	=	array(
		  					'SPID'		=>		$SPID
		  				);
			  			
			  			$this->scripts->do_update_ols('order_link_sp', $fields, $id);	
		  			}
						$txt	=	'Order Assigned';
						
					$this->session->set_userdata('QueryResult',"Record Has Been Successfully ".$txt."...");
					
					redirect(base_url().'mmsadmin/' . $folder . '/edit/'.rtrim(base64_encode($id), '='));
						
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