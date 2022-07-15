<?php


namespace App\Contracts;


interface IWeatherService {

    /**
     * Retrieve weather information from some weather provider
     * @param $lat - latitude
     * @param $lng - longiture
     * @return $this
     */
    public function getWeather($lat, $lng);
}
