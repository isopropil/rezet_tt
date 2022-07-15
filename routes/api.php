<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/signup', [\App\Http\Controllers\Auth::class, 'postSignup']);
Route::post('/login', [\App\Http\Controllers\Auth::class, 'postLogin']);
Route::get('/login/google', [\App\Http\Controllers\Auth::class, 'getGoogleLogin']);
Route::get('/login/google/callback', [\App\Http\Controllers\Auth::class, 'getGoogleCallback']);

Route::get('/logout', [\App\Http\Controllers\Auth::class, 'getLogout']);
Route::get('/user-data', [\App\Http\Controllers\Auth::class, 'getUserData']);

Route::get('/weather', [\App\Http\Controllers\Weather::class, 'getWeather'])
    ->middleware('auth');
