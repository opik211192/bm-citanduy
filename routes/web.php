<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AirBakuController;
use App\Http\Controllers\AsetPhotoController;
use App\Http\Controllers\BenchmarkController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DependantDropdownController;
use App\Http\Controllers\GeojsonController;
use App\Http\Controllers\RoleandPermissionController;
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
Route::get('/airbaku/print/{id}', [AirBakuController::class, 'print'])->name('airbaku.print');



Route::prefix('benchmark')->middleware(['auth', 'role:Admin|Benchmark Manager'])->group(function () {
    Route::get('/data', [BenchmarkController::class, 'index'])->name('benchmark.index');
    Route::get('/create', [BenchmarkController::class, 'create'])->name('benchmark.create');
    Route::post('/create', [BenchmarkController::class, 'store'])->name('benchmark.store');
    Route::get('/show/{benchmark}', [BenchmarkController::class, 'show'])->name('benchmark.show');
    Route::get('/edit/{benchmark}', [BenchmarkController::class, 'edit'])->name('benchmark.edit');
    Route::put('/edit/{benchmark}', [BenchmarkController::class, 'update'])->name('benchmark.update');
    Route::delete('/delete/{benchmark}', [BenchmarkController::class, 'destroy'])->name('benchmark.destroy');

});

// Route untuk aset (Infrastruktur)
Route::prefix('infrastruktur')->middleware(['auth'])->group(function () {
    // Hanya yang punya permission 'view infrastruktur' bisa akses index & show
    Route::get('/data', [AsetController::class, 'index'])->middleware('permission:view infrastruktur')->name('aset.index');
    Route::get('/show/{id}', [AsetController::class, 'show'])->middleware('permission:view infrastruktur')->name('aset.show');

    // Hanya yang punya permission 'import infrastruktur' bisa akses create & store
    Route::get('/create', [AsetController::class, 'create'])->middleware('permission:import infrastruktur')->name('aset.create');

    Route::post('/create', [AsetController::class, 'store'])->middleware('permission:import infrastruktur')->name('aset.store');

    // Edit & update (permission disesuaikan)
    Route::get('/edit/{aset}', [AsetController::class, 'edit'])->middleware('permission:upload foto infrastruktur') ->name('aset.edit');

    Route::put('/edit/{aset}', [AsetController::class, 'update'])->middleware('permission:upload foto infrastruktur')->name('aset.update');

    Route::delete('/delete/{aset}', [AsetController::class, 'destroy']) ->middleware('permission:delete infrastruktur') ->name('aset.destroy');

    Route::post('/import', [AsetController::class, 'import'])->middleware('permission:import infrastruktur')->name('aset.import');

    Route::get('/photos/{kode_integrasi}', [AsetController::class, 'getPhotos'])->middleware('permission:view infrastruktur')->name('aset.photos');

    Route::delete('/photos/{kode_integrasi}', [AsetController::class, 'photos_destroy'])->middleware('permission:upload foto infrastruktur')->name('photos.destroy');

    Route::post('/photos', [AsetController::class, 'photos_store'])->middleware('permission:upload foto infrastruktur')->name('photos.store');

    Route::delete('/foto-aset/{id}', [AsetController::class, 'hapusFoto'])->middleware('permission:upload foto infrastruktur') ->name('foto-aset.hapus');
});


//Route untuk air baku
Route::prefix('airbaku')->middleware(['auth', 'role:Admin|Air Baku Manager'])->group(function () {
    Route::get('/', [AirBakuController::class, 'index'])->name('airbaku.index');
    Route::post('/import', [AirBakuController::class, 'import'])->name('airbaku.import');

    // Photos
    Route::get('/{kode_integrasi}/photos', [AirBakuController::class, 'getPhotos'])->name('airbaku.photos');
    Route::post('/photos', [AirBakuController::class, 'photos_store'])->name('airbaku.photos.store');
    Route::delete('/photos/{id}', [AirBakuController::class, 'photos_destroy'])->name('airbaku.photos.destroy');

    // Print
});

// Route untuk manajemen user (hanya Admin)
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('users', UserController::class);
});

Route::prefix('roles')->middleware(['auth', 'role:Admin'])->group(function(){
    //roles
    Route::get('/', [RoleandPermissionController::class, 'index'])->name('roles.index');
    Route::post('/', [RoleandPermissionController::class, 'store'])->name('roles.store');
    Route::put('/{id}', [RoleandPermissionController::class, 'update'])->name('roles.update');
    Route::delete('/{id}', [RoleandPermissionController::class, 'destroy'])->name('roles.destroy');

    //permissions
    Route::post('/permissions', [RoleandPermissionController::class, 'storePermission'])->name('permissions.store');
    Route::put('/permissions/{id}', [RoleandPermissionController::class, 'updatePermission'])->name('permissions.update');
    Route::delete('/permissions/{id}', [RoleandPermissionController::class, 'destroyPermission'])->name('permissions.destroy');
     
    //assign permission
    Route::post('/assign-permission', [RoleandPermissionController::class, 'assignPermission'])->name('roles.assign-permission');
    Route::delete('/{id}/revoke-all', [RoleandPermissionController::class, 'revokeAll'])->name('roles.revoke-all');

    //get roles
    Route::get('/get-roles', [RoleandPermissionController::class, 'getRoles'])->name('roles.get-roles');
    //form check
    Route::get('/permissions/form-check', [RoleandPermissionController::class, 'formCheck'])->name('roles.form-check');

});

Route::prefix('das')->middleware(['auth', 'role:Admin'])->group(function () {

    Route::get('/batas-das', [GeojsonController::class, 'index'])->name('batas-das.index');
    Route::post('/batas-das/create', [GeojsonController::class, 'store'])->name('batas-das.store');
    Route::put('/batas-das/edit/{id}', [GeojsonController::class, 'update'])->name('batas-das.update');
    Route::delete('/batas-das/delete/{id}', [GeojsonController::class, 'destroy'])->name('batas-das.destroy');

    // Route::post('/batas-das/upload', [GeojsonController::class, 'uploadDas'])->name('batas-das.upload');

    // Route::get('/sungai', [GeojsonController::class, 'sungaiIndex'])->name('sungai.index');
    // Route::post('/sungai/upload', [GeojsonController::class, 'uploadSungai'])->name('sungai.upload');
});

Route::get('/provinces', [DependantDropdownController::class, 'provinces'])->name('provinces');
Route::get('/cities', [DependantDropdownController::class, 'cities'])->name('cities');
Route::get('/districts', [DependantDropdownController::class, 'districts'])->name('districts');
Route::get('/villages', [DependantDropdownController::class, 'villages'])->name('villages');

//log-viewers
Route::get('log-viewers', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])
        ->middleware(['auth, role:Admin'])
        ->name('log-viewers');