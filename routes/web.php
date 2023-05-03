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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/user/{id}/togglePresence', [DashboardController::class, 'togglePresence'])->name('togglePresence');
    Route::get('/dashboard/user/{id}/reportRecovery', [DashboardController::class, 'reportRecovery'])->name('reportRecovery');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/fetchEvents', [CalendarController::class, 'fetchEvents']);

    Route::resource('admin', AdminController::class);
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::put('/admin/user/{id}/update', [AdminController::class, 'update'])->name('update');
    Route::post('/admin/user/{id}/setEvent', [AdminController::class, 'setEvent'])->name('setEvent');
    Route::post('/admin/user/{id}/setSchedule', [AdminController::class, 'setSchedule'])->name('setSchedule');

    Route::get('/api/v1/users/{apiKey}/', [APIController::class, 'connectAPI'])->name('connectAPI');
});

require __DIR__.'/auth.php';
