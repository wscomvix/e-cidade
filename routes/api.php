<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\SoapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebService\CustomerServices;
use App\Http\Controllers\WebService\PriceServices;
use App\Http\Controllers\WebService\StockServices;
use App\Http\Controllers\WebService\VoucherServices;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
