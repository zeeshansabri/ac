<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ServiceProvider extends CI_Controller {

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
			$return = 'service_provider';
		} else if($Get == 'folder'){
			$return = 'serviceprovider';
		} else if($Get == 'add_edit'){
			$return = 'blog';
		} else if($Get == 'controller'){
			$return = 'serviceprovider';
		} else if($Get == 'pagetitle'){
			$return = 'Service Provider';
		} else if($Get == 'filelocation'){
			$return	=	'assets/upload/blog/';
		} else if($Get == 'Headings'){
			$return =  array('Name', 'Number', 'Location', 'Timings', 'Category', 'Category', 'SortOrder');
		} else if($Get == 'TableHeading'){
			$return =  array('Name', 'Phone', 'LocationName', 'Timings', 'Category1', 'Category2', 'SortOrder');
		}
		return $return;
	}
	
	public function getSEO($SEO1, $Name, $ID){
		
		if($SEO1 != ""){
			    
			    $SEOName	=	$SEO1;
		    
		    } else {
		    
		    	$SEOName	=	$Name;
		    
		    }
		    
		    $SEO			=  preg_replace("/[^A-Za-z0-9 ]/", ' ', $SEOName);
  		    $SEO			=  explode(" ", $SEO);
  		    $SEO			=	implode("-", $SEO);
  		    
  		    $Rand	   		=	substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,5);
  		    
  		    $Duplicate 		 = 	$this->checkDup($SEO, $ID);    
			
	  		if($Duplicate == 1){
	  		    $SEO = $SEO.'-'.$Rand;
	  		}
	  		
	  		return $SEO;
		
	}
	
	public function checkDup($SEO, $ID = NULL){
		
		$DQ 	= 	"SELECT * FROM projects WHERE SEO = '$SEO'";
		if($ID != NULL){
			$DQ	.=	" AND ID != $ID";
		}
		$DR		=	$this->db->query($DQ);
		if($DR->num_rows() > 0){
			return 1;
		} else {
			return 0;
		}
		
	}
	
	public function uploadImage($Image, $OldImage, $loc){
		
		$return = '';
		
		if (isset($_FILES[$Image]) && $_FILES[$Image]['tmp_name']){
					$config['upload_path']   = $loc;
					$config['allowed_types'] = '*';
					$config['max_size']		 = '10000000000';
					
			if(isset($_POST[$OldImage])){
				if(is_file($loc.$_POST[$OldImage])){
				unlink($loc.$_POST[$OldImage]);
				}
			}
			
			$return = $this->do_upload($config, $Image);	
			
			}

		return $return;
	}
	
	public function getTAGS($Tags, $NewTags){
		
		$NTArray	=	array();
		
		if(!empty($NewTags)){
		
		foreach(explode(',', $NewTags) as $NT){
			$query	=	'SELECT * FROM blog_tags WHERE TagTitle = "' . $NT . '"';
			$Run	=	$this->db->query($query);
			if($Run->num_rows() == 0){
			
				 $SEO			=  preg_replace("/[^A-Za-z0-9 ]/", ' ', $NT);
				 $SEO			=  explode(" ", $SEO);
				 $SEO			=	implode("-", $SEO);
				
				$Query	=	'INSERT INTO blog_tags SET TagTitle = "' . $NT . '", SortOrder = 1, Status = "on", SEO = "' . $SEO . '"';
				$Run	=	$this->db->query($Query);
			}

			array_push($NTArray, $NT);
		}
		
		}
	
		foreach($Tags as $ENT){
			array_push($NTArray, $ENT);
		}
		
		$Tags = implode(',', $NTArray);
		
		return $Tags;
	
	}
	
	public function getfielddata($ID = NULL){
		
			$Name           =  	$this->input->post('Name');
			$Category1      =  	$this->input->post('Category1');
			$Category2      =  	$this->input->post('Category2');
			$Number         =  	$this->input->post('Number');
			$Timing         =  	$this->input->post('Timing');
			$Location       =  	$this->input->post('Location');
			$LocationID		=	$this->input->post('LocationID');
			$Comments       =  	$this->input->post('Comments');
		    $Status			=	$this->input->post('Status');
		    $SMS			=	$this->input->post('SMS');
		    $SortOrder		=	$this->input->post('SortOrder');
		      			
    		if($Status == 1){
  			
  				$Status = 'on';
  			
			} else {
  			
  				$Status = 'off';
			
			}
			
			if($SMS == 1){
  			
  				$SMS = 'on';
  			
			} else {
  			
  				$SMS = 'off';
			
			}
		  	
  			$fields = array(
						'Status' 			=> 	$Status,
						'Name'				=> 	$Name,
						'Category1'			=>	$Category1,
						'Category2'			=>	$Category2,
						'Phone'				=>	$Number,
						'Timings'			=>	$Timing,
						'Location'			=>	$Location,
						'LID'				=>	$LocationID,
						'Comments'			=>	$Comments,
						'Status'			=>	$Status,
						'SortOrder'			=>	$SortOrder,
						'SMS'				=>	$SMS
			);
			
					  			
			return $fields;
	}
			
	public function view_all(){
		
		// Get cURL resource
		
		/* ALL SLIDER DATA */
		$table =  $this->getvalues('table');
		$Query	=	"SELECT a.*, b.SPCatName AS Category1, c.SPCatName AS Category2, d.Name As LocationName
						FROM $table AS a 
						LEFT JOIN `sp_cat` AS b ON a.Category1 = b.ID  
						LEFT JOIN `sp_cat` AS c ON a.Category2 = c.ID
						LEFT JOIN `location` AS d ON a.LID = d.ID
						ORDER BY a.Name ASC";
		$Run	=	$this->db->query($Query);
		$result	=	$Run->result();
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
			
			$table =  'sp_cat';
			$where = '';
			$order = ' ORDER BY SPCatName ASC ';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Category'] = $result;
			
			$table =  'location';
			$where = '';
			$order = ' ORDER BY Name ASC ';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Location'] = $result;
						
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
			
			$table =  'sp_cat';
			$where = '';
			$order = ' ORDER BY SPCatName ASC ';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Category'] = $result;
			
			$table =  'location';
			$where = '';
			$order = ' ORDER BY Name ASC ';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			$data['Location'] = $result;

						
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
			
			public function delete() {
		  
		  		    $i            	      =  $this->input->get('i', TRUE);
		  		    $delete            	  =  $this->input->get('delete', TRUE);
		  			
					
					if(!empty($i) && $delete == "yes"){
										
					$ID = base64_decode($i);
					
					$Query	=	"DELETE FROM `service_provider` WHERE ID = $ID";
					$Run	=	$this->db->query($Query);
					
					
					
				}
				
				header('Location: ' . $_SERVER['HTTP_REFERER']);
		  }


}

/* End of file page_types.php */
/* Location: ./application/controllers/page_types.php */