<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\APIController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
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
    return redirect('dashboard');
});

Route::get('/api/v1/users/{rfidToken}/apiTogglePresence/{apiKey}', [APIController::class, 'apiTogglePresence'])->name('apiTogglePresence');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kiosk', [DashboardController::class, 'displayKioskMode'])->name('kiosk');
    Route::get('/dashboard/user/{id}/togglePresence', [DashboardController::class, 'togglePresence'])->name('togglePresence');
    Route::get('/dashboard/user/{id}/reportRecovery', [DashboardController::class, 'reportRecovery'])->name('reportRecovery');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/fetchEvents', [CalendarController::class, 'fetchEvents']);

    Route::middleware('role')->group(function () {
        Route::resource('admin', AdminController::class);
        Route::get('/admin', [AdminController::class, 'index'])->name('admin');
        Route::get('/admin/user/{id}/logs', [DashboardController::class, 'showLogs'])->name('showLogs');
        Route::post('/admin/store', [AdminController::class, 'store'])->name('store');
        Route::put('/admin/user/{id}/update', [AdminController::class, 'update'])->name('update');
        Route::post('/admin/user/{id}/setEvent', [AdminController::class, 'setEvent'])->name('setEvent');
        Route::get('/admin/user/{id}/editDynamicWeekField/{week}', [AdminController::class, 'editDynamicWeekField'])->name('editDynamicWeekField');
        Route::post('/admin/user/{id}/setSchedule', [AdminController::class, 'setSchedule'])->name('setSchedule');
    });
});

require __DIR__.'/auth.php';
