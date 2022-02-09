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

Route::get('/home', function () {
    return view('home');
})->middleware(['auth','admin']);

Route::get('/testing', function () {
    return view('testing');
});

Route::get('/check', function () {
    return view('check');
})->middleware(['auth','supervisor']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth','notsales'])->name('dashboard');

require __DIR__.'/auth.php';
