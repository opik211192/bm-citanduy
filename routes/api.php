<?php

use App\Http\Controllers\BenchmarkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/data/bm', [BenchmarkController::class, 'api_benchmark'])->name('api.bm');
Route::get('/data/bm/{id}', [BenchmarkController::class, 'api_benchmark_detail'])->name('api.bm.detail');
