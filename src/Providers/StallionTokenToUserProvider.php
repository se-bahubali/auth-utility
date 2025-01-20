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

        $user = null;
        
        if ($response->successful()) {
            $data = $response->object()->data;
            $user = new User;

            $warehouses = [];
            if(isset($data->warehouses)){
                foreach ($data->warehouses as $key => $warehouse) {
                    $warehouses[$this->decodeHashValue($key)] = [
                        'name' => $warehouse->name,
                        'hash' => $this->decodeHashValue($warehouse->hash),
                        'id' => $this->decodeHashValue($warehouse->hash)
                    ];
                }
            }
            foreach ($data as $key => $value) {
                $user->{$key} = $value;
            }
            $user->id = (int)$this->decodeHashValue($data->hash);
            $user->account_id = (int)$this->decodeHashValue($data->account_id);
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
