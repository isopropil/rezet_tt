<?php

namespace App\Http\Controllers;

use App\Contracts\IUserRepository;
use App\Contracts\IWeatherService;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Laravel\Socialite\Facades\Socialite;

class Weather extends BaseController {
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
            return \response()->json([
                'status' =>'error',
                'payload' => 'Data unavailable - coordinates not sent'
            ]);
        }

        $weather->getWeather($data['lat'], $data['lng']);

        return \response()->json([
            'status' =>'ok',
            'payload' => [
                'temp' => $weather->getTemp(),
                'pressure' => $weather->getPressure(),
                'humidity' => $weather->getHumidity(),
                'temp_min' => $weather->getTempMin(),
                'temp_max' => $weather->getTempMax()
            ]
        ]);
    }

}
