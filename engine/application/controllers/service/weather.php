<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Weather
 *
 * @author marwansaleh
 */
class Weather extends REST_Api {
    protected $icon_base = 'http://openweathermap.org/img/w/';
    protected $icon_ext = '.png';
    protected $icon_local_path = 'assets/img/cuaca/';
    
    function __construct($config='rest') {
        parent::__construct($config);
        //load models
        $this->load->model(array('weather/ow_city_m','weather/ow_cuaca_m'));
    }
    
    function index_get($id=NULL){
        $remap_fields = array(
            'id'    => 'id',
            'city_name' => 'city',
            'api_result_summary' => 'summary',
            'temp'  => 'temperature',
            'pressure' => 'pressure',
            'humidity' => 'humidity',
            'icon_original_url' => 'original_icon_url',
            'icon_local_url' => 'icon_url'
        );
        if ($id){
            $item = $this->ow_cuaca_m->get($id);
            if (isset($item->icon_local_url)){
                $item->icon_local_url = site_url($item->icon_local_url);
            }
            $this->result['item'] = $this->remap_fields($remap_fields, $item);
        }else{
            $items = $this->ow_cuaca_m->get();
            $result = array();
            foreach ($items as $item){
                if (isset($item->icon_local_url)){
                    $item->icon_local_url = site_url($item->icon_local_url);
                }
                $result [] = $item;
            }
            $this->result['items'] = $this->remap_fields($remap_fields, $result);
        }
        
        $this->response($this->result);
    }
    
    function last_get($city_id=NULL){
        if ($city_id){
            $result = $this->ow_cuaca_m->get_by(array('city_id' => $city_id), TRUE);
        }else{
            $result = $this->ow_cuaca_m->get_by(NULL, TRUE);
        }
        if (isset($result->last_checked_time)){
            $result->last_checked_time = date('Y-m-d H:i:s', $result->last_checked_time);
        }
        $this->result = $this->remap_fields(array('city_name'=>'city','api_result_summary'=>'summary','last_checked_date' => 'last_date', 'last_checked_time' => 'last_time'), $result);
        
        $this->response($this->result);
    }
    
    function sync_get($city_id=NULL){
        $api_key = '3330aaf0f92c101dc121d1c537a1406e';
        $api_base = 'http://api.openweathermap.org/data/2.5/weather?appid=' . $api_key . '&id=';

        $date = date('Y-m-d');
        $datetime = time();

        //get data from api
        $city_name = 'Jakarta';
        if (!$city_id) {
            $city_id = $this->_get_cityId($city_name);
        }

        //set api_end_point
        $api_end_point = $api_base . $city_id;
        $wheather_data_json = file_get_contents($api_end_point);
        if (!$wheather_data_json) {
            $this->result['message'] = 'Can not get api content';
        }
        $wheather_data = json_decode($wheather_data_json);
        if (!$wheather_data) {
            $this->result['message'] = 'Can not parsing data return from api';
        }

        //insert into database
        $data = array(
            'city_id' => $city_id,
            'city_name' => $city_name,
            'last_checked_date' => $date,
            'last_checked_time' => $datetime,
            'api_end_point' => $api_end_point,
            'api_result' => $wheather_data_json,
            'api_result_summary' => $wheather_data->weather[0]->description,
            'temp'  => $wheather_data->main->temp,
            'pressure' => $wheather_data->main->pressure,
            'humidity' => $wheather_data->main->humidity,
            'icon' => $wheather_data->weather[0]->icon,
            'icon_original_url' => $this->icon_base . $wheather_data->weather[0]->icon . $this->icon_ext,
            'icon_local_url' => $this->icon_local_path . $wheather_data->weather[0]->icon . $this->icon_ext
        );

        if ($this->ow_cuaca_m->save($data)) {
            $this->result['message'] = 'New data api inserted into database successfully';
            $this->result['data'] = $data;
        } else {
            $this->result['message'] = 'Failed to save data api into database';
        }

        //check if we have the icon on local
        if (!file_exists($data['icon_local_url'])) {
            $copied_message = '';
            $result_icon = $this->_save_weather_icon($data['icon_original_url'],$data['icon_local_url'], $copied_message);
            if ($result_icon){
                $this->result['icon'] = 'Icon copied successfully';
            }else{
                $this->result['icon'] = $copied_message;
            }
        }else{
            $this->result['icon'] = 'Icon exists';
        }
        
        $this->response($this->result);
    }
    
    private function _get_cityId($city_name) {
        $city_id = $this->ow_city_m->get_value('_id', array('name' => $city_name));

        return $city_id;
    }
    
    private function _save_weather_icon($url, $save_name, &$message) {
        $image = file_get_contents($url);
        if ($image!==FALSE){
            if (file_put_contents($save_name, $image)){
                $message = 'Success copy icon';
                return TRUE;
            }else{
                $message = 'Can not write content to file';
            }
        }else{
            $message = 'Can not get content of file';
        }
        
        return FALSE;
//        $ch = curl_init($url);
//        $fp = fopen($save_name, 'wb');
//        curl_setopt($ch, CURLOPT_FILE, $fp);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_exec($ch);
//        curl_close($ch);
//        fclose($fp);
    }
    
}

/*
 * file location: engine/application/controllers/service/weather.php
 */
