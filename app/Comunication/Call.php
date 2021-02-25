<?php


namespace App\Comunication;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;

/**
 * Class that send messages to telegram
 */
class Call
{
    /**
     * Return the correct salutation based on your current hour of the day
     *
     * @param string $timezone
     * 
     * @return string
     */
    public function salutationConf(string $timezone)
    {
        $salutation='';
        //DEFINE YOUR TIME ZONE
        date_default_timezone_set($timezone);
        $salutation_time_config = intval(date('H'));

        if ($salutation_time_config<=12 and $salutation_time_config >=4 ) {
            $salutation='Good morning!!!';
        } 
        else if ($salutation_time_config >12 and $salutation_time_config <=18) {
            $salutation='Good afternoon!!!';
        }
        else {
            $salutation='Good evening!!!';
        }

        return $salutation;
    }
    
    /**
     * Receive the array from Weather API and transform it in a string and send it to telegram's chat
     *
     * @param array   $message 
     * @param integer $id
     * 
     * @return void
     */
    public static function sendWeather(array $message, int $id)
    {   
        $lat = strval($message['lat']);
        $long = strval($message['long']);
        $website = "http://api.timezonedb.com/v2.1/get-time-zone?key=".$_ENV['TIME_ZONE_API_KEY']."&format=json&by=position&lat=".$lat."&lng=".$long;
        $update = file_get_contents($website);
        $update=json_decode($update, true);

        //SET YOUR CURRENT TIME ZONE
        //SEARCH FOR MORE INFORMATION AT 'WATHER API' DOCUMENTATION
        date_default_timezone_set($update['zoneName']);
        
         //SET THE CURRENT TIME
        $current_time = date('H:i');
        
        $salutation = new Call();
        
        //CONVERTING TEMPERATURE INTO STRING 
        $message['temp'] = strval(round($message['temp']));
        
        $final_message = $salutation->salutationConf($update['zoneName'])." Status from ".$message['name']."."." The time is ".$current_time.'. It is '.$message['temp'].'°C. Climate Status: '.$message['weather'][0]." ->".$message['weather'][1];
        

        // BOT INSTANCE
        $bot = new BotApi($_ENV['API_TELEGRAM_TOKEN']);
        //SENDING THE MESSAGE
        $bot->sendMessage($id, $final_message);

    }
    
    /**
     * Send Message
     *
     * @param string  $message
     * @param integer $id
     * 
     * @return void
     */
    public static function sendMessage(string $message,int $id)
    {
          // BOT INSTANCE
          $bot = new BotApi($_ENV['API_TELEGRAM_TOKEN']);
          //SENDING THE MESSAGE
          $bot->sendMessage($id, $message);
    }

    /**
     * Get the last message in telegram's chat
     *
     * @return void
     */
    public static function getLastMessage()
    {
        /**
        * Id da mensagem a ser lida
        */
        $next_id_message = 0;
        /**
         * Id da ultima mensagem 
         */
        $last_id_message = 0;
        /**
         * String ta ultima mensagem
         */
        $last_message = '';

        $key_message = false;



        while (true) {
            $website = "http://api.telegram.org/bot".$_ENV['API_TELEGRAM_TOKEN']."/getUpdates";
            $update = file_get_contents($website);
            $update=json_decode($update, true);

            foreach ($update['result'] as $key => $lista) {
                $lista_arrays = $update['result'];
        
                //LAST ARRAY IN JSON
                if (end($lista_arrays) == $lista) {
            
                    $last_id_message = $lista['update_id'];
                    
                    if ($next_id_message == 0) {
                        $next_id_message = $last_id_message;
                    }
                    
                    if ($next_id_message == $last_id_message ) {
               
                        $last_message = $lista['message']['text'];
                        if ($last_message != '/city' and $key_message == false) {
                            $next_id_message =$last_id_message +1;
                            continue;
                        }
                        //ativar o comando /city
                        if ($last_message == '/city') {
                            Call::sendMessage('Tell me your city:', $lista['message']['chat']['id']);
                            $next_id_message =$last_id_message +1;
                            $key_message = true;
                            continue;
                        }
                        if ($key_message) {
                            
                        
                                $weather = new Weather();
                                $weather_result = [];
                           
                                $weather_result = $weather->getWeatherByCity($last_message);
                            
                                Call::sendWeather($weather_result, $lista['message']['chat']['id']);
                                $next_id_message =$last_id_message +1;
                                $key_message = false;
                                echo "Enviado".PHP_EOL;
                                echo "=======".PHP_EOL;

                        }
                        
           
                    }
                    else 
                    {
               
                        $last_message = $lista['message']['text'];
                        echo "Next Id: ".$next_id_message.PHP_EOL;
                        echo 'Current Update Id: '.$lista['update_id'].PHP_EOL;
                        echo $last_message."->";
                        echo "Nao enviado".PHP_EOL;
                        echo "=======".PHP_EOL;
                    }
                }
        
     
            }
            sleep(10);
        }
    }

}
