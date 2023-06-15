<?php

namespace StallionExpress\AuthUtility;

use StallionExpress\AuthUtility\Models\User;
use StallionExpress\AuthUtility\Enums\UserTypeEnum;
use StallionExpress\AuthUtility\Trait\STEncodeDecodeTrait;

class AuthUtility
{
    use STEncodeDecodeTrait;
    // Build your next great package.

    public static function hasAbility(User $user, string $feature, string $action, object $modelObject = null)
    {    
        $hasAbility = false;

        // Check user has ability for feature
        if (isset($user->abilities->{$feature}) === true && in_array($action, $user->abilities->{$feature}) === true) {
            
            //check for model dependent authorization
            if($modelObject){
                // Check user is in same 3pl 
                if ($user->three_pl->hash == $this->decodeHashValue($modelObject->three_pl_id)) {
                    $hasAbility = true;

                    // Check user is in same 3pl customer
                    if(in_array($user->user_type->value, [(UserTypeEnum::THREE_PL_CUSTOMER)->value, (UserTypeEnum::THREE_PL_CUSTOMER_STAFF)->value])){
                        
                        
                        if ($user->three_pl_customer->hash == $this->decodeHashValue($modelObject->three_pl_customer_id)) {
                            $hasAbility = true;
                        }else{
                            $hasAbility = false;
                        }
                    }
                }
            }else{
                $hasAbility = true;
            }           
        }
        return $hasAbility;
    }
}
