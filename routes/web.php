<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::resource('users', UserController::class);

Route::get('/', function () {
    return redirect('users');
});

Route::get('/users/admin', [UserController::class, 'admin'])->name('users.admin');

Route::post('/users/{id?}/edit', [UserController::class, 'update'])->name('users.update');

