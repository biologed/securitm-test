<?php

use Illuminate\Support\Facades\Route;
use App\Http\ApiControllers\UsersController;
use App\Http\Middleware\ForceJsonMiddleware;

Route::name('api.users.')->middleware([ForceJsonMiddleware::class])->group(static function () {
    Route::get('users', [UsersController::class, 'index'])->name('index');
    Route::post('users', [UsersController::class, 'store'])->name('store');
    Route::get('users/{id}', [UsersController::class, 'show'])->name('show');
    Route::put('users/{id}', [UsersController::class, 'update'])->name('update');
    Route::delete('users/{id}', [UsersController::class, 'destroy'])->name('destroy');
});

