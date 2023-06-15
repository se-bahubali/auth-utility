<?php

use StallionExpress\AuthUtility\Enums\UserTypeEnum;
use StallionExpress\AuthUtility\Models\User;

function getCustomerId(User $user, int $customerId = null)
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

    return $customerId;
}

function getThreePlId(User $user):int{
    $threePlId = $user->id;

    if($user->user_type == UserTypeEnum::THREE_PL_STAFF->value){
        $threePlId = $user->three_pl->hash;
    }

    return $threePlId;
}
