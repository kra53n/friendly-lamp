<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;

Route::prefix("accounts")->group(function () {
    Route::get('/', [AccountController::class, 'get_all']);
    Route::post('/', [AccountController::class, 'store']);
    Route::get('{id}', [AccountController::class, 'get_by_id']);
    Route::put('{id}', [AccountController::class, 'update_by_id']);
    Route::patch('{id}', [AccountController::class, 'update_by_id']);
    Route::delete('{id}', [AccountController::class, 'delete_by_id']);
});

Route::prefix("transactions")->group(function () {
    Route::get('/', [TransactionController::class, 'get_all']);
    Route::post('/', [TransactionController::class, 'store']);
    Route::get('{id}', [TransactionController::class, 'get_by_id']);
    Route::put('{id}', [TransactionController::class, 'update_by_id']);
    Route::patch('{id}', [TransactionController::class, 'update_by_id']);
});
