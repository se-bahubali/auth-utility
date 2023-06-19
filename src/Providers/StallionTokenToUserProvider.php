<?php

namespace StallionExpress\AuthUtility\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Http;
use StallionExpress\AuthUtility\Enums\UserTypeEnum;
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
            $data = $response->object();
            $user = new User;

            $warehouses = [];
            if(isset($data->warehouses)){
                foreach ($data->warehouses as $key => $warehouse) {
                    $warehouses[$this->decodeHashValue($key)] = [
                        'name' => $warehouse->name,
                        'hash' => $this->decodeHashValue($warehouse->hash)
                    ];
                }
            }

            [$user->id, $user->hash, $user->warehouses, $user->details, $user->user_type, $user->abilities] = 
            [$this->decodeHashValue($data->hash), $data->hash, $warehouses, $data->details, $data->user_type, $data->scopes];

            if($data->user_type == UserTypeEnum::THREE_PL_STAFF->value){
                $user->three_pl = $data->three_pl;
                $user->three_pl->hash = $this->decodeHashValue($data->three_pl->hash);
            }

            if($data->user_type == UserTypeEnum::THREE_PL_CUSTOMER_STAFF->value){
                $user->three_pl_customer = $data->three_pl_customer;
                $user->three_pl_customer->hash = $this->decodeHashValue($data->three_pl_customer->hash);
            }
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
