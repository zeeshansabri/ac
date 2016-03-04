<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SpecialRates extends CI_Controller {

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
			$return = 'userrate';
		} else if($Get == 'folder'){
			$return = 'specialrates';
		} else if($Get == 'controller'){
			$return = 'speicalrates';
		} else if($Get == 'pagetitle'){
			$return = 'Special Rates';
		} else if($Get == 'Headings'){
			$return =  array('Code', 'Country', 'Network', 'Route Type');
		} else if($Get == 'TableHeadings'){
			$return =  array('Code', 'Country', 'Network', 'RouteType');
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
		
			$UserID	         =  $this->input->post('UserID');
			$RateListID		 =	$this->input->post('RateID');
			$Rate			 =	$this->input->post('Rate');
		    $Status			 =	$this->input->post('Status');
		    
  		    
  		    if($Status == 1){
			  			
			  			$Status = 'on';
			  			
		  			} else {
			  			
			  			$Status = 'off';
		  			}
		  			
		  			
  			$fields = array(
						'Status' 			=> 	$Status,
						'RateListID'		=> 	$RateListID,
						'UserID'			=>	$UserID,
						'Rate'				=>	$Rate
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
		$RQ		 = 	' SELECT a.ID, a.Status, b.Country, d.Code, d.RouteType, c.Network 
						FROM userrate AS a 
						LEFT JOIN ratelist AS d ON a.RateListID = d.ID 
						LEFT JOIN country AS b ON d.CountryID = b.ID 
						LEFT JOIN network AS c ON d.NetworkID = c.ID';
						
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
			
			$RQ		 = 	' SELECT a.*, b.Country, c.Network FROM ratelist AS a LEFT JOIN country AS b ON a.CountryID = b.ID LEFT JOIN network AS c ON a.NetworkID = c.ID';
			$RQR	 =	$this->db->query($RQ);
			$result  = 	$RQR->result();
			$data['RateList'] = $result;
			
			$RQ		 = 	'SELECT *
						FROM users 
						WHERE Status = "on"';
			$RQR	 =	$this->db->query($RQ);
			$result  = 	$RQR->result();
			$data['Users'] = $result;
			
			
			
			
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
			
			$RQ		 = 	'SELECT a.Rate, a.Status, b.RouteType, c.Country, d.Network, e.CompanyName
							FROM userrate AS a 
							LEFT JOIN ratelist 	AS b ON a.RateListID = b.ID 
							LEFT JOIN country	AS c ON c.ID = b.CountryID
							LEFT JOIN network	AS d ON d.ID = b.NetworkID
							LEFT JOIN users		AS e ON e.ID = a.UserID
							WHERE a.ID = ' . $ID;
							
			$RQR	 =	$this->db->query($RQ);
			$result  = 	$RQR->result();
			$data['Info'] = $result;
						
			if ($this->session->userdata('is_admin_login') == true) {
			$this->load->view('mmsadmin/'.$folder.'/add_edit', $data);
			
			
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
		  
		  		    $id				 =  $this->input->post('ID');
		  		    $txt			 =  "Updated";
		  		    	
					$table =  $this->getvalues('table');
					
					$Status			 =	$this->input->post('Status');
		    
  		    
					if($Status == 1){
			  			
			  			$Status = 'on';
			  			
		  			} else {
			  			
			  			$Status = 'off';
		  			}
		  			
		  			
		  			$fields = array(
						'Status' 			=> 	$Status
					);
					
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