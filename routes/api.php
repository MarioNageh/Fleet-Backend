<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusController;
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

/////////////////////////////// Auth ///////////////////////////////////////////
Route::post("signUp", [AuthController::class, 'signUp']);
Route::post("login", [AuthController::class, 'login']);
///////////////////////////////////////////////////////////////////////////////
Route::middleware('auth:sanctum')->group(function () {
    ////////////////////////////////////////////////////////
    Route::get("user",[AuthController::class,'user']);
    ///////////////////////////////////
    Route::get("city",[CityController::class,'getAllCities']);
    ///////////////////////////////////
    Route::get("bus",[BusController::class,'getAllBuses']);

});
