<?php

namespace StallionExpress\AuthUtility\Enums;

enum UserTypeEnum:int {
    case ADMIN = 1;
    case ADMIN_STAFF = 2;
    case THREE_PL = 3;
    case THREE_PL_STAFF = 4;
    case THREE_PL_CUSTOMER = 5;
    case THREE_PL_CUSTOMER_STAFF = 6;
}
