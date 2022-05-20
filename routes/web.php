<?php

use App\Http\Controllers\CustomerTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('owner')->middleware('owner')->group(function() {
  Route::get('/', [HomeController::class, 'indexOwner']);
});
Route::prefix('supervisor')->middleware('supervisor')->group(function() {
  Route::get('/', [HomeController::class, 'indexSupervisor']);

  //Route untuk jenis customer
  Route::get('/jenis', [CustomerTypeController::class, 'index']);
  Route::get('/jenis/cari', [CustomerTypeController::class, 'search']);
  Route::get('/jenis/tambah', [CustomerTypeController::class, 'create']);
  Route::post('/jenis/tambahjenis', [CustomerTypeController::class, 'store']);
  Route::get('/jenis/ubah/{customertype:id}', [CustomerTypeController::class, 'edit']);
  Route::put('/jenis/ubahjenis/{customertype:id}', [CustomerTypeController::class, 'update']);
});
// Route::prefix('salesman')->middleware('salesman')->group(function() {
//   Route::get('/', [HomeController::class, 'indexSalesman']);
// });
// Route::prefix('shipper')->middleware('shipper')->group(function() {
//   Route::get('/', [HomeController::class, 'indexShipper']);
// });
Route::prefix('administrasi')->middleware('administrasi')->group(function() {
  Route::get('/', [HomeController::class, 'indexAdministrasi']);

  Route::get('/stok', [ItemController::class, 'indexAdministrasi']);
  Route::get('/stok/cari', [ItemController::class, 'cariStok']);
  Route::get('/stok/riwayat', [ItemController::class, 'riwayatAdministrasi']);
  Route::get('/stok/riwayat/cari', [ItemController::class, 'cariRiwayat']);

  Route::prefix('stok')->group(function(){
    Route::get('/', function () {
      return view('administrasi.stok.stok');
    });
    Route::resource('/produk', ItemController::class);
    Route::get('/pengadaan', [ItemController::class, 'productList'])->name('products.list');
    Route::get('/pengadaan/cart', [CartController::class, 'cartList'])->name('cart.list');
    Route::post('/pengadaan/cart', [CartController::class, 'addToCart'])->name('cart.store');
    Route::post('/pengadaan/update-cart', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('/pengadaan/remove', [CartController::class, 'removeCart'])->name('cart.remove');
    Route::post('/pengadaan/clear', [CartController::class, 'clearAllCart'])->name('cart.clear');
  });

  Route::get('/datacustomer', [CustomerController::class, 'administrasiIndex']);
  Route::get('/datacustomer/create', [CustomerController::class, 'administrasiCreate']);
  Route::post('/datacustomer/tambahcustomer', [CustomerController::class, 'administrasiStore']);
  Route::get('/datacustomer/ubah/{customer:id}', [CustomerController::class, 'administrasiEdit']);
  Route::put('/datacustomer/ubahcustomer/{customer:id}', [CustomerController::class, 'administrasiUpdate']);
  Route::get('/datacustomer/{customer:id}', [CustomerController::class, 'administrasiShow']);
  Route::post('/datacustomer/ubahstatus/{customer:id}', [CustomerController::class, 'administrasiEditStatusCustomer']);
});

Route::prefix('customer')->middleware('customer')->group(function() {
  Route::get('/', [HomeController::class, 'indexCustomer']);

  //Route untuk produk
  Route::get('/produk', [ItemController::class, 'customerIndex']);
  Route::get('/produk/cari', [ItemController::class, 'customerSearch']);
});

require __DIR__.'/auth.php';

Route::get('/{path}', function () {
  return view('reactView');
})->where('path', '.*');