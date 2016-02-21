<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Description of Sitemaps
 *
 * @author marwansaleh
 */
class Sitemaps extends MY_News{
    function __construct() {
        parent::__construct();
    }
    
    function index(){
        $years = $this->_get_article_years();
        $current_year = date ('Y');
        
        $baseurl = 'sitemaps/year/';
        
        $xml = array();
        $xml[]= '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[]= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($years as $year){
            $xml [] = '<sitemap>';
            $xml [] = '<loc>'.  site_url($baseurl . $year) .'</loc>';
            if ($year == $current_year){
                $lastmod = str_replace('#', 'T', date('Y-m-d#H:i:s+00:00'));
                $xml [] = '<lastmod>'.$lastmod.'</lastmod>';
            }else{
                $xml [] = '<lastmod>'.$year.'-12-31T23:29:00+00:00</lastmod>';
            }
            $xml [] = '</sitemap>';
        }
        $xml[]= '</sitemapindex>';
        
        $this->output->set_content_type('text/xml');
        $this->output->set_output(implode(PHP_EOL, $xml));
    }
    
    function year($year){
        $baseurl = 'detail/';
        
        //get article for selected year
        $this->load->model('article/article_m');
        $articles = $this->article_m->get_select_where('url_title',array('year'=>$year));
        if (!$articles){
            show_404();
            exit;
        }
        
        $xml = array();
        $xml[]= '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[]= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($articles as $article){
            $xml[]= '<url>';
            $xml[]= '<loc>'. site_url($baseurl . $article->url_title) .'</loc>';
            $xml[]= '</url>';
        }
        $xml[]= '</urlset>';
        
        $this->output->set_content_type('text/xml');
        $this->output->set_output(implode(PHP_EOL, $xml));
    }
    
    private function _get_article_years(){
        $years = array();
        
        $sql = 'SELECT distinct `year` FROM `nsc_articles` WHERE year > 0';
        $query = $this->db->query($sql);
        foreach ($query->result() as $row){
            if (!in_array($row->year, $years) && $row->year > 0){
                $years [] = $row->year;
            }
        }
        //sort from low to high
        sort($years);
        
        return $years;
    }
}
