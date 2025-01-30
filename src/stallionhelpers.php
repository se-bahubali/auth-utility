<?php

if (!function_exists('getFeaturesForThreepl')) {
    /**
     * This function gets the list of scopes that are granted to the specified user type.
     *
     * @return array A list of scopes.
     */
    function getFeaturesForThreepl(): array
    {
        $modules = config('modulescopes.modules');        

        $scopesToShow = [];
        foreach ($modules as $module) {
            if(config($module)['visible'])
            {
                array_push($scopesToShow, config($module)['module']);
            }            
        }

        return $scopesToShow;
    }
}

if (!function_exists('getSystemFeatures')) {
    /**
     * This function gets the list of scopes that are granted to the specified user type.
     *
     * @return array A list of scopes.
     */
    function getSystemFeatures(): array
    {
        $modules = config('modulescopes.modules');       

        $scopesToShow = [];
        foreach ($modules as $module) {
            array_push($scopesToShow, config($module));
        }

        return $scopesToShow;
    }
}

if (!function_exists('createScopesForLoggedInUserBackend')) {
    function createScopesForLoggedInUserBackend($grants): array
    {
        $finalGrants = [];

        foreach ($grants as $module => $moduleActions) {

            /**
             * check module has some depended module are not
             */
            if (isset(config($module . 'Scopes')['action_depended_other_module_scope'])) {
                /**
                 * Yes module has some depended module
                 * Get the dependent module scopes form array
                 */
                $moduleDependedScopes = config($module . 'Scopes')['action_depended_other_module_scope'];

                foreach ($moduleActions as $mAction) {
                    /**
                     * Append permitted scope to user
                     */
                    $finalGrants[$module][] = $mAction;

                    /**
                     * check action exist in the dependent scopes
                     */
                    if (isset($moduleDependedScopes[$mAction])) {

                        /**
                         * Get only those action which allowed to logged in user
                         */
                        foreach ($moduleDependedScopes[$mAction] as $dependedModule) {

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
                 * Add module to final scope.
                 */
                foreach ($moduleActions as $mAction) {
                    $finalGrants[$module][] = $mAction;
                }
            }
        }
        return $finalGrants;
    }
}
