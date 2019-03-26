<?php

namespace Drupal\weather;

use Guzzle\Http\Client;

class WeatherService {  
  public function WeatherMethod($city) {
    $app = \Drupal::config('weather.settings')->get('app');
    $client = new \GuzzleHttp\Client();
    $response = $client->get('https://samples.openweathermap.org/data/2.5/weather?q='.$city.'&appid='.$app);
    // kint($response->getBody()->getContents());
    // kint($response);
    // exit();
    return json_decode($response->getBody()->getContents());
  }
}
