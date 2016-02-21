<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class wrapper for REST_Controller
 *
 * @author marwansaleh <amazzura.biz@gmail.com>
 */

class REST_Api extends REST_Controller {
    protected $_recs_per_page = 100;
    protected $result = array();
    
    private $_log_file;
    private $_log_path;
    
    function __construct($config = 'rest') {
        parent::__construct($config);
        //Load api helper
        $this->load->helper('api');
        
        $this->_log_file = config_item('log_filename') ? config_item('log_filename') : 'mylogfile.log';
        $this->_log_path = rtrim(sys_get_temp_dir(), '/') .'/';
    }
    
    public function service_not_found(){
        $this->result['status'] = FALSE;
        $this->result['message'] = 'Service not found';
        $this->response($this->result);
    }
    
    protected function remap_fields($arr_map, $data){
        $result = NULL;
        
        if (is_array($data)){
            $result = array();
            if (count($data)){
                foreach ($data as $item){
                    $result [] = $this->_remap_object_properties($arr_map, $item);
                }
            }
        }else{
            $result = $this->_remap_object_properties($arr_map, $data);
        }
        
        return $result;
    }
    
    private function _remap_object_properties($maps,$object){
        $new_class = new stdClass();
        foreach ($maps as $src => $dest){
            $new_class->{$dest} = isset($object->{$src})? $object->{$src} : NULL;
        }
        return $new_class;
    }
    
    protected function get_sys_vars($pattern=NULL){
        $this->load->helper('general');
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
    
    protected function read_log($lines=5, $adaptive = true){
        // Open file
        $filepath = $this->_log_path . $this->_log_file;
        
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
    
    protected function get_forbidden_categories(){
        $forbidden_slug = array(CATEGORY_EMBUNPAGI, CATEGORY_TEROPONG);
        $forbidden_ids = $this->_get_category_inherit_ids($forbidden_slug);
        
        if ($forbidden_ids && count($forbidden_ids)){
            return $forbidden_ids;
        }
        return NULL;
    }
    
    protected function _get_category_inherit_ids($slugs){
        if (!$this->category_m){
            $this->load->model('article/category_m');
        }
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
}
