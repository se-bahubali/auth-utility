<?php

namespace StallionExpress\AuthUtility\Trait;

use AuthUtility;

trait ByUserScopeTrait
{
    public function scopeByUser($query, $user , $userId)
    {
        return $query->when($userId && AuthUtility::isUser3plOr3plStaff($user), function ($query) use ($userId) {
            $query->where('user_id', $userId); // 3pl wants to check client record
        }, function ($query) use ($user) {
            $query->when(\AuthUtility::isUser3plOr3plStaff($user), function ($query) use ($user) {
                $query->where('user_id', \AuthUtility::getThreePlIdFor3PlAndStaff($user));
            });
        })
        ->when(\AuthUtility::isUserCustomerOrCustomerStaff($user), function ($query) use ($user) {
            $query->where('user_id', \AuthUtility::getCustomerIdFor3PlCustomerAndStaff($user));
        });
    }
}
