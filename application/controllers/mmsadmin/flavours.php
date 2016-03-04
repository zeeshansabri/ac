<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class flavours extends CI_Controller {

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
			$return = 'flavours';
		} else if($Get == 'folder'){
			$return = 'flavours';
		} else if($Get == 'add_edit'){
			$return = 'flavours';
		} else if($Get == 'controller'){
			$return = 'flavours';
		} else if($Get == 'pagetitle'){
			$return = 'Flavours';
		} else if($Get == 'filelocation'){
			$return	=	'assets/upload/products/';
		} else if($Get == 'Headings'){
			$return =  array('Flavour', 'Vendor Name', 'Type Name', 'Status');
		} else if($Get == 'TableHeading'){
			$return =  array('Flavour', 'VendorName', 'TypeName', 'Status');
		}
		return $return;
	}


	
	public function getfielddata($ID = NULL){
		
			$Flavour        =  	$this->input->post('Flavour');
			$TypeID			=	$this->input->post('TypeID');
			$VID			=	$this->input->post('VID');
		    $Status			=	$this->input->post('Status');
		    $SortOrder		=	$this->input->post('SortOrder');
		    
		      			
  		    if($Status == 1){
			  			
			  			$Status = 'on';
			  			
		  			} else {
			  			
			  			$Status = 'off';
		  			}

  			$fields = array(
						'Status' 			=> 	$Status,
						'Flavour'			=> 	$Flavour,
						'VID'				=>	$VID,
						'SortOrder'			=>	$SortOrder,
						'TypeID' 			=>  $TypeID
			);
			

	  		  			
			return $fields;
	}

			
	public function view_all(){
		
		// Get cURL resource
		
		/* ALL SLIDER DATA */
		$table =  $this->getvalues('table');
		$Query =	'SELECT a.*, b.Name AS VendorName, c.Name AS TypeName FROM flavours AS a LEFT JOIN `restaurants` AS b ON b.ID = a.VID LEFT JOIN product_type AS c ON a.TypeID = c.ID ORDER BY VendorName ASC  ';
		$Run	=	$this->db->query($Query);
		$result = $Run->result();
		$data['data_row'] = $result;
		/* ALL SLIDER DATA */
		$folder = $this->getvalues('folder');
		
		$data['PageTitle'] = $this->getvalues('pagetitle');
		$data['Headings'] = $this->getvalues('Headings');
		$data['TableHeadings'] = $this->getvalues('TableHeading');
		
				
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
			
			$table =  'restaurants';
			$where = '';
			$order = ' ORDER BY Name ASC ';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Vendor'] = $result;
			
			$table =  'product_type';
			$where = '';
			$order = ' ORDER BY Name ASC ';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Type'] = $result;
			
			if ($this->session->userdata('is_admin_login') == true) {
				
			$this->load->view('mmsadmin/'.$folder.'/add_edit', $data);
	
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
			
			$table =  'restaurants';
			$where = '';
			$order = ' ORDER BY Name ASC ';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Vendor'] = $result;
			
			$table =  'product_type';
			$where = '';
			$order = ' ORDER BY Name ASC ';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Type'] = $result;
			
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
					$fields = $this->getfielddata($id);
					
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
							redirect(base_url().'mmsadmin/' . $folder . '/add_new');	
						
					}
					
					} else
					{
						
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
			
			public function delete() {
		  
		  		    $i            	      =  $this->input->get('i', TRUE);
		  		    $delete            	  =  $this->input->get('delete', TRUE);
					
					
					if(!empty($i) && $delete == "yes"){
										
					$Pic = $this->input->get('pic', TRUE);
					
					if(!empty($Pic)){
					
						$query = $this->scripts->delete('project_details', $i);	
						
						$loc	=	$this->getvalues('filelocation');
						if(is_file($loc.$Pic)){
							unlink($loc.$Pic);
						}	
						
					} 
					
				}
				
				header('Location: ' . $_SERVER['HTTP_REFERER']);
		  }


}

/* End of file page_types.php */
/* Location: ./application/controllers/page_types.php */