<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/orders', [OrdersController::class, 'index']);
Route::post('/orders', [OrdersController::class, 'store']);
Route::put('/orders/{id}', [OrdersController::class, 'update']);
Route::delete('/orders/{id}', [OrdersController::class, 'destroy']);
