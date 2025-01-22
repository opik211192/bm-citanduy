<?php

use App\Http\Controllers\BenchmarkController;
use App\Http\Controllers\DependantDropdownController;
use Illuminate\Support\Facades\Auth;
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
    return view('home');
})->name('welcome');

Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/benchmark/print/{id}', [BenchmarkController::class, 'print'])->name('benchmark.print');
Route::get('/benchmark/download/{id}', [BenchmarkController::class, 'download'])->name('benchmark.download');


Route::prefix('benchmark')->middleware('auth')->group(function () {
    Route::get('/data', [BenchmarkController::class, 'index'])->name('benchmark.index');
    Route::get('/create', [BenchmarkController::class, 'create'])->name('benchmark.create');
    Route::post('/create', [BenchmarkController::class, 'store'])->name('benchmark.store');
    Route::get('/show/{benchmark}', [BenchmarkController::class, 'show'])->name('benchmark.show');
    Route::get('/edit/{benchmark}', [BenchmarkController::class, 'edit'])->name('benchmark.edit');
    Route::put('/edit/{benchmark}', [BenchmarkController::class, 'update'])->name('benchmark.update');
    Route::delete('/delete/{benchmark}', [BenchmarkController::class, 'destroy'])->name('benchmark.destroy');

});


Route::get('/provinces', [DependantDropdownController::class, 'provinces'])->name('provinces');
Route::get('/cities', [DependantDropdownController::class, 'cities'])->name('cities');
Route::get('/districts', [DependantDropdownController::class, 'districts'])->name('districts');
Route::get('/villages', [DependantDropdownController::class, 'villages'])->name('villages');

//log-viewers
Route::get('log-viewers', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);