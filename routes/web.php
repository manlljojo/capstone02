<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KalabController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\StafAdminController;
use App\Http\Controllers\StafLabController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Main authenticated group
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 1. Administrator Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Users
        Route::get('/users', [AdminController::class, 'indexUsers'])->name('users.index');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // Rooms
        Route::get('/rooms', [AdminController::class, 'indexRooms'])->name('rooms.index');
        Route::post('/rooms', [AdminController::class, 'storeRoom'])->name('rooms.store');
        Route::put('/rooms/{room}', [AdminController::class, 'updateRoom'])->name('rooms.update');
        Route::delete('/rooms/{room}', [AdminController::class, 'destroyRoom'])->name('rooms.destroy');
    });

    // 2. Kepala Laboratorium Routes
    Route::middleware(['role:kalab'])->prefix('kalab')->name('kalab.')->group(function () {
        Route::get('/drafts', [KalabController::class, 'index'])->name('drafts.index');
        Route::post('/drafts', [KalabController::class, 'store'])->name('drafts.store');
        Route::get('/drafts/{draft}', [KalabController::class, 'show'])->name('drafts.show');
        Route::post('/drafts/{draft}/submit', [KalabController::class, 'submit'])->name('drafts.submit');
        Route::get('/history', [KalabController::class, 'history'])->name('history');

        // Items inside drafts
        Route::post('/drafts/{draft}/items', [KalabController::class, 'storeItem'])->name('drafts.items.store');
        Route::put('/drafts/{draft}/items/{item}', [KalabController::class, 'updateItem'])->name('drafts.items.update');
        Route::delete('/drafts/{draft}/items/{item}', [KalabController::class, 'destroyItem'])->name('drafts.items.destroy');
    });

    // 3. Kaprodi Routes
    Route::middleware(['role:kaprodi'])->prefix('kaprodi')->name('kaprodi.')->group(function () {
        Route::get('/review', [KaprodiController::class, 'index'])->name('review.index');
        Route::get('/review/{draft}', [KaprodiController::class, 'show'])->name('review.show');
        Route::post('/review/{draft}/items/{item}/approve', [KaprodiController::class, 'approveItem'])->name('review.items.approve');
        Route::post('/review/{draft}/items/{item}/reject', [KaprodiController::class, 'rejectItem'])->name('review.items.reject');
        Route::post('/review/{draft}/finalize', [KaprodiController::class, 'finalize'])->name('review.finalize');
    });

    // 4. Staf Administrasi Routes
    Route::middleware(['role:staf_admin'])->prefix('staf-admin')->name('staf_admin.')->group(function () {
        // Receives
        Route::get('/approved', [StafAdminController::class, 'indexApproved'])->name('approved.index');
        Route::get('/approved/{draft}', [StafAdminController::class, 'showApproved'])->name('approved.show');
        Route::post('/approved/{draft}/items/{item}/receipt', [StafAdminController::class, 'storeReceipt'])->name('approved.items.receipt');

        // Assets
        Route::get('/assets', [StafAdminController::class, 'indexAssets'])->name('assets.index');
        Route::put('/assets/{asset}', [StafAdminController::class, 'updateAsset'])->name('assets.update');
    });

    // 5. Staf Laboratorium Routes
    Route::middleware(['role:staf_lab'])->prefix('staf-lab')->name('staf_lab.')->group(function () {
        // BHP Stock
        Route::get('/bhp', [StafLabController::class, 'indexBhp'])->name('bhp.index');
        Route::post('/bhp', [StafLabController::class, 'storeBhp'])->name('bhp.store');
        Route::put('/bhp/{bhp}', [StafLabController::class, 'updateBhp'])->name('bhp.update');
        Route::delete('/bhp/{bhp}', [StafLabController::class, 'destroyBhp'])->name('bhp.destroy');

        // Maintenance
        Route::get('/maintenance', [StafLabController::class, 'indexMaintenance'])->name('maintenance.index');
        Route::get('/maintenance/create', [StafLabController::class, 'createMaintenance'])->name('maintenance.create');
        Route::post('/maintenance', [StafLabController::class, 'storeMaintenance'])->name('maintenance.store');
    });
});
