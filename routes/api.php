<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Weather as WeatherController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/signup', [AuthController::class, 'postSignup']);
Route::post('/login', [AuthController::class, 'postLogin']);
Route::get('/login/google', [AuthController::class, 'getGoogleLogin']);
Route::get('/login/google/callback', [AuthController::class, 'getGoogleCallback']);

Route::get('/logout', [AuthController::class, 'getLogout']);
Route::get('/user-data', [AuthController::class, 'getUserData']);

Route::get('/weather', [WeatherController::class, 'getWeather'])
    ->middleware('auth');
