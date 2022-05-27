<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReturController;
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

//sales cari customer
Route::post('/cariCustomer', [CustomerController::class, 'cariCustomerApi']);
Route::get('/dataFormTrip', [CustomerController::class, 'dataFormTripApi']);
//sales add/update data customer
Route::get('/tripCustomer/{id}', [CustomerController::class, 'dataCustomerApi']);
Route::post('/tripCustomer', [CustomerController::class, 'simpanCustomerApi']);
Route::post('/tripCustomer/foto/{id}', [CustomerController::class, 'simpanCustomerFotoApi']);


// filter item
Route::get('/filterProduk', [ItemController::class, 'filterProdukApi']);
//customer menambahkan data di keranjang
Route::post('/customer/order/cart', [CartController::class, 'addToCart']);


// sales pemesanan
Route::get('/products/search/{name}', [ItemController::class, 'searchProductAPI']); 
Route::post('/salesman/buatOrder', [OrderController::class, 'simpanDataOrderSalesmanAPI']);
Route::get('/kodeCustomer/{id}', [OrderController::class, 'dataKodeCustomer']);
Route::post('/tripOrderCustomer', [OrderController::class, 'catatTripOrderApi']);
Route::post('/keluarToko/{id}', [OrderController::class, 'keluarTripOrderApi']);
Route::get('/tipeRetur', [ReturController::class, 'getTypeReturAPI']);
Route::get('/kodeEvent/{kode}', [EventController::class, 'dataKodeEventAPI']);

Route::get('/forceLogout', [LoginController::class, 'logoutUnauthorizedSPAApi']);

Route::group(['middleware' => 'auth:sanctum'], function(){
  Route::post('v1/logout', [LoginController::class, 'logoutApi']);
  Route::get('/user', [LoginController::class, 'checkUser']);

  Route::prefix('salesman')->group(function() {
    Route::get('/listitems', [ItemController::class, 'getListAllProductAPI']);
  });
});

Route::post('v1/login', [LoginController::class, 'index']);
