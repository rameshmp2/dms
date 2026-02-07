<?php
// routes/api.php

use App\Http\Controllers\Api\DocumentApiController;
use App\Http\Controllers\Api\AuthApiController;

Route::post('login', [AuthApiController::class, 'login']);
Route::post('register', [AuthApiController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthApiController::class, 'user']);
    Route::post('logout', [AuthApiController::class, 'logout']);
    
    Route::apiResource('documents', DocumentApiController::class);
    Route::post('documents/{id}/download', [DocumentApiController::class, 'download']);
    Route::get('documents/{id}/versions', [DocumentApiController::class, 'versions']);
});