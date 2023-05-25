<?php

use Illuminate\Support\Facades\Route;

Route::get('login', function () {
    $url = config('services.authservice.url') . '/redirect?client_id=' . config('services.authservice.client_id') . '&redirect_uri=' . config('app.url') . '/auth/callback';
    return redirect($url);
});
