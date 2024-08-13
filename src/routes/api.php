<?php

use App\Http\Controllers\Api\V1\HolidayPlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

//  api/v1
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1', 'middleware' => 'auth:sanctum'], function() {
    Route::apiResource('holidayplans', HolidayPlanController::class);
    Route::get('holidayplans/{id}/pdf', [HolidayPlanController::class, 'getPdf']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
