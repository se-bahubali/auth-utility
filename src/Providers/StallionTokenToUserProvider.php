<?php

namespace StallionExpress\AuthUtility\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Http;
use StallionExpress\AuthUtility\Models\User;

class StallionTokenToUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        return null;
    }

    public function retrieveByToken($identifier, $token)
    {
        $response = Http::withToken($token)
            ->withHeaders([
                'Accept' => 'application/json',
            ])->get(config('stallionauthutility.authservice.url') . '/api/oauth/token/user');

        if ($response->successful()) {
            $data = $response->object();

            $user = new User;

            [$user->id, $user->email, $user->abilities, $user->user_type, $user->three_pl, $user->three_pl_customer] =
                [$data->id, $data->email, $data->scopes, $data->user_type, $data->three_pl, $data->three_pl_customer];
        }

        return $user ?? null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // update via remember token not necessary
    }

    public function retrieveByCredentials(array $credentials)
    {
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return false;
    }
}
