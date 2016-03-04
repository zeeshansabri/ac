<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class StMapAuto extends CI_Controller {

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
	}
 
	 
	public function index()
	{
	
		$this->load->library('stmap');
		$sitemap = $this->stmap->load('http://mmsservices.co.uk');
		$sitemap->setPath('xmls/');
		$sitemap->setFilename('newsitemap');
		$sitemap->addItem('/', '1.0', 'daily', 'Today');
		$sitemap->addItem('/about', '1.0', 'daily', 'Today');
		$sitemap->addItem('/services', '1.0', 'daily', 'Today');
		
		$sitemap->addItem('/portfolio', '1.0', 'daily', 'Today');
		$Result = $this->middle_scripts->get_projects();

		foreach($Result['Result'] as $Port){
			$sitemap->addItem('/portfolio/details/'.$Port->SEO, '1.0', 'daily', 'Today');
			foreach(explode(',', $Port->Tags) as $Tag){
				$Tag = str_replace(' ', '-', $Tag);
				$sitemap->addItem('/search?s='.$Tag, '1.0', 'daily', 'Today');
				$sitemap->addItem('/search/tags/'.$Tag, '1.0', 'daily', 'Today');
			}
		}
		
		$sitemap->addItem('/blog', '1.0', 'daily', 'Today');
		$Result = $this->middle_scripts->get_allblog_stmap();
		foreach($Result['Result'] as $Blog){
			$sitemap->addItem('/blog/details/'.$Blog->SEO, '1.0', 'daily', 'Today');
			foreach(explode(',', $Blog->Tags) as $Tag){
				$Tag = str_replace(' ', '-', $Tag);
				$sitemap->addItem('/search?s='.$Tag, '1.0', 'daily', 'Today');
				$sitemap->addItem('/search/tags/'.$Tag, '1.0', 'daily', 'Today');
			}
		}
		
		$sitemap->addItem('/contact', '1.0', 'daily', 'Today');
		$sitemap->addItem('/search', '1.0', 'daily', 'Today');
		$sitemap->createSitemapIndex('http://mmsservices.co.uk/xmls/', 'Today');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */