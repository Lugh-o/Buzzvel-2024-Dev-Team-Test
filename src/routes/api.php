<?php

use App\Http\Controllers\Api\V1\HolidayPlanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//  api/v1
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1', 'middleware' => 'auth:sanctum'], function() {
    Route::apiResource('holidayPlans', HolidayPlanController::class);
    Route::get('holidayPlans/{id}/pdf', [HolidayPlanController::class, 'getPdf']);
});