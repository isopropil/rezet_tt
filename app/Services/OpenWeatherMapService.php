<?php


namespace App\Services;


use App\Contracts\IWeatherService;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class OpenWeatherMapService extends AbstractWeatherService implements IWeatherService {
    protected function requestRawData($lat, $lng) {
        $client = new Client();
        $key = config('services.openweather.key');
        $url = config('services.openweather.url');
        $res = $client->get($url, [
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

    private function fromRawData($path) {
        return Arr::get($this->rawData, $path, null);
    }

    public function getTemp() {
        return (float)$this->fromRawData('main.temp');
    }

    public function getPressure() {
        return (float)$this->fromRawData('main.pressure');
    }

    public function getHumidity() {
        return (float)$this->fromRawData('main.humidity');
    }

    public function getTempMin() {
        return (float)$this->fromRawData('main.temp_min');
    }

    public function getTempMax() {
        return (float)$this->fromRawData('main.temp_max');
    }


}
