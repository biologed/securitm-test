<?php

use Illuminate\Support\Facades\Route;
use App\Http\ApiControllers\UsersController;
use App\Http\Middleware\ForceJsonMiddleware;

Route::group(['middleware' => [ForceJsonMiddleware::class]], static function () {
    Route::get('users', [UsersController::class, 'index'])->name('users.index');
    Route::post('users', [UsersController::class, 'store'])->name('users.store');
    Route::get('users/{id}', [UsersController::class, 'show'])->name('users.show');
    Route::put('users/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
});

