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


        $weather->getWeather($data['lat'], $data['lng']);

        return \response()->json([
            'status' =>'ok',
            'payload' => []
        ]);
    }

}
