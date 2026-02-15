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
    TeknisiController
};
use App\Http\Controllers\AuthController;

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
Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); // bisa diakses tanpa login
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (Middleware: Installed + Auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['installed', 'auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'dashboard'])->name('home');
    Route::permanentRedirect('/dashboard', '/');

    // Master Data
    Route::resource('pelanggan', CustomerController::class)->names('customers')->only(['index', 'store']);
    Route::resource('kendaraan', KendaraanController::class)->names('kendaraans');

    // SDM
    Route::resource('teknisi', TeknisiController::class)->names('teknisis')->except(['create', 'edit', 'show']);

    // Service & Transaksi
    Route::controller(ServiceController::class)->group(function () {
        Route::get('/service', 'index')->name('services.index');
        Route::post('/service/store', 'store')->name('services.store');
        Route::get('/service/{id}', 'show')->name('services.show');
        Route::patch('/services/{service}/update-status', 'updateStatus')->name('services.updateStatus');
        
        Route::get('/transaksi', 'transaksi')->name('transaksi.index');
    });

    // Inventori
    Route::resource('spareparts', SparepartController::class)->only(['index', 'store', 'destroy']);
    Route::post('/spareparts/update-stok', [SparepartController::class, 'updateStok'])->name('spareparts.updateStok');

    // Keuangan
    Route::resource('pengeluaran', PengeluaranController::class);

    // Profile
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Development/Test
    Route::get('/test-installed', fn() => 'OK MASUK ROUTE');
});
