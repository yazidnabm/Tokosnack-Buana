<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KasMasukController;
use App\Http\Controllers\KasKeluarController;
use App\Http\Controllers\LaporanController;
/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Route::get('/register', [AuthController::class, 'register'])->name('register');
// Route::post('/register', [AuthController::class, 'storeRegister'])->name('register.store');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/api/chart-data', [DashboardController::class, 'getChartData']);

    Route::get('/api/dashboard-summary', [DashboardController::class, 'getSummaryData'])
        ->name('dashboard.summary');

    // Kas Masuk
    Route::get('/kas-masuk', [KasMasukController::class, 'index'])
        ->name('kas-masuk.index');
    Route::post('/kas-masuk', [KasMasukController::class, 'store'])
        ->name('kas-masuk.store');
    Route::put('/kas-masuk/{id}', [KasMasukController::class, 'update'])
        ->name('kas-masuk.update');
    Route::delete('/kas-masuk/{id}', [KasMasukController::class, 'destroy'])
        ->name('kas-masuk.destroy');

    // Kas Keluar
    Route::get('/kas-keluar', [KasKeluarController::class, 'index'])
        ->name('kas-keluar.index');
    Route::post('/kas-keluar', [KasKeluarController::class, 'store'])
        ->name('kas-keluar.store');
    Route::put('/kas-keluar/{id}', [KasKeluarController::class, 'update'])
        ->name('kas-keluar.update');
    Route::delete('/kas-keluar/{id}', [KasKeluarController::class, 'destroy'])
        ->name('kas-keluar.destroy');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');

    // EXPORT PDF
    Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])
        ->name('laporan.export-pdf');

});
