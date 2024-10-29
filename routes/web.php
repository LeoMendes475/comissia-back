<?php

use App\Http\Controllers\Admin\{ReplySupportController, SupportController};
use App\Http\Controllers\UserController;
use App\Http\Controllers\AffiliatesController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\Site\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::group([
    'prefix' => 'user',
], function () {
    Route::post('/create', [UserController::class, 'create']);
    Route::get('/findAll', [UserController::class, 'findAll']);
    Route::patch('/update/{id}', [UserController::class, 'update']);
    Route::get('/findById/{id}', [UserController::class, 'findById']);
    Route::put('/deactivate/{id}', [UserController::class, 'deactivate']);
});

Route::group([
    'prefix' => 'affiliates',
], function () {
    Route::post('/create', [AffiliatesController::class, 'create']);
    Route::get('/findAll', [AffiliatesController::class, 'findAll']);
    Route::patch('/update/{id}', [AffiliatesController::class, 'update']);
    Route::get('/findById/{id}', [AffiliatesController::class, 'findById']);
    Route::put('/deactivate/{id}', [AffiliatesController::class, 'deactivate']);
});

Route::group([
    'prefix' => 'commission',
], function () {
    Route::post('/create', [CommissionController::class, 'create']);
    Route::get('/findAll', [CommissionController::class, 'findAll']);
    Route::patch('/update/{id}', [CommissionController::class, 'update']);
    Route::get('/findById/{id}', [CommissionController::class, 'findById']);
    Route::delete('/delete/{id}', [CommissionController::class, 'delete']);
});
require __DIR__ . '/auth.php';
