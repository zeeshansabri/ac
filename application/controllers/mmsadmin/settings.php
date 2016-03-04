<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

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
			$return = 'settings';
		} else if($Get == 'folder'){
			$return = 'settings';
		} else if($Get == 'add_edit'){
			$return = 'settings';
		} else if($Get == 'controller'){
			$return = 'settings';
		} else if($Get == 'pagetitle'){
			$return = 'Settings';
		} else if($Get == 'Headings'){
			$return =  array('Name', 'Short Description', 'SortOrder');
		} else if($Get == 'TableHeadings'){
			$return =  array('Name', 'ShortDescription', 'SortOrder');
		}		

		return $return;
	}
	
	public function getfielddata(){
		
			$Title       		 =  $this->input->post('Title');
  		    $MetaKeywords		 =	$this->input->post('MetaKeywords');
  		    $MetaDescription	 =	$this->input->post('MetaDescription');
  		    $FooterText			 =	$this->input->post('FooterText');
  		    $HeaderText			 =	$this->input->post('HeaderText');
  		    $Copyright			 =	$this->input->post('Copyright');
  		    
  		    $Address			 =	$this->input->post('Address');
  		    $Phone				 =	$this->input->post('Phone');
  		    $Mobile				 =	$this->input->post('Mobile');
  		    $Timings			 =	$this->input->post('Timings');
  		    $Map				 =	$this->input->post('Map');
  		    $OtherOffices		 =	$this->input->post('OtherOffices');
  		    
  		    $Facebook			 =	$this->input->post('Facebook');
  		    $Twitter			 =	$this->input->post('Twitter');
  		    $Pinterest			 =	$this->input->post('Pinterest');
  		    $LinkedIn			 =	$this->input->post('LinkedIn');
  		    $GooglePlus			 =	$this->input->post('GooglePlus');
  		    $Email				 =	$this->input->post('Email');
  		    $Status				 =	1;
  		    
  		    
  			
  			$fields = array(
				'Title'	 			=> 	$Title,
				'MetaKeywords'		=> 	$MetaKeywords,
				'MetaDescription'	=>	$MetaDescription,
				'FooterText'		=>	$FooterText,
				'HeaderText'		=>	$HeaderText,
				'Copyright'			=>	$Copyright,
				'Address'			=>	$Address,
				'EmailTo'			=>	$Email,
				'Phone'				=>	$Phone,
				'Mobile'			=>	$Mobile,
				'Facebook'			=>	$Facebook,
				'Twitter'			=>	$Twitter,
				'Pinterest'			=>	$Pinterest,
				'LinkedIn'			=>	$LinkedIn,
				'GooglePlus'		=>	$GooglePlus,
				'Timings'			=>	$Timings,
				'Map'				=>	$Map,
				'Status'			=>	$Status,
				'OtherOffices'		=>	$OtherOffices
			);
			
			if ($_FILES['Logo']['tmp_name']){
					$config['upload_path']   = 'assets/upload/media/';
					$config['allowed_types'] = '*';
					$config['max_size']		 = '10000000000';
					
			if(isset($_POST['oldImage'])){
				unlink('assets/upload/media/'.$_POST['oldImage']);
			}
			
			$fields['Logo'] = $this->do_upload($config, 'Logo');	
			
  			}
  			
  			if ($_FILES['Logo2']['tmp_name']){
					$config['upload_path']   = 'assets/upload/media/';
					$config['allowed_types'] = '*';
					$config['max_size']		 = '10000000000';
					
			if(isset($_POST['oldImage2'])){
				unlink('assets/upload/media/'.$_POST['oldImage2']);
			}
			
			$fields['Logo2'] = $this->do_upload($config, 'Logo2');	
			
  			}
			
			return $fields;
	}
			
	public function view_all(){
		
		// Get cURL resource
		
		/* ALL SLIDER DATA */
		$table = $this->getvalues('table');
		$where = '';
		$order = '';
		$result = $this->scripts->SelectOrder($table, $where, $order);
		$data['data_row'] = $result;
		/* ALL SLIDER DATA */
	
		$view = $this->getvalues('view_file');
				
		if($this->session->userdata('is_admin_login') == true) {
			
		$this->load->view('mmsadmin/'.$view, $data);
		
		} else {
			
        redirect(base_url().'mmsadmin/');
		
        }
		
		
	}
	
		public function add_new(){
			
			$Add = $this->getvalues('add_edit');
			$data['Controller'] = $this->getvalues('controller');
			
			if ($this->session->userdata('is_admin_login') == true) {
				
			$this->load->view('mmsadmin/'.$Add, $data);
	
	        } else {
				
	        redirect(base_url().'mmsadmin/');
			
	        }
			
		}
	
	 
		public function edit(){
			
			$ID = 1;
			
			$data['ID'] = $ID;
			
			$table = $this->getvalues('table');
			$where = ' WHERE ID = ' . $ID;
			$order = '';
			$result = $this->scripts->SelectOrder($table, $where, $order);
			
			$data['PageTitle'] = 'Settings - MMS Admin';
			$data['Edit'] = $result;
			$folder = $this->getvalues('folder');
			$data['Controller'] = $this->getvalues('controller');
			
			if ($this->session->userdata('is_admin_login') == true) {
			$this->load->view('mmsadmin/'. $folder . '/settings', $data);
			
			
			} else {
				
			redirect('mmsadmin/');
			
			}
		}

	  	  
	   public function do_edit() {
		  
		  		   
		  		    $txt			 =  "Updated";
		  		    	
					$table = $this->getvalues('table');
					$id 		=		$this->input->post('ID');
					
					
					$fields = $this->getfielddata();
					
					$this->scripts->do_edit($table, $fields, $id);
					$Controller = $this->getvalues('controller');
					
					if($_POST['save'] == 'save'){
					
					$this->session->set_userdata(array(
					
					'QueryResult' => true,
					'Message' 	  => "Record Has Been Successfully ".$txt."..."
					
					));
					
					redirect(base_url().'mmsadmin/'. $Controller . '/edit/'.rtrim(base64_encode($id), '='));
					
					} else if($_POST['save'] == 'close'){
						
					$this->session->set_userdata('QueryResult',"Record Has Been Successfully ".$txt."...");
					redirect(base_url().'mmsadmin/'. $Controller .'/view_all');	
						
					}
										
	  }
	  
	  public function do_add() {
		  
			  		$txt			 =  "Created";
		  		    	
					$table = $this->getvalues('table');
					$fields = $this->getfielddata();
					
					$query = $this->scripts->do_add($table, $fields);
					$Controller = $this->getvalues('controller');
					
					if($query['Created']){
					
					$result = 'success';
					$id = $query['Created'];
					
					
					if($_POST['save'] == 'save'){
					
					
							$this->session->set_userdata('QueryResult',"Record Has Been Successfully ".$txt."...");
							redirect(base_url().'mmsadmin/'. $Controller .'/edit/'.rtrim(base64_encode($id),'='));
						
					
					} else if($_POST['save'] == 'close'){
						
							$this->session->set_userdata('Success',"Record Has Been Successfully ".$txt."...");
							redirect(base_url().'mmsadmin/' . $Controller . '/view_all');	
						
					}
					
					} else {
						
						$this->session->set_userdata('QueryResult',"There has been an error. Please check fields or try again...");
						
						$data['name'] 			= $Name;
						$data['description'] 	= $Description;
						$data['icon'] 			= $Icon;
						$data['status'] 		= $Status;
						$data['sortorder']		= $SortOrder;
						
						$this->load->view('mmsadmin/'. $Controller, $data);
						
						
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
		  
		   		function do_upload($config, $Name){

			    $this->load->library('upload');
			    	
			    $this->upload->initialize($config);
			    $PImage = '';
			    
			    if ( $this->upload->do_upload($Name))
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