<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix("accounts")->group(function () {
    Route::get('/', [AccountController::class, 'get_all_accounts']);
    Route::post('/', [AccountController::class, 'store']);
    Route::get('{id}', [AccountController::class, 'get_by_id']);
    Route::put('{id}', [AccountController::class, 'update_by_id']);
    Route::patch('{id}', [AccountController::class, 'update_by_id']);
    Route::delete('{id}', [AccountController::class, 'delete_by_id']);
});