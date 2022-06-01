<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\Controller;
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
// sales cari produk berdasarkan nama
Route::get('/products/search/{name}', [ItemController::class, 'searchProductAPI']); 
//customer dan sales melakukan filter item
Route::get('/filterProduk', [ItemController::class, 'filterProdukApi']);
//customer menambahkan data di keranjang
Route::post('/customer/order/cart', [CartController::class, 'addToCart']);

// sales checkout
Route::get('/products/search/{name}', [ItemController::class, 'searchProductAPI']); 
Route::post('/salesman/buatOrder', [OrderController::class, 'simpanDataOrderSalesmanAPI']);

// sales sudah ada kode customer
Route::get('/kodeCustomer/{id}', [OrderController::class, 'dataKodeCustomer']);

// catat trip untuk order
Route::post('/tripOrderCustomer', [OrderController::class, 'catatTripOrderApi']);

// ubah trip untuk keluar
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
Route::prefix('shipper')->group(function() {
  Route::get('/jadwalPengiriman', [OrderController::class, 'getListShippingAPI']);
  Route::get('/jadwalPengiriman/{id}', [OrderController::class, 'getdetailShippingAPI']);
});
Route::get('/test/{id}', [Controller::class, 'test']);

Route::post('v1/login', [LoginController::class, 'index']);

Route::post('/checkpassword/{staff:id}', [AuthController::class, 'checkPasswordAPI']);
Route::post('/changepassword/{staff:id}', [AuthController::class, 'changePasswordAPI']);
Route::post('/pesanan/detail/{order:id}/dikirimkan', [OrderController::class, 'konfirmasiPengiriman']);
