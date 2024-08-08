<?php

use App\Http\Controllers\Api\V1\HolidayPlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//  api/v1
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1', 'middleware' => 'auth:sanctum'], function() {
    Route::apiResource('holidayPlans', HolidayPlanController::class);
    Route::get('holidayPlans/{id}/pdf', [HolidayPlanController::class, 'getPdf']);
});