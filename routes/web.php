<?php

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

Route::prefix('dashboard')->group(function() {
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
    Route::get('/', function() {
      return view('supervisor/pengguna');
    });
    Route::get('/tambah', function() {
      return view('supervisor/addPengguna');
    });
    Route::get('/ubah', function() {
      return view('supervisor/editPengguna');
    });
  });

});

Route::get('/home', function () {
    return view('home');
});

Route::get('/testing', function () {
    return view('testing');
});

Route::get('/check', function () {
    return view('check');
})->middleware('supervisor');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth','notsales'])->name('dashboard');

require __DIR__.'/auth.php';

