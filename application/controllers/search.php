<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

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
		$this->load->model('middle_scripts');
		$this->load->helper('text');	
		$this->load->library("pagination");	
		
	}
  
	 
	public function index()
	{
	
		$data['BlogInfo']	=	$this->pagetitle();
		
		$Tag = $this->input->get('s');
		$this->session->set_userdata(array(
							'Srarch'	=>	true,
							'SearchText' => $Tag
							));
		
		$Result				=	$this->middle_scripts->get_allsearch_tag('search', 10, $Tag, 4);
		$data['Search']		=	$Result['PResult']['Result'];
		$data["links"] 		=	$Result['links'];
		
		$data['Sidebar']	=	$this->getblogsidebar();
		
		$Result	=	$this->middle_scripts->get_alltags(10);
		$data['BlogTags']	=	$Result['Result'];
		
		$this->load->view("search", $data);

		
	}
	
	function pagetitle(){
		
		$ID = 4;
			
		$Query = "SELECT * FROM pages WHERE ID = '$ID' ";
    	$SQ = $this->db->query($Query);
		$BlogInfo = $SQ->result();
		return $BlogInfo;

	}
	
	
	function i(){
	
		if($this->session->userdata('Srarch') == true){
			$Tag = $this->session->userdata('SearchText');
		} else {
			redirect(base_url().'search');
		}
		
		$data['BlogInfo']	=	$this->pagetitle();
		
		$Result				=	$this->middle_scripts->get_allsearch_tag('search', 10, $Tag, 3);
		$data['Search']		=	$Result['PResult']['Result'];
		$data["links"] 		=	$Result['links'];
		
		$data['Sidebar']	=	$this->getblogsidebar();
		
		$Result	=	$this->middle_scripts->get_alltags(10);
		$data['BlogTags']	=	$Result['Result'];
		
		$this->load->view("search", $data);
				
				
	}
	
	function getblogsidebar(){
		
		$Result = $this->middle_scripts->get_all_featured_blog(4);
		$data['SideBarFeatured']	=	$Result['Result'];
		
		$Result =	$this->middle_scripts->get_all_blog_cat(4);
		$data['BlogCat']	=	$Result['Result'];
		
		$Result	=	$this->middle_scripts->get_alltags();
		$data['BlogTags']	=	$Result['Result'];
		
		return $data;
		
	}
	
	function tags($Tag){
	
		$data['BlogInfo']	=	$this->pagetitle();
		
		$Result				=	$this->middle_scripts->get_allsearch_tag('search/tags', 10, $Tag, 4);
		$data['Search']		=	$Result['PResult']['Result'];
		$data["links"] 		=	$Result['links'];
		
		$data['Sidebar']	=	$this->getblogsidebar();
		
		$Result	=	$this->middle_scripts->get_alltags(10);
		$data['BlogTags']	=	$Result['Result'];
		
		$this->load->view("search", $data);
			
	}
	
	function category($Cat){
	
		$data['BlogInfo']	=	$this->pagetitle();
		
		$Result =	$this->middle_scripts->get_all_blog_cat();
		$data['BlogCat']	=	$Result['Result'];
		
		$Result				=	$this->middle_scripts->get_all_featured_blog(1);
		$data['Featured']	=	$Result['Result'];
		$FID	=	$data['Featured'][0];
		
		$Result				=	$this->middle_scripts->get_allblog_cat('blog/category', 3, $Cat, 4); /* Default Location, Limit, SEO, URL) */
		$data['Blog']		=	$Result['PResult']['Result'];
		$data["links"] 		=	$Result['links'];
		
		$data['Sidebar']	=	$this->getblogsidebar();
		
		$Result	=	$this->middle_scripts->get_alltags(10);
		$data['BlogTags']	=	$Result['Result'];
		
		$this->load->view("blog", $data);
		
	}
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */