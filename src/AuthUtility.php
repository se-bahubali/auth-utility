<?php

namespace StallionExpress\AuthUtility;

use StallionExpress\AuthUtility\Models\User;
use StallionExpress\AuthUtility\Enums\UserTypeEnum;
use StallionExpress\AuthUtility\Trait\STEncodeDecodeTrait;

class AuthUtility
{
    use STEncodeDecodeTrait;
    // Build your next great package.

    /**
     * Check user has ability for feature
     * @param User $user
     * @param string $feature
     * @param string $action
     * @return bool
     * 
     * 1) User has ability for feature
     */
    public function hasAbility(User $user, string $feature, string $action):bool
    {    
        return isset($user->abilities->{config('stallionauthutility.microservice_name')}->{$feature}) === true && 
        in_array($action, $user->abilities->{config('stallionauthutility.microservice_name')}->{$feature}) === true;
    }
}
