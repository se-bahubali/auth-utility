<?php

namespace StallionExpress\AuthUtility;

use StallionExpress\AuthUtility\Models\User;

class AuthUtility
{
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
        return isset($user->scopes->{$feature}) === true && 
        in_array($action, $user->scopes->{$feature}) === true;
    }

    public function is3PL(User $user):bool
    {
        return $user->account->is_3pl;
    }
    public function isClient(User $user):bool
    {
        return !$user->account->is_3pl;
    }

    public function account(User $user)
    {
        return $user->account;
    }

    public function accountId(User $user)
    {
        return $user->account_id;
    }
}
