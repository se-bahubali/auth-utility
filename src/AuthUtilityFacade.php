<?php

namespace StallionExpress\AuthUtility;

use Illuminate\Support\Facades\Facade;

/**
 * @see \StallionExpress\AuthUtility\Skeleton\SkeletonClass
 */
class AuthUtilityFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'authutility';
    }
}
