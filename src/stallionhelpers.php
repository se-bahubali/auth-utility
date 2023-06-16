<?php

use StallionExpress\AuthUtility\Enums\UserTypeEnum;
use StallionExpress\AuthUtility\Models\User;

function getCustomerId(User $user, int $customerId = null)
{
    // check only for 3pl customers or 3pl customers staff
    switch ($user->user_type->value) {
        case UserTypeEnum::THREE_PL_CUSTOMER_STAFF->value:
            $customerId = $user->three_pl_customer[0]->id;
            break;
        case UserTypeEnum::THREE_PL_CUSTOMER->value:
            $customerId = $user->id;
            break;

    }

    return $customerId;
}

if (!function_exists('getScopesByUserType')) {
/**
 * This function gets the list of scopes that are granted to the specified user type.
 *
 * @param int $userType The user type.
 * @return array A list of scopes.
 */
    function getScopesByUserType(int $userType): array
    {
        $modules = config('permissions.modules');

        $permissionsToShow = [];
        foreach ($modules as $module) {
            // Check if the user type is granted access to the module.
            if (in_array($userType, config($module)['granted_to'])) {
                // Add the module to the list of permissions to show.
                array_push($permissionsToShow, config($module)['module']);
            }
        }

        return $permissionsToShow;
    }
}

if (!function_exists('createFinalScopesForLoggedInUserBackend')) {
    function createFinalScopesForLoggedInUserBackend($grants): array
    {
        $finalGrants = [];

        foreach ($grants as $module => $moduleActions) {

            $modulePermission = [];
            /**
             * check module has some depended module are not
             */
            if (isset(config($module . 'Permission')['action_depended_other_module_scope'])) {
                /**
                 * Yes module has some depended module
                 * Get the dependent module permissions form array
                 */
                $moduleDependedPermissions = config($module . 'Permission')['action_depended_other_module_scope'];

                foreach ($moduleActions as $mAction) {
                    /**
                     * Append permitted scope to user
                     */
                    $finalGrants[$module][] = $mAction;

                    /**
                     * check action exist in the dependent scopes
                     */
                    if (isset($moduleDependedPermissions[$mAction])) {

                        /**
                         * Get only those action which allowed to logged in user
                         */
                        foreach ($moduleDependedPermissions[$mAction] as $dependedModule) {

                            $dependedModuleData = explode('.', $dependedModule);

                            if (isset($finalGrants[$dependedModuleData[0]])) {

                                if (!in_array($dependedModuleData[1], $finalGrants[$dependedModuleData[0]])) {
                                    /**
                                     * Depended module not exist in final scope add it
                                     */
                                    $finalGrants[$dependedModuleData[0]][] = $dependedModuleData[1];
                                }
                            } else {
                                /**
                                 * Depended module not exist in final scope add it
                                 */
                                $finalGrants[$dependedModuleData[0]][] = $dependedModuleData[1];
                            }
                        }
                    }
                }
            } else {
                /**
                 * Add module to final permission.
                 */
                foreach ($moduleActions as $mAction) {
                    $finalGrants[$module][] = $mAction;
                }
            }
        }
        return $finalGrants;
    }
}
