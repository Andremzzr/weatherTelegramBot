<?php


namespace App\Comunication;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;

class Call
{
    /**
     * Return the correct salutation based on your current hour of the day
     *
     * @return string
     */
    public function salutationConf()
    {
        $salutation='';
        //DEFINE YOUR TIME ZONE
        date_default_timezone_set('Example/Timezone');
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
     * @param  array $message
     * @return void
     */
    public static function sendWeather(array $message)
    {   
        //SET YOUR CURRENT TIME ZONE
        //SEARCH FOR MORE INFORMATION AT 'WATHER API' DOCUMENTATION
        date_default_timezone_set('Example/Timezone');
        
         //SET THE CURRENT TIME
        $current_time = date('H:i');
        
        $salutation = new Call();
        
        //CONVERTING TEMPERATURE INTO STRING 
        $message['temp'] = strval(round($message['temp']));
        
        $final_message = $salutation->salutationConf()." The time is ".$current_time.'. It is '.$message['temp'].'°C. Climate Status: '.$message['weather'][0];
        

        // BOT INSTANCE
        $bot = new BotApi($_ENV['API_TELEGRAM_TOKEN']);
        //SENDING THE MESSAGE
        $bot->sendMessage($_ENV['TELEGRAM_CHAT_ID'], $final_message);

    }
 
    

}