<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\CustomerController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/cariCustomer', [CustomerController::class, 'cariCustomerApi']);
Route::get('/dataFormTrip', [CustomerController::class, 'dataFormTripApi']);

Route::get('/tripCustomer/{id}', [CustomerController::class, 'dataCustomerApi']);
Route::post('/tripCustomer', [CustomerController::class, 'simpanCustomerApi']);
Route::post('/tripCustomer/foto/{id}', [CustomerController::class, 'simpanCustomerFotoApi']);


Route::group(['middleware' => 'auth:sanctum'], function(){
  Route::post('v1/logout', [LoginController::class, 'logoutApi']);
});

Route::post('v1/login', [LoginController::class, 'index']);