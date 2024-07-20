<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;

Route::post('/register', [AuthController::class, 'register']);


Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('files', FileController::class);

    // Route pour approuver un fichier
    Route::put('files/{file}/approve', [FileController::class, 'approve']);
});
