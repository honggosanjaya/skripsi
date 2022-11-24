<?php

use App\Http\Controllers\CustomerTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CashAccountController;
use App\Http\Controllers\CategoryItemController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\LaporanPenagihanController;
use App\Http\Controllers\RencanaTripController;
use App\Http\Controllers\TargetController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    return redirect('/login');
});
Route::get('/test',[Controller::class, 'test']);

Route::get('/confirmemail/{id}',[RegisteredUserController::class, 'confirmEmail']);

if(config('app.enabled_email_confirmation')==true){
  Auth::routes(['verify'=>true]);
}

// ============ OWNER ==============
Route::prefix('owner')->middleware('owner')->group(function() {
  Route::get('/report/penjualan', [ReportController::class,'penjualan']);
  Route::get('/report/kinerja', [ReportController::class,'kinerja']);
  Route::get('/report/koordinattrip', [ReportController::class,'koordinattrip']);
  Route::get('/report/koordinattrip/{trip:id}', [ReportController::class,'cekKoordinatTrip']);

  Route::get('/', [ReportController::class, 'index']);
  // Route::post('/', [ReportController::class, 'index']);
  Route::get('/profil', [HomeController::class, 'lihatProfil']);
  Route::get('/datasupervisor', [StaffController::class, 'datasupervisor']);
  Route::get('/datasupervisor/create', [StaffController::class, 'createSupervisor']);
  Route::get('/datasupervisor/{staff:id}', [StaffController::class, 'detailDatasupervisor']);
  Route::post('/datasupervisor', [StaffController::class, 'store']);
  Route::post('/datasupervisor', [StaffController::class, 'store']);
  Route::get('/datasupervisor/edit/{staff:id}', [StaffController::class, 'editSupervisor']);
  Route::put('/datasupervisor/{id}/edit', [StaffController::class, 'update']);
  Route::post('/datasupervisor/ubahstatus/{staf:id}', [StaffController::class, 'editStatusStaf']);

  Route::get('/profil', [HomeController::class, 'lihatProfil']);
  Route::get('/profil/ubahpassword', [HomeController::class, 'lihatPassword']);
  Route::post('/profil/check/{user:id_users}', [AuthController::class, 'check']);
  Route::get('/profil/ubahpasswordbaru/{user:id}', [AuthController::class, 'passwordBaru']);
  Route::post('/profil/gantipassword/{user:id}', [AuthController::class, 'gantiPassword']);

  Route::get('/panduan', [ReportController::class, 'panduanPelaporan']);
});


// ============ SUPERVISOR ==============
Route::prefix('supervisor')->middleware('supervisor')->group(function() {
  Route::get('/', [ReportController::class, 'index']);
  Route::get('/profil', [HomeController::class, 'lihatProfil']);
  Route::get('/profil/ubahpassword', [HomeController::class, 'lihatPassword']);
  Route::post('/profil/check/{user:id_users}', [AuthController::class, 'check']);
  Route::get('/profil/ubahpasswordbaru/{user:id}', [AuthController::class, 'passwordBaru']);
  Route::post('/profil/gantipassword/{user:id}', [AuthController::class, 'gantiPassword']);

  Route::get('/event', [EventController::class, 'index']);
  Route::get('/event/tambah', [EventController::class, 'create']);
  Route::post('/event/tambahevent', [EventController::class, 'store']);
  Route::get('/event/ubah/{event:id}', [EventController::class, 'edit']);
  Route::put('/event/ubahevent/{event:id}', [EventController::class, 'update']);
  Route::get('/event/hapusevent/{event:id}', [EventController::class, 'delete']);

  Route::get('/wilayah', [DistrictController::class, 'index']);
  Route::get('/wilayah/tambah', [DistrictController::class, 'create']);
  Route::get('/wilayah/lihat', [DistrictController::class, 'lihat']);
  Route::post('/wilayah/tambahwilayah', [DistrictController::class, 'store']);
  Route::get('/wilayah/ubah/{district:id}', [DistrictController::class, 'edit']);
  Route::put('/wilayah/ubahwilayah/{district:id}', [DistrictController::class, 'update']);

  Route::get('/datacustomer', [CustomerController::class, 'dataCustomer']);
  Route::get('/datacustomer/pengajuan', [CustomerController::class, 'dataPengajuanLimit']);
  Route::get('/datacustomer/pengajuan/{customer:id}', [CustomerController::class, 'detailDataPengajuanLimit']);
  Route::post('/datacustomer/pengajuan/setuju/{customer:id}', [CustomerController::class, 'setujuPengajuanLimit']);
  Route::post('/datacustomer/pengajuan/tolak/{customer:id}', [CustomerController::class, 'tolakPengajuanLimit']);
  Route::get('/datacustomer/{customer:id}', [CustomerController::class, 'detailCustomerSPV']);

  Route::get('/jenis', [CustomerTypeController::class, 'index']);
  Route::get('/jenis/tambah', [CustomerTypeController::class, 'create']);
  Route::post('/jenis/tambahjenis', [CustomerTypeController::class, 'store']);
  Route::get('/jenis/ubah/{customertype:id}', [CustomerTypeController::class, 'edit']);
  Route::put('/jenis/ubahjenis/{customertype:id}', [CustomerTypeController::class, 'update']);

  Route::resource('/datastaf', StaffController::class)->except(['show', 'destroy']);
  Route::post('/datastaf/ubahstatus/{staf:id}', [StaffController::class, 'editStatusStaf']);
  Route::get('/datastaf/{staff:id}', [StaffController::class, 'detailStaff']);

  Route::get('/report/penjualan', [ReportController::class,'penjualan']);
  Route::get('/report/kinerja', [ReportController::class,'kinerja']);
  Route::get('/report/koordinattrip', [ReportController::class,'koordinattrip']);
  Route::get('/report/koordinattrip/{trip:id}', [ReportController::class,'cekKoordinatTrip']);

  Route::get('/cashaccount', [CashAccountController::class, 'cashAccountIndex']);
  Route::get('/cashaccount/tambah', [CashAccountController::class, 'cashAccountCreate']);
  Route::post('/cashaccount/tambah', [CashAccountController::class, 'cashAccountStore']);
  Route::get('/cashaccount/ubah/{cashaccount:id}', [CashAccountController::class, 'cashAccountEdit']);
  Route::put('/cashaccount/ubah/{cashaccount:id}', [CashAccountController::class, 'cashAccountUpdate']);

  Route::get('/category', [CategoryItemController::class, 'categoryIndex']);
  Route::get('/category/tambah', [CategoryItemController::class, 'categoryCreate']);
  Route::post('/category/tambah', [CategoryItemController::class, 'categoryStore']);
  Route::get('/category/ubah/{category:id}', [CategoryItemController::class, 'categoryEdit']);
  Route::put('/category/ubah/{category:id}', [CategoryItemController::class, 'categoryUpdate']);

  Route::get('/stokopname', [OrderController::class, 'dataPengajuanOpname']);
  Route::get('/stokopname/{order:id}', [OrderController::class, 'detailPengajuanOpname']);
  Route::post('/stokopname/setuju/{order:id}', [OrderController::class, 'konfirmasiPengajuanOpname']);
  Route::post('/stokopname/tolak/{order:id}', [OrderController::class, 'tolakPengajuanOpname']);

  Route::get('/target', [TargetController::class, 'index']);
  Route::get('/target/tambah', [TargetController::class, 'tambahTarget']);
  Route::post('/target/tambah', [TargetController::class, 'storeTarget']);

  Route::get('/perubahankas', [KasController::class, 'perubahanKasSpv']);
  Route::post('/perubahankas/setuju/{kas:id}', [KasController::class, 'setujuPerubahanKasSpv']);
  Route::post('/perubahankas/tolak/{kas:id}', [KasController::class, 'tolakPerubahanKasSpv']);

  Route::get('/panduan', [ReportController::class, 'panduanPelaporan']);
});


// =============== ADMINISTRASI ====================
Route::prefix('administrasi')->middleware('administrasi')->group(function() {
  Route::get('/', [HomeController::class, 'indexAdministrasi']);
  Route::get('/pesanan', [OrderController::class, 'index']);
  // Route::get('/pesanan/cari', [OrderController::class, 'search']);
  // Route::get('/pesanan/filter', [OrderController::class, 'filter']);
  Route::get('/pesanan/detail/{order:id}', [OrderController::class, 'viewDetail']);
  Route::get('/pesanan/detail/{order:id}/kapasitas', [OrderController::class, 'viewKapasitas']);
  Route::get('/pesanan/detail/{order:id}/cetak-memo', [OrderController::class, 'cetakMemo']);
  Route::get('/pesanan/detail/{order:id}/cetak-invoice', [OrderController::class, 'cetakInvoice']);
  Route::get('/pesanan/detail/{order:id}/cetak-sj', [OrderController::class, 'cetakSJ']);
  Route::post('/pesanan/setuju/{order:id}', [OrderController::class, 'setujuPesanan']);
  Route::post('/pesanan/tolak/{order:id}', [OrderController::class, 'tolakPesanan']);
  Route::get('/pesanan/detail/{order:id}/pengiriman', [OrderController::class, 'viewPengiriman']);
  Route::get('/pesanan/detail/{order:id}/pembayaran', [OrderController::class, 'inputPembayaran']);
  Route::post('/pesanan/detail/{order:id}/dikirimkan', [OrderController::class, 'konfirmasiPengiriman']);
  Route::post('/pesanan/detail/{order:id}/dibayar', [OrderController::class, 'konfirmasiPembayaran']);

  //Route untuk retur
  Route::get('/retur', [ReturController::class, 'index']);
  Route::get('/retur/{retur:no_retur}', [ReturController::class, 'viewRetur']);
  Route::post('/retur/konfirmasi', [ReturController::class, 'confirmRetur']);
  Route::get('/retur/cetak-retur/{retur:no_retur}', [ReturController::class, 'cetakRetur']);

  //Route untuk kendaraan
  Route::get('/kendaraan', [VehicleController::class, 'index']);
  Route::get('/kendaraan/cari', [VehicleController::class, 'search']);
  Route::get('/kendaraan/tambah', [VehicleController::class, 'create']);
  Route::post('/kendaraan/tambahkendaraan', [VehicleController::class, 'store']);
  Route::get('/kendaraan/ubah/{vehicle:id}', [VehicleController::class, 'edit']);
  Route::put('/kendaraan/ubahkendaraan/{vehicle:id}', [VehicleController::class, 'update']);
  Route::get('/kendaraan/{vehicle:id}', [VehicleController::class, 'detail']);
  Route::get('/kendaraan/{vehicle:id}/cetak-memo', [OrderController::class, 'cetakKeseluruhanMemo']);

  Route::prefix('stok')->group(function(){
    Route::get('/', [ItemController::class, 'indexAdministrasi']);
    
    //Route untuk riwayat stok
    Route::get('/cari', [ItemController::class, 'cariStok']);
    Route::get('/riwayat', [ItemController::class, 'riwayatAdministrasi']);
    // Route::get('/riwayat/cari', [ItemController::class, 'cariRiwayat']);
    Route::get('/riwayat/detail/{pengadaan:no_pengadaan}', [ItemController::class, 'cariRiwayatDetail']);
    Route::get('/riwayat/detail/{pengadaan:no_pengadaan}/cetak-pdf', [ItemController::class, 'cetakPDF']);
    
    //Route untuk pengadaan
    Route::resource('/produk', ItemController::class);
    Route::post('/produk/ubahstatus/{item:id}', [ItemController::class, 'administrasiEditStatusItem']);

    Route::get('/pengadaan', [ItemController::class, 'productList'])->name('products.list');
    Route::get('/pengadaan/cart', [CartController::class, 'cartList'])->name('cart.list');
    Route::post('/pengadaan/cart', [CartController::class, 'addToCart'])->name('cart.store');
    // Route::post('/pengadaan/update-cart', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('/pengadaan/remove', [CartController::class, 'removeCart'])->name('cart.remove');
    // Route::post('/pengadaan/clear', [CartController::class, 'clearAllCart'])->name('cart.clear');
    Route::get('/pengadaan/clear', [CartController::class, 'clearAllCart']);
    Route::post('/pengadaan/tambahpengadaan', [ItemController::class, 'simpanDataPengadaan']);

    //Route untuk stok
    Route::get('/opname', [ItemController::class, 'productListOpname']);
    Route::get('/opname/riwayat', [ItemController::class, 'riwayatOpname']);
    Route::get('/opname/riwayat/detail/{order:id}', [ItemController::class, 'detailRiwayatOpname']);
    Route::get('/opname/final', [CartController::class, 'cartList']);
    Route::post('/opname/final', [CartController::class, 'addToCart']);
    // Route::post('/opname/update-final', [CartController::class, 'updateCart']);
    // Route::post('/opname/remove', [CartController::class, 'removeCart']);
    Route::get('/opname/clear', [CartController::class, 'clearAllCart']);
    Route::get('/opname/tambahopname', [ItemController::class, 'simpanDataOpname']);

    // Rout untuk stok retur
    Route::get('/stokretur', [ItemController::class, 'productListStokRetur']);
    Route::get('/stokretur/cart', [CartController::class, 'cartList']);
    Route::post('/stokretur/cart', [CartController::class, 'addToCart']);
    Route::get('/stokretur/clear', [CartController::class, 'clearAllCart']);
    Route::post('/stokretur/tambahstokretur', [ItemController::class, 'simpanDataStokRetur']);
  });

  //Route untuk data customer
  Route::get('/datacustomer', [CustomerController::class, 'administrasiIndex']);
  Route::get('/datacustomer/create', [CustomerController::class, 'administrasiCreate']);
  Route::post('/datacustomer/tambahcustomer', [CustomerController::class, 'administrasiStore']);
  Route::get('/datacustomer/ubah/{customer:id}', [CustomerController::class, 'administrasiEdit']);
  Route::put('/datacustomer/ubahcustomer/{customer:id}', [CustomerController::class, 'administrasiUpdate']);
  Route::get('/datacustomer/{customer:id}', [CustomerController::class, 'administrasiShow']);
  Route::get('/datacustomer/{customer:id}/generate-qr', [CustomerController::class, 'generateQRCustomer']);
  Route::get('/datacustomer/{customer:id}/cetak-qr', [CustomerController::class, 'cetakQRCustomer']);

  Route::get('/profil', [HomeController::class, 'lihatProfil']);
  Route::get('/profil/ubahpassword', [HomeController::class, 'lihatPassword']);
  Route::post('/profil/check/{user:id_users}', [AuthController::class, 'check']);
  Route::get('/profil/ubahpasswordbaru/{user:id}', [AuthController::class, 'passwordBaru']);
  Route::post('/profil/gantipassword/{user:id}', [AuthController::class, 'gantiPassword']);

  Route::get('/reimbursement', [CashAccountController::class, 'adminReimbursementIndex']);
  Route::get('/reimbursement/pengajuan', [CashAccountController::class, 'adminReimbursementPengajuan']);
  Route::get('/reimbursement/pembayaran', [CashAccountController::class, 'adminReimbursementPembayaran']);
  Route::get('/reimbursement/pengajuan/{reimbursement:id}', [CashAccountController::class, 'adminReimbursementPengajuanDetail']);
  Route::post('/reimbursement/pengajuan/setuju/{reimbursement:id}', [CashAccountController::class, 'setujuReimbursement']);
  Route::post('/reimbursement/pengajuan/tolak/{reimbursement:id}', [CashAccountController::class, 'tolakReimbursement']);
  Route::post('/reimbursement/pengajuan/dibayar/{reimbursement:id}', [CashAccountController::class, 'bayarReimbursement']);

  Route::get('/lp3', [LaporanPenagihanController::class, 'index']);
  Route::post('/lp3/penagihan', [LaporanPenagihanController::class, 'storeLp3']);
  Route::post('/lp3/cetak-lp3', [LaporanPenagihanController::class, 'cetakLp3']);

  Route::get('/rencanakunjungan', [RencanaTripController::class, 'index']);
  Route::post('/rencanakunjungan/create', [RencanaTripController::class, 'storeRencana']);
  Route::post('/rencanakunjungan/cetak-rak', [RencanaTripController::class, 'cetakRAK']);
  
  Route::get('/kas', [KasController::class, 'index']);
  Route::get('/kas/create/{cashaccount:id}', [KasController::class, 'createKas']);
  Route::post('/kas/store', [KasController::class, 'storeKas']);
  Route::get('/kas/{cashaccount:id}', [KasController::class, 'bukuKas']);
  Route::post('/kas/pengajuanpenghapusan/{kas:id}', [KasController::class, 'pengajuanPenghapusanKas']);
  Route::get('/kas/print/{cashaccount:id}', [KasController::class, 'cetakKas']);
  Route::post('/kas/print/{cashaccount:id}/cetak-kas', [KasController::class, 'cetakKasPDF']);
  Route::post('/changeorderitem/{order_item:id}', [OrderController::class, 'changeOrderItem']);

  Route::get('/kanvas', [ItemController::class, 'indexKanvas']);
  Route::get('/kanvas/create', [ItemController::class, 'createKanvas']);
  Route::post('/kanvas/store', [ItemController::class, 'storeKanvas']);
  Route::get('/kanvas/history', [ItemController::class, 'historyKanvas']);
  Route::post('/kanvas/dikembalikan/{ids}', [ItemController::class, 'pengembalianKanvas']);

  // trip sales
  Route::get('/tripsales', [ReportController::class, 'tripSalesAdmin']);
  Route::get('/tripsales/{trip:id}', [ReportController::class,'cekKoordinatTrip']);
});


// =============== CUSTOMER ====================
Route::prefix('customer')->middleware('customer')->group(function() {
  Route::get('/', [HomeController::class, 'indexCustomer']);
  Route::get('/produk', [ItemController::class, 'customerIndex']);
  Route::get('/produk/cari', [ItemController::class, 'itemSearch']);
  Route::get('/produk/cart', [CartController::class, 'cartList']);
  Route::get('/produk/cart/tambahorder', [OrderController::class, 'simpanDataOrderCustomer']);
  Route::get('/produk/cart/clear', [CartController::class, 'clearAllCart']);
  Route::get('/event', [EventController::class, 'customerIndex']);
  Route::get('/event/cari', [EventController::class, 'customerSearch']);
  Route::get('/profil', [HomeController::class, 'lihatProfil']);
  Route::get('/profil/detailprofil', [HomeController::class, 'lihatDetailProfil']);
  Route::get('/profil/pesanan/{customer:id}', [HomeController::class, 'lihatPesanan']);
  Route::get('/profil/ubahpassword', [HomeController::class, 'lihatPassword']);
  Route::post('/profil/check/{user:id}', [AuthController::class, 'check']);
  Route::get('/profil/ubahpasswordbaru/{user:id}', [AuthController::class, 'passwordBaru']);
  Route::post('/profil/gantipassword/{user:id}', [AuthController::class, 'gantiPassword']);
  Route::post('/historyorder/hapus/{order:id}', [OrderController::class, 'hapusKodeCustomer']);
});

// Route::prefix('salesman')->middleware('salesman')->group(function() {
//   Route::get('/', [HomeController::class, 'indexSalesman']);
// });
// Route::prefix('shipper')->middleware('shipper')->group(function() {
//   Route::get('/', [HomeController::class, 'indexShipper']);
// });

require __DIR__.'/auth.php';

Route::get('/{path}', function () {
  return view('reactView');
})->where('path', '.*');