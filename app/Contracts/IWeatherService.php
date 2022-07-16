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

    /**
     * Get current temperature
     * @return float
     */
    public function getTemp();

    /**
     * Get current pressure
     * @return float
     */
    public function getPressure();

    /**
     * Get current humidity
     * @return float
     */
    public function getHumidity();

    /**
     * Get min temperature in locality
     * @return float
     */
    public function getTempMin();

    /**
     * Get max temperature in locality
     * @return float
     */
    public function getTempMax();

}
