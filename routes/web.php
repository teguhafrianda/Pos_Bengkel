<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CustomerController,
    DashboardController,
    InstallController,
    KendaraanController,
    PengeluaranController,
    ServiceController,
    SparepartController,
    TeknisiController,
    AuthController
};

/*
|--------------------------------------------------------------------------
| Installation Routes
|--------------------------------------------------------------------------
*/
Route::prefix('install')->group(function () {
    Route::get('/', [InstallController::class, 'step1']);
    Route::post('/step1', [InstallController::class, 'saveStep1']);

    Route::get('/database', [InstallController::class, 'step2']);
    Route::post('/database', [InstallController::class, 'saveStep2']);

    Route::get('/admin', [InstallController::class, 'step3']);
    Route::post('/admin', [InstallController::class, 'saveStep3']);

    Route::get('/finish', [InstallController::class, 'finish']);
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (Installed + Auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['installed', 'auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'dashboard'])->name('home');
    Route::permanentRedirect('/dashboard', '/');

    /*
    |--------------------------------------------------------------------------
    | Pelanggan & Kendaraan
    |--------------------------------------------------------------------------
    */
    Route::resource('pelanggan', CustomerController::class)->names('customers');

    Route::prefix('kendaraans')->name('kendaraans.')->group(function () {
        Route::get('/check-plat', [KendaraanController::class, 'checkPlat'])->name('checkPlat'); // AJAX validasi plat
        Route::post('/', [KendaraanController::class, 'store'])->name('store');
        Route::put('/{kendaraan}', [KendaraanController::class, 'update'])->name('update');
        Route::delete('/{kendaraan}', [KendaraanController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Teknisi
    |--------------------------------------------------------------------------
    */
    Route::resource('teknisi', TeknisiController::class)
        ->names('teknisis')
        ->except(['create', 'edit', 'show']);

    /*
    |--------------------------------------------------------------------------
    | Service & Transaksi
    |--------------------------------------------------------------------------
    */
    Route::controller(ServiceController::class)->group(function () {
        Route::get('/service', 'index')->name('services.index');
        Route::post('/service/store', 'store')->name('services.store');
        Route::get('/service/{id}', 'show')->name('services.show');
        Route::patch('/services/{service}/update-status', 'updateStatus')->name('services.updateStatus');
        Route::get('/transaksi', 'transaksi')->name('transaksi.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Inventori / Sparepart
    |--------------------------------------------------------------------------
    */
    Route::resource('spareparts', SparepartController::class)->only(['index', 'store', 'destroy']);
    Route::post('/spareparts/update-stok', [SparepartController::class, 'updateStok'])->name('spareparts.updateStok');

    /*
    |--------------------------------------------------------------------------
    | Pengeluaran / Keuangan
    |--------------------------------------------------------------------------
    */
    Route::resource('pengeluaran', PengeluaranController::class);

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | Development / Test Route
    |--------------------------------------------------------------------------
    */
    Route::get('/test-installed', fn() => 'OK MASUK ROUTE');
});