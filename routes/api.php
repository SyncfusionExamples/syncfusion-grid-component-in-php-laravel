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
Route::post('/students/read', [ServerController::class, 'read']);            // Read/Query
Route::post('/students/create', [ServerController::class, 'insert']);        // Insert
Route::post('/students/update', [ServerController::class, 'update']);        // Update
Route::post('/students/remove', [ServerController::class, 'remove']);        // Delete

// Student API Routes - RESTful resource endpoints
Route::get('/students', [StudentController::class, 'index']);
Route::post('/students/store', [StudentController::class, 'store']);

// Legacy endpoints for backward compatibility
Route::get('getFullData', [StudentController::class, 'index']);
Route::post('insertData', [StudentController::class, 'store']);