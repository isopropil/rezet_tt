<?php

namespace App\Http\Controllers;

use App\Contracts\IWeatherService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class Weather extends AbstractController {
    use ValidatesRequests;

    /**
     * Get weather by given coordinates
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWeather(Request $request, IWeatherService $weather) {
        $data = $request->validate([
            'lat' => 'numeric',
            'lng' => 'numeric',
        ]);

        /**
         * Possible to add GeoIP for request user's coordinates, if it not sent by user, but I don't know,
         * is it needed for test task...
         */
        if (empty($data['lat']) || empty($data['lng'])) {
            return $this->errorResponse('Data unavailable - coordinates not sent');
        }

        $weather->getWeather($data['lat'], $data['lng']);

        return $this->successResponse([
            'temp' => $weather->getTemp(),
            'pressure' => $weather->getPressure(),
            'humidity' => $weather->getHumidity(),
            'temp_min' => $weather->getTempMin(),
            'temp_max' => $weather->getTempMax()
        ]);
    }

}
