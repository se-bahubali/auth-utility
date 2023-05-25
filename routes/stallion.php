<?php

use Illuminate\Support\Facades\Route;
use StallionExpress\AuthUtility\Controllers\StallionAuthController;

Route::middleware(['decode_st'])->group(function () {
    Route::get('/auth/callback', [StallionAuthController::class, 'getAccessToken']);

    Route::get('/login', [StallionAuthController::class, 'login']);

    Route::get('api/stallion/access/token/{id}', [StallionAuthController::class, 'returnToken']);

});
