<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_BaseController extends CI_Controller {
    private $_cookie_visitor = 'visitor';
    private $_log_file;
    
    protected $REC_PER_PAGE = 8;
    protected $_log_path;
    protected $users;
    
    function __construct() {
        parent::__construct();
        $this->load->library('user_agent');
        
        //Load User Library
        $this->users = Userlib::getInstance();
        
        //Load helper
        $this->load->helper('general');
        $this->load->helper('cookie');
        
        //Iniatiate process
        $this->__initialisation();        
        
        //$this->data['mobile'] = $this->is_device('MOBILE')||$this->is_device('TABLET');
        $this->data['mobile'] = $this->is_mobile();
    }
    
    private function __initialisation(){
        //Create unique id for unique visitor
        $this->_create_unique_visitor();
        
        $this->_log_file = config_item('log_filename') ? config_item('log_filename') : 'mylogfile.log';
        $this->_log_path = rtrim(sys_get_temp_dir(), '/') .'/';
    }
    
    /**
     * Create unique visitor cookie
     */
    protected function _create_unique_visitor(){
        //check if cookie for this visitor exists, if not create one
        
        if (!get_cookie($this->_cookie_visitor)){
            $cookie = array(
                'name'   => $this->_cookie_visitor,
                'value'  => md5(time() . $this->input->ip_address()),
                'expire' => 8640 * 365
            );
            $this->input->set_cookie($cookie);
            
            //register new user
            if (!$this->agent->is_robot()){
                $this->_visitor_register();
            }
        }
    }
    
    /**
     * Get unique visitor ID from cookie created by function create_unique_visitor
     * @return string unique visitor id
     */
    protected function _get_unique_visitor(){
        return get_cookie($this->_cookie_visitor);
    }
    
    protected function _visitor_register(){
        $this->db->insert('unique_visitors', array(
            'visitor_id'    => $this->_get_unique_visitor(),
            'date'          => time(),
            'ip_address'    => $this->input->ip_address()
        ));
    }


    /**
     * Write into log file
     * @param string $event_name log description
     * @throws Exception if failed
     */
    public function _write_log($event_name=''){
        $content = array(
            date('Y-m-d H:i:s'), 
            $this->_get_unique_visitor(), 
            $this->input->ip_address(),
            $this->agent->agent_string(),
            $event_name
        );
        
        if ($fp = @fopen($this->_log_path . $this->_log_file, 'a')){
            fputcsv($fp, $content, "\t");
            fclose($fp);
        }
    }
    
    protected function file_extension($filename){
        $ext = pathinfo($filename, PATHINFO_EXTENSION); 
        return strtolower($ext);
    }
    
    protected function read_log($lines=5, $filepath=NULL, $adaptive = true){
        // Open file
        if (!$filepath){
            $filepath = $this->_log_path . $this->_log_file;
        }
        $f = @fopen($filepath, "rb");
        if ($f === false) return false;

        // Sets buffer size
        if (!$adaptive) $buffer = 4096;
        else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));

        // Jump to last character
        fseek($f, -1, SEEK_END);

        // Read it and adjust line number if necessary
        // (Otherwise the result would be wrong if file doesn't end with a blank line)
        if (fread($f, 1) != "\n") $lines -= 1;
        // Start reading
        $output = '';
        $chunk = '';

        // While we would like more
        while (ftell($f) > 0 && $lines >= 0) {

        // Figure out how far back we should jump
        $seek = min(ftell($f), $buffer);

        // Do the jump (backwards, relative to where we are)
        fseek($f, -$seek, SEEK_CUR);

        // Read a chunk and prepend it to our output
        $output = ($chunk = fread($f, $seek)) . $output;

        // Jump back to where we started reading
        fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);

        // Decrease our line counter
        $lines -= substr_count($chunk, "\n");

        }

        // While we have too many lines
        // (Because of buffer size we might have read too many)
        while ($lines++ < 0) {

        // Find first newline and remove all text before that
        $output = substr($output, strpos($output, "\n") + 1);

        }

        // Close file and return
        fclose($f);
        return trim($output);
    }
    
    /**
     * Get sys parameters
     * @param string $pattern
     * @return associative array
     */
    protected function get_sys_parameters($pattern=NULL){
        $this->load->model('system/sys_variables_m','sysvar_m');
        
        $sysvars = array();
        $result = NULL;
        
        if (!$pattern){
            $result = $this->sysvar_m->get();
        }else{
            if (is_array($pattern)){
                foreach($pattern as $index => $p){
                    if ($index==0):
                        $this->db->like('var_name', $p);
                    else:
                        $this->db->or_like('var_name', $p);
                    endif;
                }
            }else{
                $this->db->like('var_name', $pattern);
            }
            $result = $this->sysvar_m->get();
        }
        if ($result){
            foreach ($result as $var){
                $sysvars[$var->var_name] = variable_type_cast($var->var_value,$var->var_type);
            }
        }
        
        return $sysvars;
    }
    
    protected function is_mobile(){
        //first check for blackberry 10 BB10
        $user_agent_string = $this->agent->agent_string();
        if (strpos($user_agent_string, 'BB10')!== FALSE){
            return TRUE;
        }
        return $this->agent->is_mobile();
    }
    
    protected function is_device($deviceToCheck='DESKTOP'){
        $IE = stripos($_SERVER['HTTP_USER_AGENT'],"MSIE");
        $iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
        $iMac = stripos($_SERVER['HTTP_USER_AGENT'],"Macintosh");
        $AndroidTablet = false;
        
        if(stripos($_SERVER['HTTP_USER_AGENT'],"Android") && stripos($_SERVER['HTTP_USER_AGENT'],"mobile")){
                $Android = true;
        }else if(stripos($_SERVER['HTTP_USER_AGENT'],"Android")){
                $Android = false;
                $AndroidTablet = true;
        }else{
                $Android = false;
                $AndroidTablet = false;
        }
        
        $symbianOS = stripos($_SERVER['HTTP_USER_AGENT'],"symbianOS");
        //$webOS = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
        $BlackBerry = stripos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
        $RimTablet= stripos($_SERVER['HTTP_USER_AGENT'],"RIM Tablet");
        $winP= stripos($_SERVER['HTTP_USER_AGENT'],"Windows Phone");
        $winM= stripos($_SERVER['HTTP_USER_AGENT'],"Windows Mobile");
        $win= stripos($_SERVER['HTTP_USER_AGENT'],"Windows");

        
        switch($deviceToCheck){
            case 'IE': return $IE; break;
            case 'IPOD': return $iPod; break;
            case 'IPHONE': return $iPhone; break;
            case 'IPAD': return $iPad; break;
            case 'IMAC': return $iMac; break;
            case 'ANDROID': return ( $Android || $AndroidTablet ? true : false); break;
            case 'ANDROIDPHONE': return $Android; break;
            case 'ANDROIDTAB': return $AndroidTablet; break;
            case 'WINMO': return ( $winP || $winM ? true : false); break;
            case 'WIN': return $win; break;
            case 'SYMBIAN': return $symbianOS; break;
            case 'BLACKBERRY': return $BlackBerry; break;
            case 'RIMTABLET': return $RimTablet; break;
            case 'MOBILE': return ( $iPad || $iPod || $iPhone || $Android || $symbianOS  || $BlackBerry || $winP || $winM ? true : false); break;
            case 'TABLET': return ( $AndroidTablet || $RimTablet ? true : false); break;
            case 'DESKTOP': return ( ($iMac || $win) ? true : false); break;
        }
        
        return FALSE;
    }
}

/**
 * Description of MY_Controller
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class MY_Controller extends MY_BaseController {
    public $data = array();
    
    protected $_pagination_adjacent = 3;
    protected $_pagination_pages = 5;
    
    protected $meta_keyword = array('manajemen', 'resiko', 'majalah', 'stabilitas', 'keuangan','jasa keuangan','tata kelola');
    
    function __construct() {
        parent::__construct();    
        
        $this->data['meta_title'] = 'Indonesia Satu';
        $this->data['active_menu'] = 'home';
        
        //is user loggedin
        $this->data['is_logged_in'] = $this->users->isLoggedin();
        if ($this->users->isLoggedin()){
            $this->data['me'] = $this->users->me();
        }
        
        $this->meta_set_default();
        $this->og_set_default();
    }
    
    protected function get_FB_ID(){
        $param = 'FB_APP_ID';
        if (!$this->session->userdata($param)){
            
            $sysvar = $this->get_sys_parameters($param);
            if (!$sysvar){
                $sysvar[$param] = '1667512626834805';
            }
            $this->session->set_userdata($param, $sysvar[$param]);
            
            return $sysvar[$param];
        }else{
            return $this->session->userdata($param);
        }
    }
    
    protected function get_GA_Code(){
        $param = 'GA_CODE';
        if (!$this->session->userdata($param)){
            
            $sysvar = $this->get_sys_parameters($param);
            if (!$sysvar){
                $sysvar[$param] = 'UA-71024445-1';
            }
            $this->session->set_userdata($param, $sysvar[$param]);
            
            return $sysvar[$param];
        }else{
            return $this->session->userdata($param);
        }
    }
    
    protected function _get_today_indonesia($timestamp=FALSE){
        $hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
        
        $date = getdate($timestamp ? $timestamp : time());
        $today = array(
            'hari'      => $hari[$date['wday']],
            'tanggal'   => $date['mday'],
            'bulan'     => $bulan[$date['mon']-1],
            'tahun'     => $date['year']
        );
        
        return $today;
    }
    
    protected function meta_set_props($arr_meta){
        if (isset($this->data['meta'])){
            $this->data['meta'] = array_merge($this->data['meta'], $arr_meta);
        }else{
            $this->data['meta'] = $arr_meta;
        }
        
        return $this->data['meta'];
    }
    
    protected function meta_set_value($key, $value){
        if (isset($this->data['meta'])){
            $this->data['meta'][$key] = $value;
            
            return $this->data['meta'][$key];
        }
        
        return NULL;
    }
    
    protected function meta_get_value($key){
        if (isset($this->data['meta']) && isset($this->data['meta'][$key])){
            return $this->data['meta'][$key];
        }
        
        return NULL;
    }
    
    protected function set_keyword(array $keyword){
        $this->meta_keyword = $keyword;
        
        $this->meta_set_value('keywords', implode(',',  $this->meta_keyword));
    }
    
    protected function add_keyword($keyword){
        if (is_array($keyword)){
            $this->meta_keyword = array_merge($this->meta_keyword, $keyword);
        }else{
            $this->meta_keyword[] = $keyword;
        }
        
        $this->meta_set_value('keywords', implode(',',  $this->meta_keyword));
    }
    
    /**
     * Create meta tags default for all pages
     */
    private function meta_set_default(){
        $this->data['meta'] = array(
            'author'            =>  'Indonesia Satu',
            'description'       =>  'Berita online yang mengulas secara lengkap tentang kejadian yang terjadi setiap hari nasional dan internasional',
            'keywords'          => implode(',', $this->meta_keyword),            
            'canonical'         => current_url(),
            
        );
    }
    
    protected function og_set_value($key, $value){
        if (isset($this->data['og_props'])){
            $this->data['og_props'][$key] = $value;
            
            return $this->data['og_props'][$key];
        }
        
        return NULL;
    }
    
    protected function ogg_get_value($key){
        if (isset($this->data['og_props']) && isset($this->data['og_props'][$key])){
            return $this->data['og_props'][$key];
        }
        
        return NULL;
    }
    
    protected function og_set_props($arr_prop){
        //$_ogg_properties = array('title','site_name','description','url','fb:app_id','type','local','article:author','article:publisher','image');
        if (is_array($arr_prop)){
            foreach ($arr_prop as $prop => $value){
                $this->data['og_props'][$prop] = $value;
            }
        }
    }
    
    private function og_set_default(){
        $this->data['og_props'] = array(
            'fb:app_id'         =>  $this->get_FB_ID(),
            'title'             =>  'Indonesia Satu',
            'site_name'         =>  'Indonesia Satu',
            'description'       =>  'Berita online yang mengulas secara lengkap tentang kejadian yang terjadi setiap hari nasional dan internasional',          
            'url'               =>  current_url(),
            'type'              => 'article'
        );
    }
}

class MY_AdminController extends MY_Controller {
    protected $REC_PER_PAGE = 12;
    
    function __construct() {
        parent::__construct();
        if (!$this->users->isLoggedin()){
            redirect('home');
            exit;
        }
        $this->data['meta_title'] = 'IndonesiaSatu.co - CMS';
        $this->data['active_menu'] = 'dashboard';
        $this->data['body_class'] = 'skin-blue';
        
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
        
        $this->data['breadcumb'] = array();
        //set default breadcumb
        breadcumb_add($this->data['breadcumb'], '<i class="fa fa-home"></i> Dashboard', site_url('cms/dashboard'));
        
        $this->data['page_title'] = 'Dashboard';
        
        //set user loggedin info
        $this->data['avatar_url_me'] = $this->users->get_avatar_url();
        //set parameter for filemanager
        $this->_filemanager_setup();
    }
    
    private function _filemanager_setup(){
        if (!session_id()){
            session_start();
        }
        
        if (ENVIRONMENT == 'development'){
            $filemanager = array(
                'FM_BASEURL'        => rtrim(site_url(), '/'),
                'FM_UPLOAD_DIR'     => '/' . ltrim(userfiles_basepath(config_item('images')), '/'),
                'FM_CURRENT_PATH'   => '../../'. ltrim(userfiles_basepath(config_item('images')), '/'),
                'FM_THUMB_PATH'     => '../../'. ltrim(userfiles_basepath(config_item('rfthumbs')), '/'),
                'FM_RESIZE_PATH'    => '../../'. ltrim(userfiles_basepath(config_item('thumbs')), '/')
            );
        }else{
            $filemanager = array(
                'FM_BASEURL'        => rtrim(userfiles_baseurl(), '/'),
                'FM_UPLOAD_DIR'     => '/' . userfiles_basepath(config_item('images')),
                'FM_CURRENT_PATH'   => '../../../../images.indonesiasatu.co/www/'. config_item('images'),
                //'FM_THUMB_PATH'     => '../../../../images.indonesiasatu.co/www/'. config_item('rfthumbs'),
                'FM_THUMB_PATH'     => '../../thumbs/',
                'FM_RESIZE_PATH'    => '../../../../images.indonesiasatu.co/www/'. config_item('thumbs')
            );
        }
        $_SESSION['FILEMANAGER'] = $filemanager;
    }
}

/**
 * Description of MY_Ajax
 *
 * @author Marwan Saleh <marwan.saleh@ymail.com>
 */
class MY_Ajax extends MY_BaseController {
    
    function __construct() {
        parent::__construct();
        
        //check if ajax request
        //$this->_exit_not_ajax_request();
    }
    
    function send_output($data=NULL){
        $this->output->set_content_type('application/json');
        $this->output->set_header('Cache-Control: no-cache, must-revalidate');
        $this->output->set_header('Expires: '.date('r', time()+(86400*365)));

        $output = json_encode($data);

        $this->output->set_output($output);
    }
    
    private function _exit_not_ajax_request(){
        if (!$this->input->is_ajax_request()){
            show_error('The requested page is not allowed to access', 401);
            exit;
        }
    }
}

/* News custom class */
class MY_News extends MY_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->model(array('article/article_m','article/category_m','article/tags_m'));
        
        $this->data['newstickers'] = $this->_newsticker(5);
        //$this->data['categories_articles'] = $this->_all_categories_articles_count(0,4);
        //$this->data['inspirasi_category'] = $this->get_inspirasi();
        $this->data['weather'] = $this->get_weather();
        
        $all_channel_and_childrens = $this->get_channels();
        $this->data['channels'] = $all_channel_and_childrens;
        $this->data['mainmenus'] = $this->_mainmenu() ;//$all_channel_and_childrens;
        $this->data['FB_ID'] = $this->get_FB_ID();
        $this->data['GA_Code'] = $this->get_GA_Code();
    }
    
    protected function get_forbidden_categories(){
        $forbidden_slug = array(CATEGORY_EMBUNPAGI, CATEGORY_TEROPONG);
        $forbidden_ids = $this->_get_category_inherit_ids($forbidden_slug);
        
        if ($forbidden_ids && count($forbidden_ids)){
            return $forbidden_ids;
        }
        return NULL;
    }
    
    protected function _article_categories($category_id, $num=3){
        if (!isset($this->article_m)){
            $this->load->model('article/article_m');
        }
        $category_id_list = array($category_id);
        //get children category
        $children_categories = $this->category_m->get_select_where('id',array('parent'=>$category_id));
        if ($children_categories){
            foreach ($children_categories as $child){
                $category_id_list [] = $child->id;
            }
        }
        
        $articles = array();
        $this->db->where_in('category_id', $category_id_list);
        $result = $this->article_m->get_offset('*',array('published' => ARTICLE_PUBLISHED),0,$num);
        foreach ($result as $item){
            $item->created_by_name = $this->user_m->get_value('full_name', array('id'=>$item->created_by));
            $articles [] = $item;
        }
        
        return $articles;
    }
    
    protected function _slider_news($num=5, $condition=NULL){
        $articles = array();
        $fields = 'title,url_title,image_url,comment,date,synopsis,created_by';
        
        $where = array('published'=>ARTICLE_PUBLISHED,'image_url !=' => '');
        if ($condition){
            $where = array_merge($where, $condition);
        }
        $this->db->like('types', ARTICLE_TYPE_SLIDER);
        $this->db->order_by('date desc');
        $result = $this->article_m->get_offset($fields,$where,0,$num);
        foreach ($result as $item){
            $item->created_by_name = $this->user_m->get_value('full_name',array('id'=>$item->created_by));
            $articles [] = $item;
        }
        
        return $articles;
    }
    
    protected function _highlight_news($num=5, $condition=NULL){
        $articles = array();
        $fields = 'title,url_title,image_url,comment,date,synopsis,created_by';
        
        $where = array('published'=>ARTICLE_PUBLISHED);
        if ($condition){
            $where = array_merge($where, $condition);
        }
        
        $this->db->like('types', ARTICLE_TYPE_HIGHLIGHT);
        $this->db->order_by('date desc');
        $result = $this->article_m->get_offset($fields,$where,0,$num);
        foreach ($result as $item){
            $item->created_by_name = $this->user_m->get_value('full_name',array('id'=>$item->created_by));
            $articles [] = $item;
        }
        
        return $articles;
    }
    
    protected function _latest_news($num=10, $condition=NULL){
        if (!isset($this->category_m)){
            $this->load->model('article/category_m');
        }
        $articles = array();
        $fields = 'title,category_id,url_title,url_short,image_url,comment,date,created_by';
        
        $where = array('published'=>ARTICLE_PUBLISHED);
        if ($condition){
            $where = array_merge($where, $condition);
        }
        
        /** set up category should not not be seen in news list **/
        $forbiden_cat_ids = $this->get_forbidden_categories();
        if ($forbiden_cat_ids){
            $this->db->where_not_in('category_id', $forbiden_cat_ids);
        }
        /** end setup **/
        
        $this->db->order_by('date desc');
        $result = $this->article_m->get_offset($fields,$where,0,$num);
        foreach ($result as $item){
            //$item->created_by_name = $this->user_m->get_value('full_name',array('id'=>$item->created_by));
            //get category
            $item->category = $this->category_m->get_value('name',array('id'=>$item->category_id));
            $articles [] = $item;
        }
        
        return $articles;
    }
    
    protected function _get_category_inherit_ids($slugs){
        if (!is_array($slugs)){
            $slugs = array($slugs);
        }
        
        $ids = array();
        
        foreach ($slugs as $slug){
            $categories = $this->category_m->get_select_where('id',array('slug' => $slug));
            if (!$categories){
                continue;
            }
            //get all inherent categories
            $categories_inherits = $this->category_m->get_select_where('id',array('parent'=>$categories[0]->id));
            if ($categories_inherits){
                $categories = array_merge($categories, $categories_inherits);
            }
            
            foreach ($categories as $cat){
                $ids [] = $cat->id;
            }
        }
        
        return $ids;
    }
    
    protected function _popular_news($num=10, $condition=NULL){
        $articles = array();
        $fields = 'title,url_title,url_short,image_url,comment,date,created_by,view_count,hide_author';
        
        $where = array('published'=>ARTICLE_PUBLISHED);
        if ($condition){
            $where = array_merge($where, $condition);
        }
        
        $this->db->order_by('view_count desc, date desc');
        $result = $this->article_m->get_offset($fields,$where,0,$num);
        foreach ($result as $item){
            $item->created_by_name = $this->user_m->get_value('full_name',array('id'=>$item->created_by));
            $articles [] = $item;
        }
        
        return $articles;
    }
    
    protected function _commented_news($num=10, $condition=NULL){
        $articles = array();
        $fields = 'title,url_title,url_short,image_url,comment,date,created_by';
        
        $this->db->order_by('comment desc, date desc');
        $where = array('published'=>ARTICLE_PUBLISHED, 'comment >'=>0);
        if ($condition){
            $where = array_merge($where, $condition);
        }
        $result = $this->article_m->get_offset($fields,$where,0,$num);
        foreach ($result as $item){
            $item->created_by_name = $this->user_m->get_value('full_name',array('id'=>$item->created_by));
            $articles [] = $item;
        }
        
        return $articles;
    }
    
    protected function _newsticker($num=5){
        $this->load->model('article/newsticker_m');
        
        return $this->newsticker_m->get_offset('*',array('active'=>1),0,$num);
    }
    
    protected function _allmenus($all=FALSE){
        $allmenu = array('items'=>array(),'parents'=>array());
        
        $condition = array('is_menu'=>1);
        if ($all){
            $condition = NULL;
        }
        
        $result = $this->category_m->get_offset('id,name,parent,slug',$condition,0,0);
        foreach ($result as $item){
            $allmenu['parents'] [$item->parent] [] = $item->id;
            $allmenu['items'][$item->id] = $item;
        }
        
        return $allmenu;
    }
    
    protected function _mainmenu(){
        //get as basis
        $allmenus = $this->_allmenus(TRUE);
        //get main categories parent=0,is_menu=1
        $mainmenus = $this->category_m->get_by(array('parent'=>0,'is_menu'=>1));
        $menus = array();
        
        //iterate selected mainmenus to get its children
        foreach($mainmenus as $mm){
            $mm->url = site_url('category/'.$mm->slug);
            $mm->children = array();
            if (isset($allmenus['parents'][$mm->id])){
                foreach ($allmenus['parents'][$mm->id] as $child_id){
                    $children = $allmenus['items'][$child_id];
                    $children->url = site_url('category/'.$children->slug);
                    $mm->children [] = $children;
                }
            }
            $menus [] = $mm;
        }
        
        return $menus;
    }
    
    protected function _home_category($num=2){
        $categories = array();
        
        $result = $this->category_m->get_offset('*',array('is_home'=>1),0,$num);
        foreach ($result as $category){
            $categories [] = $category;
        }
        
        return $categories;
    }
    
    protected function _all_categories_articles_count($limit=NULL,$groupin=0){
        $categories = array();
        $result = $this->category_m->get_offset('id,name,parent,slug',NULL,0,$limit);
        
        $group_index = 0;
        foreach ($result as $item){
            $item->article_count = $this->article_m->get_count(array('category_id'=>$item->id));
            
            if ($groupin>0){
                if ($group_index==$groupin){
                    $group_index = 0;
                }
                //create array for group
                if (!isset($categories[$group_index])){
                    $categories[$group_index] = array();
                }
                
                $categories[$group_index++][] = $item;
                
            }else{
                $categories [] = $item;
            }
        }
        
        return $categories;
    }
    
    protected function get_inspirasi($category_id=NULL){
        if (!$category_id){
            //get category id from session if exists
            if (!$this->session->userdata('INSPIRASI_CATEGORY_ID')){
                $category_id = $this->category_m->get_value('id', array('slug'=>'inspirasi'));
                if (!$category_id){
                    $category = $this->category_m->get_select_where('id',NULL,TRUE);
                    $category_id = $category->id;
                }
                $this->session->set_userdata('INSPIRASI_CATEGORY_ID', $category_id);
            }else{
                $category_id = $this->session->userdata('INSPIRASI_CATEGORY_ID');
            }
        }
        if ($this->session->userdata('INSPIRASI_CATEGORY')){
            $inspirasi = $this->session->userdata('INSPIRASI_CATEGORY');
            if (isset($inspirasi[$category_id])){
                return $inspirasi[$category_id];
            }
        }
        
        //get inspirasi category and its children
        $inspirasi = $this->get_category_inherits($category_id);
        $this->session->set_userdata('INSPIRASI_CATEGORY', array($category_id => $inspirasi));
        
        return $inspirasi;
    }
    
    protected function get_channels(){
        $result = array();
        
        //get allmenu as base data
        $all_menus = $this->_allmenus(TRUE);
        //get all main categories
        $main_categories = $this->category_m->get_by(array('parent'=>0));
        foreach ($main_categories as $item){
            $category = new stdClass();
            $category->id = $item->id;
            $category->name = $item->name;
            $category->slug = $item->slug;
            $category->children = array();
            //get category children
            if (isset($all_menus['parents'][$category->id])){
                foreach ($all_menus['parents'][$category->id] as $id){
                    $category->children [] = $all_menus['items'][$id];
                }
            }
            
            //add to collection
            $result[] = $category;
        }
        
        
        return $result;
    }
    
    protected function get_category_inherits($category_id){
        $category = NULL;
        $result = $this->_allmenus(TRUE);
        
        if (isset($result['items'][$category_id])){
            $category = $result['items'][$category_id];
            $category->children = array();
            //get category children
            if (isset($result['parents'][$category->id])){
                foreach ($result['parents'][$category->id] as $id){
                    $category->children [] = $result['items'][$id];
                }
            }
        }
        
        return $category;
    }


    protected function _photo_news($num=10){
        $this->load->model('photo/photo_m');
        
        return $this->photo_m->get_offset('*',NULL,0,$num);
    }
    
    protected function _article_view_counter($article_id){
        //check if this visitor already view counted
        $this->load->model('article/article_visit_m','visit_m');
        $visitor_id = $this->_get_unique_visitor();
        if ($this->visit_m->get_count(array('article_id'=>$article_id, 'visitor_id'=>$visitor_id))==0){
            $this->visit_m->save(array('date'=>time(),'article_id'=>$article_id,'visitor_id'=>$visitor_id));
            $this->article_m->increase_view($article_id);
        }
    }
    
    protected function _mobile_news($num=10, $condition=NULL){
        $articles = array();
        $fields = 'id,title,url_title,image_url,synopsis,date,created_by';
        
        $where = array('published'=>ARTICLE_PUBLISHED);
        if ($condition){
            $where = array_merge($where, $condition);
        }
        
        $this->db->order_by('date desc');
        $result = $this->article_m->get_offset($fields,$where,0,$num);
        foreach ($result as $item){
            //$item->created_by_name = $this->user_m->get_value('full_name',array('id'=>$item->created_by));
            $articles [] = $item;
        }
        
        return $articles;
    }
    
    /************** RATES FUNCTIONS HELPER ********/
    protected function _get_rates(){
        $this->load->model(array('rates/rates_m','rates/rates_update_m'));
        //$banks = array_keys(get_bankname_list()); //get bank code list from helper
        $bank_result = $this->rates_update_m->get_by(array('visible'=>1));
        
        $result = array();
        
        foreach ($bank_result as $bank){
            $rates = $this->_rate_get_data($bank->bank, $bank->last_update);
            if ($rates && count($rates)){
                $result_item = new stdClass();
                $result_item->bank = $bank->bank;
                $result_item->rates = $rates;
                
                $result [] = $result_item;
            }
        }
        
        return $result;
    }
    
    private function _rate_get_data($bank, $last_update){
        
        $items = $this->rates_m->get_by(array('bank'=>$bank,'last_update'=>$last_update));
        return $items;
    }
    
    protected function get_weather(){
        if (!isset($this->ow_cuaca_m)){
            $this->load->model('weather/ow_cuaca_m');
        }
        $today = date('Y-m-d');
        
        $result = $this->ow_cuaca_m->get_by(array('last_checked_date' => $today), TRUE);
        
        $cuaca = array(
            'date' => $today,
            'indonesia_date' => $this->_get_today_indonesia(strtotime($today)),
            'weather' => $result
        );
        
        return $cuaca;
    }
}

/*
 * file location: engine/application/core/MY_Controller.php
 */