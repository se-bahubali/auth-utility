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

    public function retrieveById($identifier) {}

    public function retrieveByToken($identifier, $token)
    {
        $response = Http::withToken($token)
            ->withHeaders([
                'Accept' => 'application/json',
            ])->get(config('stallionauthutility.authservice.url').'/api/oauth/token/user');

        $user = null;

        if ($response->successful()) {
            $data = $response->object()->data;
            $user = new User;

            $warehouses = [];
            if (isset($data->warehouses)) {
                foreach ($data->warehouses as $key => $warehouse) {
                    $warehouses[] = [
                        'name' => $warehouse->name,
                        'id' => (int) $this->decodeHashValue($warehouse->hash),
                    ];
                }

                $data->warehouses = $warehouses;
            }
            foreach ($data as $key => $value) {
                $user->{$key} = $value;
            }
            $user->id = (int) $this->decodeHashValue($data->hash);
            $user->account_id = (int) $this->decodeHashValue($data->account_id);
        }

        return $user ?? null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // update via remember token not necessary
    }

    public function retrieveByCredentials(array $credentials) {}

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return false;
    }
}
