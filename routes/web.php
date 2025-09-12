<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\AsetPhotoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BenchmarkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DependantDropdownController;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::get('/benchmark/print/{id}', [BenchmarkController::class, 'print'])->name('benchmark.print');
Route::get('/benchmark/download/{id}', [BenchmarkController::class, 'download'])->name('benchmark.download');

Route::get('/aset/print/{id}', [AsetController::class, 'print'])->name('aset.print');


Route::prefix('benchmark')->middleware('auth')->group(function () {
    Route::get('/data', [BenchmarkController::class, 'index'])->name('benchmark.index');
    Route::get('/create', [BenchmarkController::class, 'create'])->name('benchmark.create');
    Route::post('/create', [BenchmarkController::class, 'store'])->name('benchmark.store');
    Route::get('/show/{benchmark}', [BenchmarkController::class, 'show'])->name('benchmark.show');
    Route::get('/edit/{benchmark}', [BenchmarkController::class, 'edit'])->name('benchmark.edit');
    Route::put('/edit/{benchmark}', [BenchmarkController::class, 'update'])->name('benchmark.update');
    Route::delete('/delete/{benchmark}', [BenchmarkController::class, 'destroy'])->name('benchmark.destroy');

});

Route::prefix('aset')->middleware('auth')->group(function () {
    Route::get('/data', [AsetController::class, 'index'])->name('aset.index');
    Route::get('/create', [AsetController::class, 'create'])->name('aset.create');
    Route::post('/create', [AsetController::class, 'store'])->name('aset.store');
    Route::get('/show/{id}', [AsetController::class, 'show'])->name('aset.show');
    Route::get('/edit/{aset}', [AsetController::class, 'edit'])->name('aset.edit');
    Route::put('/edit/{aset}', [AsetController::class, 'update'])->name('aset.update');
    Route::delete('/delete/{aset}', [AsetController::class, 'destroy'])->name('aset.destroy');
    Route::post('/import', [AsetController::class, 'import'])->name('aset.import');

    Route::get('/photos/{kode_integrasi}', [AsetController::class, 'getPhotos'])->name('aset.photos');
    Route::delete('/photos/{kode_integrasi}', [AsetController::class, 'photos_destroy'])->name('photos.destroy');
    Route::post('/photos', [AsetController::class, 'photos_store'])->name('photos.store');


    Route::delete('/foto-aset/{id}', [AsetController::class, 'hapusFoto'])->name('foto-aset.hapus');

});


Route::get('/provinces', [DependantDropdownController::class, 'provinces'])->name('provinces');
Route::get('/cities', [DependantDropdownController::class, 'cities'])->name('cities');
Route::get('/districts', [DependantDropdownController::class, 'districts'])->name('districts');
Route::get('/villages', [DependantDropdownController::class, 'villages'])->name('villages');

//log-viewers
Route::get('log-viewers', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);