<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {

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
			$return = 'pages';
		} else if($Get == 'folder'){
			$return = 'pages';
		} else if($Get == 'add_edit'){
			$return = 'about';
		} else if($Get == 'controller'){
			$return = 'pages';
		} else if($Get == 'pagetitle'){
			$return = 'Pages';
		}  else if($Get == 'filelocation'){
			$return	=	'assets/upload/pages/';
		} else if($Get == 'Headings'){
			$return =  array('Name', 'Keywords', 'Meta Description');
		} else if($Get == 'TableHeadings'){
			$return =  array('PageTitle', 'MetaKeyword', 'MetaDescription');
		}		

		return $return;
	}
		
	public function getfielddata(){
		
			$Name            =  $this->input->post('Name');
			
			$Keywords		 =	$this->input->post('Keywords');
			$MetaDesc		 =	$this->input->post('MetaDesc');
			
		    $Heading1		 =	$this->input->post('Heading1');
		    $Description	 =	$this->input->post('Description');
		    
		    $Heading2		 =	$this->input->post('Heading2');
		    $Description2	 =	$this->input->post('Description2');
		    
		    $Heading3		 =	$this->input->post('Heading3');
		    $Description3	 =	$this->input->post('Description3');

			$Heading4		 =	$this->input->post('Heading4');
		    $Description4	 =	$this->input->post('Description4');
		    
		    $Skills		     =	$this->input->post('Skills');
		    
		    $HeaderLine1	 =	$this->input->post('HeaderLine1');
		    $HeaderLine2	 =	$this->input->post('HeaderLine2');
		    		
  			$fields = array(
						'PageTitle' 		=> 	$Name,
						'MetaKeyword'		=>	$Keywords,
						'MetaDescription'	=>	$MetaDesc,
						'Heading1'			=>	$Heading1,
						'Description'		=>	$Description,
						'Heading2'			=>	$Heading2,
						'Description2'		=>	$Description2,
						'Heading3'			=>	$Heading3,
						'Description3'		=>	$Description3,
						'Heading4'			=>	$Heading4,
						'Description4'		=>	$Description4,
						'Skills'			=>	$Skills,
						'HeaderLine1'		=>	$HeaderLine1,
						'HeaderLine2'		=>	$HeaderLine2
			);
			
			$loc	=	$this->getvalues('filelocation');
			
			$Image1	=	$this->uploadImage('Image1', 'oldImage', $loc);
	  		if($Image1 != ''){
	  			$fields['Image'] =	$Image1;
	  		}
	  		
	  		$Image2	=	$this->uploadImage('Image2', 'oldImage2', $loc);
	  		if($Image2 != ''){
	  			$fields['Image2'] =	$Image2;
	  		}
	  		
	  		$HeaderImage	=	$this->uploadImage('HeaderImage', 'oldHeaderImage', $loc);
	  		if($HeaderImage != ''){
	  			$fields['HeaderImage'] =	$HeaderImage;
	  		}
  			
  			
  						
			return $fields;
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
			
	public function view_all(){
		
		// Get cURL resource
		
		/* ALL SLIDER DATA */
		$table =  $this->getvalues('table');
		$where = '';
		$order = ' Order By PageTitle ASC';
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
	
		public function add_new(){
			
			$folder = $this->getvalues('folder');
			$data['Controller'] = $folder;
			$data['PageTitle'] = $this->getvalues('pagetitle');
			
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