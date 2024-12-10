<?php

// routes/api.php

use App\Http\Controllers\MyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    // Route::get('register', function () {
    //     return view('auth.register'); // Возвращает страницу с формой регистрации
    // })->name('register.form');
    
    Route::post('register', [MyController::class, 'register'])->name('register');
    Route::post('login', [MyController::class, 'login']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [MyController::class, 'me'])->name('me');
        Route::post('out', [MyController::class, 'logout']);
        Route::get('tokens', [MyController::class, 'tokens']);
        Route::post('out_all', [MyController::class, 'logoutAll']);
        Route::post('change-password', [MyController::class, 'changePassword']);

    });
});
