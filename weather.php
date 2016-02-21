<?php
class WeatherSync {
    private $_base_api_url = "http://indonesiasatu.co/service/";
    
    protected function syncronize_weather(){
        $end_point = $this->_base_api_url . 'weather/sync';
        
        $result = file_get_contents($end_point);
        if ($result){
            echo $result;
        }else{
            echo 'Failed to syncronize weather api on '.$end_point . PHP_EOL;
        }
    }
    public function main(){
        $this->syncronize_weather();
    }
}

echo "<<<<<Script ruuning for weather>>>>>" . PHP_EOL;
$weather = new WeatherSync();
$weather->main();
echo "<<<<<End of script>>>>>" .PHP_EOL;