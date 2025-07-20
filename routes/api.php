<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ActivityController;
use Illuminate\Support\Facades\Route;

/*************************** Routes d'authentification ****************************   */
Route::prefix('auth')->group(function(){
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::get('me', [AuthController::class, 'me']);

/*************************** Routes des utilisateurs ****************************   */
Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::get('/users/{id}/change-status', [UserController::class, 'changeStatus']);
});

/*************************** Routes des activités ****************************   */
Route::middleware(['auth:api'])->group(function () {
    Route::get('activities', ActivityController::class);
});