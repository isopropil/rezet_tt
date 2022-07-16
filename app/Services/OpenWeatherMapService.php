<?php


namespace App\Services;


use App\Contracts\IWeatherService;
use GuzzleHttp\Client;

class OpenWeatherMapService extends AbstractWeatherService implements IWeatherService {
    protected function requestRawData($lat, $lng) {
        $client = new Client();
        $key = config('services.openweather.key');
        $res = $client->get('https://api.openweathermap.org/data/2.5/weather', [
            'timeout' => 10,
            'query' => [
                'lat' => $lat,
                'lon' => $lng,
                'appid' => $key
            ]
        ]);

        if ($res->getStatusCode() != 200) {
            throw new \Exception('Error requesting weather data');
        }

        $body = (string)$res->getBody();
        $body = json_decode($body, true);
        if (!$body) {
            throw new \Exception('Error requesting weather data');
        }
        return $body;
    }

    public function getTemp() {
        return $this->rawData ? (float)$this->rawData['main']['temp'] ?? null : null;
    }

    public function getPressure() {
        return $this->rawData ? (float)$this->rawData['main']['pressure'] ?? null : null;
    }

    public function getHumidity() {
        return $this->rawData ? (float)$this->rawData['main']['humidity'] ?? null : null;
    }

    public function getTempMin() {
        return $this->rawData ? (float)$this->rawData['main']['temp_min'] ?? nul : null;
    }

    public function getTempMax() {
        return $this->rawData ? (float)$this->rawData['main']['temp_max'] ?? null : null;
    }


}
