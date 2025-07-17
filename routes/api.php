<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*************************** Routes d'authentification ****************************   */
Route::prefix('auth')->group(function(){
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

/*************************** Routes des utilisateurs ****************************   */
Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::get('/change-status/{id}', [UserController::class, 'changeStatus']);
});