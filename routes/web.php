<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/events', [CalendarController::class, 'events']);

    Route::get('/users/admin', [DashboardController::class, 'admin'])->name('users.admin');
    Route::resource('users', DashboardController::class);
    Route::put('/users/{id?}/edit', [DashboardController::class, 'update'])->name('users.update');
    Route::post('/users/{id?}/edit', [DashboardController::class, 'schedule'])->name('users.schedule');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
