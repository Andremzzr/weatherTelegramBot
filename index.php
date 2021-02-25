<?php

require __DIR__.'/vendor/autoload.php';

use App\Comunication\Call;
use App\Comunication\Weather;
use App\Comunication\TelegramBot;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

<<<<<<< HEAD
Call::getLastMessage();
=======
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
        $weather_result = $weather->getWeatherByCity('YOUR_CITY_NAME');
        Call::sendWeather($weather_result,'YOUR_TELEGRAM_ID');
    
    }
    
    

}
>>>>>>> 9f3dd70fb11f51d8b8d748f0cb57d072202cb3a5
