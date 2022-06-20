<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataProviderController;

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
Route::get('/', [DataProviderController::class, 'index'])->name('data-provider.index');
Route::get('/{id}', [DataProviderController::class, 'show'])->name('data-provider.show');
Route::post('/', [DataProviderController::class, 'store'])->name('data-provider.store');
Route::post('/update', [DataProviderController::class, 'update'])->name('data-provider.update');
Route::get('/delete/{id}', [DataProviderController::class, 'destroy'])->name('data-provider.destroy');

