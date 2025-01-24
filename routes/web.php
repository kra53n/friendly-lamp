<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix("accounts")->group(function () {
    Route::get('/', [AccountController::class, 'get_all_accounts']);
    Route::post('/', [AccountController::class, 'store']);
});