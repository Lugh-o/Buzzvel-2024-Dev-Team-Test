<?php

use Illuminate\Support\Facades\Route;


Route::get('/swagger', function () {
    return response()->file(public_path('swagger/index.html'));
});