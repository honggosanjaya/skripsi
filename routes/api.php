<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CashAccountController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LaporanPenagihanController;
use App\Http\Controllers\StaffController;
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

Route::post('v1/login', [LoginController::class, 'index']);
Route::group(['middleware' => 'auth:sanctum'], function(){
  Route::post('v1/logout', [LoginController::class, 'logoutApi']);
  Route::get('/user', [LoginController::class, 'checkUser']);
  // SHIPPER
  Route::prefix('shipper')->group(function() {
    Route::post('/retur', [ReturController::class, 'pengajuanReturAPI']);
    Route::get('/jadwalPengiriman', [OrderController::class, 'getListShippingAPI']);
    Route::get('/jadwalPengiriman/{id}', [OrderController::class, 'getdetailShippingAPI']);
  });
  Route::post('/pesanan/detail/{order:id}/dikirimkan', [OrderController::class, 'konfirmasiPengiriman']);
});
Route::get('/forceLogout', [LoginController::class, 'logoutUnauthorizedSPAApi']);

// SALESMAN
Route::post('/cariCustomer', [CustomerController::class, 'cariCustomerApi']);
Route::get('/dataFormTrip', [CustomerController::class, 'dataFormTripApi']);
Route::prefix('salesman')->group(function() {
  Route::get('/listitems/{id}', [ItemController::class, 'getListAllProductAPI']);
  Route::get('/historyitems/{id}', [ItemController::class, 'getListHistoryProductAPI']);
  Route::post('/updateStock', [ItemController::class, 'updateStockCustomer']);

  Route::get('/listitems/{id}/{name}', [ItemController::class, 'searchProductAPI']); 
  Route::get('/filteritems/{id}/{filterby}', [ItemController::class, 'filterProductAPI']);
});
// TRIP
Route::get('/tripCustomer/{id}', [CustomerController::class, 'dataCustomerApi']);
Route::post('/tripCustomer', [CustomerController::class, 'simpanCustomerApi']);
Route::post('/tripCustomer/foto/{id}', [CustomerController::class, 'simpanCustomerFotoApi']);
Route::post('/tripOrderCustomer', [OrderController::class, 'catatTripOrderApi']);
Route::post('/historytrip/{id}', [StaffController::class, 'getHistoryTripApi']);
// ORDER
Route::get('/products/search/{name}', [ItemController::class, 'searchProductAPI']); 
Route::post('/salesman/buatOrder', [OrderController::class, 'simpanDataOrderSalesmanAPI']);
Route::get('/tipeRetur', [ReturController::class, 'getTypeReturAPI']);
Route::get('/kodeEvent/{kode}', [EventController::class, 'dataKodeEventAPI']);
Route::get('/kodeCustomer/{id}', [OrderController::class, 'dataKodeCustomer']);
// KELUAR
Route::post('/keluarToko/{id}', [OrderController::class, 'keluarTripOrderApi']);
//UBAH PASSWORD
Route::post('/checkpassword/{staff:id}', [AuthController::class, 'checkPasswordAPI']);
Route::post('/changepassword/{staff:id}', [AuthController::class, 'changePasswordAPI']);

//CUSTOMER
Route::get('/filterProduk', [ItemController::class, 'filterProdukApi']);
Route::post('/customer/order/cart', [CartController::class, 'addToCart']);

Route::get('/test/{id}', [Controller::class, 'test']);

// Reimbursement
Route::get('/cashAcountOption', [CashAccountController::class, 'cashAccountOptionAPI']);
Route::post('/ajukanReimbursement', [CashAccountController::class, 'simpanReimbursementAPI']);
Route::post('/ajukanReimbursement/foto/{id}', [CashAccountController::class, 'simpanReimbursementFotoAPI']);
Route::get('/historyReimbursement/{id}', [CashAccountController::class, 'getHistoryReimbursementAPI']);

Route::post('/historyinvoice', [OrderController::class, 'getInvoiceAPI']);

Route::get('/administrasi/detailpenagihan/{invoice:id}', [LaporanPenagihanController::class, 'getDetailPenagihanAPI']);