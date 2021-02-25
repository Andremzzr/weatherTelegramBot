<?php


namespace App\Comunication;



class Weather
{
    private $baseURI = "https://api.openweathermap.org/data/2.5";
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = $_ENV['WEATHER_API_KEY'];
        $this->client = curl_init();
        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, true);

    }

    public function getWeatherByCity(string $name): array
    {
        
            $payload = http_build_query(
                [
                'q' => $name,
                'appId' => $this->apiKey
                ]
            );
            $uri = $this->baseURI . "/weather?" . $payload;

            curl_setopt($this->client, CURLOPT_URL, $uri);
            $result = json_decode(curl_exec($this->client), true);

            // Kelvin to Celsius
            $celsius = $result['main']['temp'] - 272.15;

            // Get the city name
            $city_name = $result['name'];

            //LAT
            $lat = $result['coord']['lat'];

            //LONG
            $long = $result['coord']['lon'];

            // Weather array
            $weathers = [];

        foreach ($result['weather'] as $weather) {
            $weathers[] = $weather['main'];
            $weathers[] = $weather['description'];
        }

            return [
            'temp' => $celsius,
            'weather' => $weathers,
            'name'=> $city_name,
            'lat' => $lat,
            'long' => $long
            ];

        
       

    }

}