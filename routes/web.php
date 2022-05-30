<?php

use App\Http\Controllers\CustomerTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ReturController;
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
  Route::get('/profil', [HomeController::class, 'lihatProfil']);
});
Route::prefix('supervisor')->middleware('supervisor')->group(function() {
  Route::get('/', [HomeController::class, 'indexSupervisor']);

  //Route untuk event
  Route::get('/event', [EventController::class, 'index']);
  Route::get('/event/cari', [EventController::class, 'search']);
  Route::get('/event/tambah', [EventController::class, 'create']);
  Route::post('/event/tambahevent', [EventController::class, 'store']);
  Route::get('/event/ubah/{event:id}', [EventController::class, 'edit']);
  Route::put('/event/ubahevent/{event:id}', [EventController::class, 'update']);

  //Route untuk wilayah
  Route::get('/wilayah', [DistrictController::class, 'index']);
  Route::get('/wilayah/cari', [DistrictController::class, 'search']);
  Route::get('/wilayah/tambah', [DistrictController::class, 'create']);
  Route::get('/wilayah/lihat', [DistrictController::class, 'lihat']);
  Route::post('/wilayah/tambahwilayah', [DistrictController::class, 'store']);
  Route::get('/wilayah/ubah/{district:id}', [DistrictController::class, 'edit']);
  Route::put('/wilayah/ubahwilayah/{district:id}', [DistrictController::class, 'update']);

  // Route untuk data customer termasuk limit pembelian
  Route::get('/datacustomer', [CustomerController::class, 'dataCustomer']);
  Route::get('/datacustomer/cari', [CustomerController::class, 'supervisorSearch']);
  Route::get('/datapengajuan', [CustomerController::class, 'dataPengajuanLimit']);
  Route::get('/datapengajuan/{customer:id}', [CustomerController::class, 'detailDataPengajuanLimit']);
  Route::post('/datapengajuan/setuju/{customer:id}', [CustomerController::class, 'setujuPengajuanLimit']);
  Route::post('/datapengajuan/tolak/{customer:id}', [CustomerController::class, 'tolakPengajuanLimit']);

  //Route untuk jenis customer
  Route::get('/jenis', [CustomerTypeController::class, 'index']);
  Route::get('/jenis/cari', [CustomerTypeController::class, 'search']);
  Route::get('/jenis/tambah', [CustomerTypeController::class, 'create']);
  Route::post('/jenis/tambahjenis', [CustomerTypeController::class, 'store']);
  Route::get('/jenis/ubah/{customertype:id}', [CustomerTypeController::class, 'edit']);
  Route::put('/jenis/ubahjenis/{customertype:id}', [CustomerTypeController::class, 'update']);

  // Route untuk data staf
  Route::resource('/datastaf', StaffController::class);
  Route::post('/datastaf/ubahstatus/{staf:id}', [StaffController::class, 'supervisorEditStatusStaf']);

  //Route untuk profil supervisor
  Route::get('/profil', [HomeController::class, 'lihatProfil']);
});
// Route::prefix('salesman')->middleware('salesman')->group(function() {
//   Route::get('/', [HomeController::class, 'indexSalesman']);
// });
// Route::prefix('shipper')->middleware('shipper')->group(function() {
//   Route::get('/', [HomeController::class, 'indexShipper']);
// });
Route::prefix('administrasi')->middleware('administrasi')->group(function() {
  Route::get('/', [HomeController::class, 'indexAdministrasi']);

  //Route untuk pesanan
  Route::get('/pesanan', [OrderController::class, 'index']);
  Route::get('/pesanan/cari', [OrderController::class, 'search']);
  Route::get('/pesanan/filter', [OrderController::class, 'filter']);
  Route::get('/pesanan/detail/{order:id}', [OrderController::class, 'viewDetail']);
  Route::get('/pesanan/detail/{order:id}/kapasitas', [OrderController::class, 'viewKapasitas']);
  Route::get('/pesanan/detail/{order:id}/cetak-memo', [OrderController::class, 'cetakMemo']);
  Route::get('/pesanan/detail/{order:id}/cetak-invoice', [OrderController::class, 'cetakInvoice']);
  Route::get('/pesanan/detail/{order:id}/cetak-sj', [OrderController::class, 'cetakSJ']);
  Route::post('/pesanan/setuju/{order:id}', [OrderController::class, 'setujuPesanan']);
  Route::post('/pesanan/tolak/{order:id}', [OrderController::class, 'tolakPesanan']);
  Route::get('/pesanan/detail/{order:id}/pengiriman', [OrderController::class, 'viewPengiriman']);
  Route::post('/pesanan/detail/{order:id}/dikirimkan', [OrderController::class, 'konfirmasiPengiriman']);

  //Route untuk retur
  Route::get('/retur', [ReturController::class, 'index']);
  Route::get('/retur/cari', [ReturController::class, 'search']);
  Route::get('/retur/{retur:no_retur}', [ReturController::class, 'viewRetur']);
  Route::get('/retur/cetak-retur/{retur:no_retur}', [ReturController::class, 'cetakRetur']);

  //Route untuk kendaraan
  Route::get('/kendaraan', [VehicleController::class, 'index']);
  Route::get('/kendaraan/cari', [VehicleController::class, 'search']);
  Route::get('/kendaraan/tambah', [VehicleController::class, 'create']);
  Route::post('/kendaraan/tambahkendaraan', [VehicleController::class, 'store']);
  Route::get('/kendaraan/ubah/{vehicle:id}', [VehicleController::class, 'edit']);
  Route::put('/kendaraan/ubahkendaraan/{vehicle:id}', [VehicleController::class, 'update']);


  Route::prefix('stok')->group(function(){
    Route::get('/', [ItemController::class, 'indexAdministrasi']);
    
    //Route untuk riwayat stok
    Route::get('/cari', [ItemController::class, 'cariStok']);
    Route::get('/riwayat', [ItemController::class, 'riwayatAdministrasi']);
    Route::get('/riwayat/cari', [ItemController::class, 'cariRiwayat']);
    Route::get('/riwayat/detail/{pengadaan:no_pengadaan}', [ItemController::class, 'cariRiwayatDetail']);
    Route::get('/riwayat/detail/{pengadaan:no_pengadaan}/cetak-pdf', [ItemController::class, 'cetakPDF']);
    
    //Route untuk pengadaan
    Route::resource('/produk', ItemController::class);
    Route::get('/produk/cari', [ItemController::class, 'produkSearch']);
    Route::get('/pengadaan', [ItemController::class, 'productList'])->name('products.list');
    Route::get('/pengadaan/cart', [CartController::class, 'cartList'])->name('cart.list');
    Route::post('/pengadaan/cart', [CartController::class, 'addToCart'])->name('cart.store');
    Route::post('/pengadaan/update-cart', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('/pengadaan/remove', [CartController::class, 'removeCart'])->name('cart.remove');
    Route::post('/pengadaan/clear', [CartController::class, 'clearAllCart'])->name('cart.clear');

    Route::post('/pengadaan/tambahpengadaan', [ItemController::class, 'simpanDataPengadaan']);

    //Route untuk stok
    Route::get('/opname', [ItemController::class, 'productListOpname']);
    Route::get('/opname/final', [CartController::class, 'cartList']);
    Route::post('/opname/final', [CartController::class, 'addToCart']);
    Route::post('/opname/update-final', [CartController::class, 'updateCart']);
    Route::post('/opname/remove', [CartController::class, 'removeCart']);
    Route::get('/opname/clear', [CartController::class, 'clearAllCart']);
    Route::get('/opname/tambahopname', [ItemController::class, 'simpanDataOpname']);
  });

  //Route untuk data customer
  Route::get('/datacustomer', [CustomerController::class, 'administrasiIndex']);
  Route::get('/datacustomer/cari', [CustomerController::class, 'administrasiSearch']);
  Route::get('/datacustomer/create', [CustomerController::class, 'administrasiCreate']);
  Route::post('/datacustomer/tambahcustomer', [CustomerController::class, 'administrasiStore']);
  Route::get('/datacustomer/ubah/{customer:id}', [CustomerController::class, 'administrasiEdit']);
  Route::put('/datacustomer/ubahcustomer/{customer:id}', [CustomerController::class, 'administrasiUpdate']);
  Route::get('/datacustomer/{customer:id}', [CustomerController::class, 'administrasiShow']);
  Route::post('/datacustomer/ubahstatus/{customer:id}', [CustomerController::class, 'administrasiEditStatusCustomer']);

  //Route untuk profil administrasi
  Route::get('/profil', [HomeController::class, 'lihatProfil']);
  Route::get('/profil/ubahpassword', [HomeController::class, 'lihatPassword']);
  Route::post('/profil/check/{user:id_users}', [AuthController::class, 'check']);
  Route::get('/profil/ubahpasswordbaru/{user:id_users}', [AuthController::class, 'passwordBaru']);
  Route::post('/profil/gantipassword/{user:id_users}', [AuthController::class, 'gantiPassword']);
});


Route::prefix('customer')->middleware('customer')->group(function() {
  Route::get('/', [HomeController::class, 'indexCustomer']);

  //Route untuk Customer produk
  Route::get('/produk', [ItemController::class, 'customerIndex']);
  Route::get('/produk/cari', [ItemController::class, 'itemSearch']);
  //Route untuk cart
  Route::get('/cart', [CartController::class, 'cartList']);
  Route::get('/cart/tambahorder', [OrderController::class, 'simpanDataOrderCustomer']);
  //Route untuk event
  Route::get('/event', [EventController::class, 'customerIndex']);
  Route::get('/event/cari', [EventController::class, 'customerSearch']);
});

require __DIR__.'/auth.php';

Route::get('/{path}', function () {
  return view('reactView');
})->where('path', '.*');