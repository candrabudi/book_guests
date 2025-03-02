<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\HistoryGuestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteSettingController;
use App\Http\Controllers\NotulensiController;
use App\Http\Controllers\VisitorController;

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login/process', [AuthController::class, 'loginProcess'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/guest-comparison', [DashboardController::class, 'getGuestComparison'])->name('dashboard.guest-comparison');

    Route::prefix('guests')->name('guests.')->group(function () {
        Route::get('/', [GuestController::class, 'index'])->name('index');
        Route::get('/pending', [GuestController::class, 'getPendingGuests'])->name('pending');
        Route::get('/accepted', [GuestController::class, 'getAcceptedGuests'])->name('accepted');
        Route::get('/disposition', [GuestController::class, 'getDispositionGuests'])->name('disposition');
        Route::get('/completed', [GuestController::class, 'getCompletedGuests'])->name('completed');
        Route::get('/create', [GuestController::class, 'create'])->name('create');
        Route::get('/detail/{a}', [GuestController::class, 'detail'])->name('detail');
        Route::post('/update/{a}', [GuestController::class, 'update'])->name('update');
        Route::post('/store', [GuestController::class, 'store'])->name('store');
        Route::post('/notulensi/store/{a}', [GuestController::class, 'guestNotulensiStore'])->name('guestNotulensiStore');
    });

    Route::get('/history/guests', [HistoryGuestController::class, 'index'])->name('history_guests.index');
    Route::get('/history/guests/list', [HistoryGuestController::class, 'getHistoryGuests'])->name('history_guests.getHistoryGuests');
    
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::put('/update/{a}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{a}', [UserController::class, 'destroy'])->name('destroy');
    });
    Route::get('/notulensis', [NotulensiController::class, 'index'])->name('notulensis.index');

    Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index');
    Route::get('/visitors/list', [VisitorController::class, 'list'])->name('visitors.list');

    Route::get('website-settings', [WebsiteSettingController::class, 'index'])->name('website_settings.index');
    Route::post('website-settings/create-or-update', [WebsiteSettingController::class, 'storeOrUpdate'])->name('website_settings.storeOrUpdate');
});
