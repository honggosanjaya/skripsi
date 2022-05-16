<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\UbahPasswordController;
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




/**
 * Route untuk halaman dashboard keseluruhan
 */
Route::prefix('dashboard')->middleware(['auth','notsales'])->group(function() {
  Route::get('/', function() {
    return view('layouts/dashboard');
  });
  
  Route::get('/pesanan', [PesananController::class, 'index']);
  
  Route::get('/retur', [ReturController::class, 'index']);
  Route::get('/retur/terimaRetur/{item:id}', [ReturController::class, 'terimaRetur']);
  Route::get('/retur/tolakRetur/{item:id}', [ReturController::class, 'tolakRetur']);

  Route::get('/produk/stok/edit', [StokController::class, 'index2']);
  Route::resource('/produk/stok', StokController::class)->except('destroy');
  Route::resource('/produk', ItemController::class)->except('destroy');
  Route::get('/produk/ubahstatus/{item:id}', [ItemController::class, 'ubahstatus']);
  
  Route::prefix('profil')->group(function() {
    /**
     * Route untuk profil akun
     */
    Route::get('/ubah/{user:id}', [ProfilController::class, 'index']);
    Route::put('ubahprofil/{user:id}', [ProfilController::class, 'update']);


    /**
     * Route untuk ubah password lama ke password baru
     */
    Route::get('/ubahpasswordlama/{user:id}', [UbahPasswordController::class, 'index']);
    Route::post('/check/{user:id}', [UbahPasswordController::class, 'check']);
    Route::get('/ubahpasswordbaru/{user:id}', [UbahPasswordController::class, 'indexPasswordBaru']);
    Route::post('/gantipassword/{user:id}', [UbahPasswordController::class, 'gantiPassword']);
  });


  /**
   * Route untuk halaman pengguna (supervisor)
   */
  Route::prefix('pengguna')->group(function() {
    Route::get('/', [SupervisorController::class, 'index']);
    Route::get('/cari', [SupervisorController::class, 'search']);
    Route::get('/tambah', [SupervisorController::class, 'create']);
    Route::post('/tambahuser', [SupervisorController::class, 'store']);
    Route::get('/ubah/{user:id}', [SupervisorController::class, 'edit']);
    Route::put('/ubahuser/{user:id}', [SupervisorController::class, 'update']);
  });
});


/**
 * Route di bawah masih untuk testing
 */
Route::get('/home', function () {
    return view('home');
})->middleware(['auth','admin']);

Route::get('/testing', function () {
    return view('testing');
});

Route::get('/check', function () {
    return view('check');
})->middleware(['auth','supervisor']);

require __DIR__.'/auth.php';

Route::get('/sales/{path}', function () {
  return view('sales');
})->where('path', '.*');