<?php

use App\Http\Controllers\HomeController;
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
});
Route::prefix('salesman')->middleware('salesman')->group(function() {
  Route::get('/', [HomeController::class, 'indexSalesman']);
});
Route::prefix('shipper')->middleware('shipper')->group(function() {
  Route::get('/', [HomeController::class, 'indexShipper']);
});
Route::prefix('administrasi')->middleware('administrasi')->group(function() {
  Route::get('/', [HomeController::class, 'indexAdministrasi']);
});
Route::prefix('customer')->middleware('customer')->group(function() {
  Route::get('/', [HomeController::class, 'indexCustomer']);
});

require __DIR__.'/auth.php';

Route::get('/{path}', function () {
  return view('reactView');
})->where('path', '.*');