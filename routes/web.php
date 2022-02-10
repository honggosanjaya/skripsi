<?php

use App\Http\Controllers\SupervisorController;
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

Route::prefix('dashboard')->middleware(['auth','notsales'])->group(function() {
  Route::get('/', function() {
    return view('layouts/dashboard');
  });
  
  Route::get('/pesanan', function() {
    return view('admin/pesanan');
  });
  
  Route::get('/retur', function() {
    return view('admin/retur');
  });

  Route::prefix('produk')->group(function() {
    Route::get('/', function() {
      return view('admin/produk');
    });

    Route::get('/tambah', function() {
      return view('admin/addProduk');
    });

    Route::get('/ubah', function() {
      return view('admin/editProduk');
    });
  });
  
  Route::get('/profil/ubah', function() {
    return view('adminSupervisor/editProfil');
  });

  Route::get('/profil/ubahpassword', function() {
    return view('adminSupervisor/editPassword');
  });

  Route::prefix('profil')->group(function() {
    Route::get('/ubah', function() {
      return view('adminSupervisor/editProfil');
    });

    Route::get('/ubahpassword', function() {
      return view('adminSupervisor/editPassword');
    });
  });

  Route::prefix('pengguna')->group(function() {
    Route::get('/', [SupervisorController::class, 'index']);

    Route::get('/cari', [SupervisorController::class, 'search']);

    Route::get('/tambah', [SupervisorController::class, 'create']);

    Route::post('/tambahuser', [SupervisorController::class, 'store']);

    Route::get('/ubah/{user:id}', [SupervisorController::class, 'edit']);

    Route::put('/ubahuser/{user:id}', [SupervisorController::class, 'update']);

    Route::get('/hapus/{user:id}', [SupervisorController::class, 'destroy']);
  });

});

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

