<?php

namespace StallionExpress\AuthUtility\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Http;
use StallionExpress\AuthUtility\Models\User;
use StallionExpress\AuthUtility\Trait\STEncodeDecodeTrait;

class StallionTokenToUserProvider implements UserProvider
{
    use STEncodeDecodeTrait;

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

            if (isset($data->three_pl->hash)) {
                $data->three_pl->hash = $this->decodeHashValue($data->three_pl->hash);
            }

            if (isset($data->three_pl_customer->hash)) {
                $data->three_pl_customer->hash = $this->decodeHashValue($data->three_pl_customer->hash);
            }

            if(isset($data->warehouses) && is_array($data->warehouses)){
                foreach ($data->warehouses as $key => $warehouse) {
                    $data->warehouses[$key]->hash = $this->decodeHashValue($warehouse->hash);
                }
            }

            $user = $data;
            $user->id = $this->decodeHashValue($data->hash);
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
