<?php

require __DIR__.'/vendor/autoload.php';

use App\Comunication\Call;
use App\Comunication\Weather;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//DEFINE THE ALERT TIME
$message_time = '00:00:00';

while (true) {
    // SET YOUR CURRENT TIME ZONE
    //SEARCH FOR MORE INFORMATION AT 'WATHER API' DOCUMENTATION
    date_default_timezone_set('America/Sao_Paulo');

    
    //SET THE CURRENT TIME
    $current_time = date('H:i:s');  

    
    if ($current_time == $message_time) {
        
        $weather = new Weather();
        $weather_result = [];
        $weather_result = $weather->getWeatherByCity('YOUR_CITY_NAME','YOUR ID');
        Call::sendWeather($weather_result);
    
    }
    
    

}
