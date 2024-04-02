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
        return isset($user->abilities->{$feature}) === true && 
        in_array($action, $user->abilities->{$feature}) === true;
    }

    public function isUserCustomerOrCustomerStaff(User $user){
        return (in_array($user->user_type->value, [UserTypeEnum::THREE_PL_CUSTOMER->value, UserTypeEnum::THREE_PL_CUSTOMER_STAFF->value]));
    }

    public function isUser3plOr3plStaff(User $user){
        return (in_array($user->user_type->value, [UserTypeEnum::THREE_PL->value, UserTypeEnum::THREE_PL_STAFF->value]));
    }

    public function getCustomerIdFor3PlCustomerAndStaff(User $user, ?int $customerId = null)
    {
        // check only for 3pl customers or 3pl customers staff
        switch ($user->user_type->value) {
            case UserTypeEnum::THREE_PL_CUSTOMER_STAFF->value:
                $customerId = $user->three_pl_customer->hash;
                break;
            case UserTypeEnum::THREE_PL_CUSTOMER->value:
                $customerId = $user->id;
                break;
        }

        return (int) $customerId;
    }

    public function getThreePlIdFor3PlAndStaff(User $user): int
    {
        $threePlId = $user->id;

        if ($user->user_type->value == UserTypeEnum::THREE_PL_STAFF->value) {
            $threePlId = $user->three_pl->hash;
        }

        return $threePlId;
    }

    public function getLoggedUserAssociatedWarehousesIds(User $user): array
    {
        return collect($user->warehouses)->pluck('hash')->toArray();
    }

    public function getUserId(User $user , ?int $userId = null): int
    {
        if($userId && $this->isUser3plOr3plStaff($user)){
            return $userId;
        }else if($this->isUserCustomerOrCustomerStaff($user)){
            return $this->getCustomerIdFor3PlCustomerAndStaff($user);
        }else if($this->isUser3plOr3plStaff($user)){
            return $this->getThreePlIdFor3PlAndStaff($user);
        }
    }
}
