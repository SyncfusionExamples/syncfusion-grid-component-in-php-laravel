<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\ServerController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ==========================================
// Syncfusion DataManager UrlAdaptor Routes
// ==========================================
Route::post('/read', [ServerController::class, 'read']);            // Read/Query
Route::post('/insert', [ServerController::class, 'insert']);        // Insert
Route::post('/update', [ServerController::class, 'update']);        // Update
Route::post('/remove', [ServerController::class, 'remove']);        // Delete