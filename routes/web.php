<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InfoController;

// cоздаём маршрут  который обрабатывает GET-запрос на URL /info/server
Route::get('/info/server', [InfoController::class, 'serverInfo']);  // будет вызван метод serverInfo из контроллера InfoController

// маршрут для получения данных о клиенте (IP и User Agent)
Route::get('/info/client', [InfoController::class, 'clientInfo']);

// маршрут для получения данных о базе данных (тип подключения и название базы)
Route::get('/info/database', [InfoController::class, 'databaseInfo']);


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
