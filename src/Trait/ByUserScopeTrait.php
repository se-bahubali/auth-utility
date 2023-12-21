<?php

namespace StallionExpress\AuthUtility\Trait;

use AuthUtility;

trait ByUserScopeTrait
{
    public function scopeByUser($query, $user , $userId)
    {
        return $query->when($userId && AuthUtility::isUser3plOr3plStaff($user), function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->when(\AuthUtility::isUserCustomerOrCustomerStaff($user), function ($query) use ($user) {
            $query->where('user_id', \AuthUtility::getCustomerIdFor3PlCustomerAndStaff($user));
        })
        ->when(\AuthUtility::isUser3plOr3plStaff($user), function ($query) use ($user) {
            $query->orWhere('user_id', \AuthUtility::getThreePlIdFor3PlAndStaff($user));
        });;
    }
}
