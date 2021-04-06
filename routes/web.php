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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes();


Route::post('/store', [App\Http\Controllers\HomeController::class, 'store'])->name('store')->middleware('auth');
Route::post('/update/{id}', [App\Http\Controllers\HomeController::class, 'update'])->name('update')->middleware('auth');
