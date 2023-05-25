<?php

use Illuminate\Support\Facades\Route;

Route::get('stallion-auth-login', function () {
    $state = (string) Str::ulid();
    Cache::put('link_'.$state, $_SERVER['HTTP_REFERER'] ?? config('stallionauthutility.authservice.front_end_url').'/dashboard', $seconds = 120);
    $url = config('stallionauthutility.authservice.url').'/redirect?client_id='.config('stallionauthutility.authservice.client_id').'&redirect_uri='.config('app.url').'/auth/callback'.'&state='.$state;
    return redirect($url);
});
