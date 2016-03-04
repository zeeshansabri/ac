<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class stmap {
    
    function stmap()
    {
        $CI = & get_instance();
        log_message('Debug', 'stmap class is loaded.');
    }
 
    function load($param=NULL)
    {
        include_once APPPATH.'/third_party/sitemap/Sitemap.php';
                 
        return new SiteMap($param);
    }
}

?>