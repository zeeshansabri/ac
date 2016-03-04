<?php
class Middle_Scripts extends CI_Model {

	function __construct() {
        parent::__construct();
        $this->load->model('scripts');
		$this->load->library('session');
		$this->load->library("pagination");
		$this->load->library("MY_Pagination");	
	}
	
	function get_slider(){
		$Query				=	'SELECT * FROM slider WHERE Status = "on" ORDER BY SortOrder ASC LIMIT 5';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_allvend(){
		$Query				=	'SELECT a.*, b.Name AS LocationName FROM restaurants AS a LEFT JOIN location AS b ON a.Location = b.ID WHERE a.Status = "on" ORDER BY rand()';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_vendcats(){
		$Query				=	'SELECT a.VID, b.Name AS CatName FROM rest_link_cat AS a LEFT JOIN rest_cat AS b ON a.CatID = b.ID WHERE b.Status = "on" ORDER BY b.SortOrder ASC';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_all_cats(){
		$Query				=	'SELECT * FROM rest_cat WHERE Status = "on" ORDER BY SortOrder ASC';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_shortservices($Limit, $Section = NULL, $Featured = NULL){	
		$Query				=	'SELECT * FROM shortservices WHERE Status = "on"';
		if($Section != NULL){
			$Query	.=	' AND Section = "' . $Section . '"'; 
		}
		if($Featured != NULL){
			$Query	.=	' AND Featured =  "on"'; 
		}
		$Query	.=	' ORDER BY SortOrder ASC LIMIT ' . $Limit;
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_projects($limit = NULL, $Rand = NULL){
		$Query				=	'SELECT * FROM projects WHERE Status = "on"';
		if($Rand != NULL){
			$Query .=	'ORDER BY rand()' ;
		} else {
			$Query .=	'ORDER BY SortOrder ASC';
		}
		
		if($limit != NULL){
			$Query		.=		" LIMIT $limit";
		}
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_f_vendors($limit = NULL, $Rand = NULL){
		$Query				=	'SELECT a.*, b.Name AS LocationName FROM restaurants AS a
								LEFT JOIN location AS b ON a.Location = b.ID
								 WHERE a.Status = "on" AND a.Featured = "on"';
		if($Rand != NULL){
			$Query .=	'ORDER BY rand()' ;
		} else {
			$Query .=	'ORDER BY SortOrder ASC';
		}
		
		if($limit != NULL){
			$Query		.=		" LIMIT $limit";
		}
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_spciealOffers(){
		$Query				=	'SELECT * FROM specialoffers WHERE Status = "on" AND VID = 0 ORDER BY rand() LIMIT 6';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_keynotes(){
		$Query				=	'SELECT * FROM keynotes WHERE Status = "on" ORDER BY SortOrder ASC';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_counters(){
		$Query				=	'SELECT * FROM counters WHERE Status = "on" ORDER BY SortOrder ASC LIMIT 4';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_testimonials(){
		$Query				=	'SELECT * FROM testimonials WHERE Status = "on" ORDER BY rand() LIMIT 6';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_latestblog(){
		$Query				=	'SELECT * FROM blog WHERE Status = "on" ORDER BY ID DESC LIMIT 3';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_featured_services($Limit = NULL){
		$Query				=	'SELECT * FROM services WHERE Status = "on" AND Featured = "on" ORDER BY SortOrder ASC';
		if($Limit != NULL){
			$Query 			.=	' LIMIT ' . $Limit;
		}
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_all_nofeature_services(){
		$Query				=	'SELECT * FROM services WHERE Status = "on" AND Featured = "off" ORDER BY SortOrder ASC LIMIT 4';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_pulldown($Section){
		$Query				=	'SELECT * FROM pull_down WHERE Status = "on" AND Section = "' . $Section . '" ORDER BY SortOrder ASC';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_all_serv(){
		$Query				=	'SELECT * FROM services WHERE Status = "on" ORDER BY SortOrder ASC';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_all_serv_by_cat($ID){
		$Query				=	"SELECT a.* 
								FROM services AS a
								LEFT JOIN services_cat AS b ON a.Section = b.ID  
								WHERE b.SEO = '$ID' ORDER BY SortOrder ASC";
		$Result				=	$this->scripts->GetAllData($Query);
		return	$Result;
	}
	
	function get_all_port_cat(){
		$Query				=	'SELECT a.* FROM project_cat AS a 
									LEFT JOIN projects AS b ON a.ID = b.CatID
								 	WHERE a.Status = "on" AND b.Status = "on" GROUP BY a.ID ORDER BY SortOrder ASC';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_allport($Section, $limit, $URI){
		$Query				=	'SELECT * FROM projects WHERE Status = "on" ORDER BY SortOrder ASC';
		$Result = $this->inc_pag($Query, $Section, $limit, $URI);
		return $Result;
	}
	
	function inc_pag($PQ, $Section, $limit, $URI){
		$record_count 	= 	$this->scripts->record_count($PQ);
		$total_rows 	= 	sizeof($record_count);
		$PQ			 	.= 	$this->my_pagination($total_rows, $Section, $limit, $URI);
		$data['PResult']	=	$this->scripts->GetAllData($PQ);
		$data["links"] = $this->pagination->create_links();
		return $data;
	}
	
	function get_all_blog_cat($limit = NULL){
		$Query				=	'SELECT a.* FROM blog_cat AS a 
									LEFT JOIN blog AS b ON a.ID = b.CatID
								 	WHERE a.Status = "on" AND b.Status = "on" GROUP BY a.ID ORDER BY rand() ';
		if($limit != NULL){
			$Query			.=	' LIMIT ' . $limit;
		}						 	
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_allblog($Section, $limit, $FID, $URI){
		$Query				=	'SELECT * FROM blog WHERE Status = "on" AND ID != ' . $FID .' ORDER BY SortOrder ASC';
		$Result = $this->inc_pag($Query, $Section, $limit, $URI);
		return $Result;
	}
	
	function get_allblog_stmap(){
		$Query				=	'SELECT * FROM blog WHERE Status = "on" ORDER BY SortOrder ASC';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_allblog_tag($Section, $limit, $Tags, $URI){
		$Tags = rtrim($Tags, '-');
		$Query				=	'SELECT * FROM blog WHERE Status = "on" AND Tags LIKE "%' . $Tags .'%" ORDER BY SortOrder ASC';
		$Result = $this->inc_pag($Query, $Section, $limit, $URI);
		return $Result;
	}
	
	function get_allsearch_tag($Section, $limit, $Tags, $URI){
		$Tags = rtrim($Tags, '-');
		$Tags = str_replace('-', ' ', $Tags);
		
		$Query				=	'SELECT "Portfolio" Prefix, PortfolioTitle AS Name, ShortDescription, SEO, Tags FROM projects 
									WHERE Status = "on" 
									AND Tags LIKE "%' . $Tags .'%"
									OR PortfolioTitle LIKE "%' . $Tags . '%"
									OR Description LIKE "%' . $Tags . '%"
									OR Description2 LIKE "%' . $Tags . '%"
									';
		$Query				.=	' UNION SELECT "Blg" Prefix, BlogTitle AS Name, ShortDescription, SEO, Tags FROM blog 
									WHERE Status = "on" 
									AND Tags LIKE "%' . $Tags .'%"
									OR BlogTitle LIKE "%' . $Tags . '%"
									OR Description LIKE "%' . $Tags . '%"
									';
		$Result = $this->inc_pag($Query, $Section, $limit, $URI);
		return $Result;
	}
	
	function get_allsearch_forsitemap($Tags = NULL){
		$Tags = rtrim($Tags, '-');
		$Tags = str_replace('-', ' ', $Tags);
		
		$Query				=	'SELECT "Portfolio" Prefix, PortfolioTitle AS Name, ShortDescription, SEO, Tags FROM projects 
									WHERE Status = "on" 
									AND Tags LIKE "%' . $Tags .'%"
									OR PortfolioTitle LIKE "%' . $Tags . '%"
									OR Description LIKE "%' . $Tags . '%"
									OR Description2 LIKE "%' . $Tags . '%"
									';
		$Query				.=	' UNION SELECT "Blg" Prefix, BlogTitle AS Name, ShortDescription, SEO, Tags FROM blog 
									WHERE Status = "on" 
									AND Tags LIKE "%' . $Tags .'%"
									OR BlogTitle LIKE "%' . $Tags . '%"
									OR Description LIKE "%' . $Tags . '%"
									';
		$Result = $this->scripts->GetAllData($Query);
		return $Result;
		
	}
	
	function get_allblog_cat($Section, $limit, $Category, $URI){
		$Query				=	'SELECT a.* FROM blog AS a
								 LEFT JOIN blog_cat AS b ON a.CatID = b.ID
								 WHERE a.Status = "on" AND b.SEO = "' . $Category . '" ORDER BY SortOrder ASC';
		$Result = $this->inc_pag($Query, $Section, $limit, $URI);
		return $Result;
	}
	
	function get_alltags($Limit = NULL){
		$Query				=	'SELECT * FROM blog_tags WHERE Status = "on" ORDER BY rand() ';
		if($Limit != NULL){
			$Query .= 'LIMIT ' . $Limit;
		} else {
			$Query .= 'LIMIT 5';
		}
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_all_featured_blog($limit){
		$Query				=	'SELECT * FROM blog WHERE Status = "on" AND Featured = "on" ORDER BY rand() LIMIT '. $limit;
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	
	/* PAGINATION */

		function my_pagination($total_rows, $Section, $limit, $URI){
		
			$config                   = array();
			$config['base_url'] 			= base_url($Section.'/i');
			$config["total_rows"]     		= $total_rows;
			$config["per_page"]       		= $limit;
			$URI = $config["uri_segment"]   = $URI; /*This is important to make the current li works */
			
			$config['full_tag_open']  = '<ul class="pagination">
												<li class="pagination-prev">
													<span>
														<span class="glyphicon glyphicon-menu-left"></span>
													</span>
												</li>';
			$config['full_tag_close'] = '	<li class="pagination-next">
												<span class="glyphicon glyphicon-menu-right"></span>
											</li>
										</ul>';
			
			$config['prev_link'] 	  = '<span><span class="glyphicon glyphicon-menu-left"></span>'; /* &larr;  */
			$config['prev_tag_open']  = '<li class="pagination-prev">';
			$config['prev_tag_close'] = '</li>';
			
			
			$config['next_link']      = '<span class="glyphicon glyphicon-menu-right"></span>'; /* &rarr; */
			$config['next_tag_open']  = '<li class="pagination-next">';
			$config['next_tag_close'] = '</li>';
			
			
			
			$config['cur_tag_open']   = '<li class="active"><span>';
			$config['cur_tag_close']  = '</span></li>';
			
			$config['num_tag_open']   = '<li><a href="#"><span>';
			$config['num_tag_close']  = '</span></a></li>';
			
			
			$this->pagination->initialize($config);
			
			$start = ($this->uri->segment($URI)) ? $this->uri->segment($URI) : 0;
			$query = ' LIMIT ' . $start . ',' . $config["per_page"];
			return $query;
			
		}
	
	/* PAGINATION ENDS */
	
	
	
	function get_all_pulldown(){
		$Query				=	"SELECT a.* 
									FROM pull_down AS a
									WHERE a.Status = 'on' ORDER BY a.Section ASC";
		$Result				=	$this->scripts->GetAllData($Query);
		return	$Result;
	}
	
	function get_page_details($SEO){
		$Query				=	'SELECT * FROM pages WHERE SEO = "' .$SEO . '"';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
	function get_clinic_details($SEO){
		$Query				=	'SELECT * FROM clinic WHERE SEO = "' .$SEO . '"';
		$Result				=	$this->scripts->GetAllData($Query);
		return $Result;
	}
	
}
?>
